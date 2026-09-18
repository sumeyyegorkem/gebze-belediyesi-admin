<?php
/**
 * includes/fotograf-galerisi-bolum.php
 * -------------------------------------------------------
 * Tekrar kullanılabilir "Fotoğraf Galerisi" bölümü.
 * Admin panelindeki "Fotoğraf Galerisi" (admin/fotograf-galerisi.php)
 * sayfasından eklenen fotoğrafları, ait oldukları bölüme göre listeler.
 * Tarihçe, Bugünkü Gebze, Tarihi Yerler, Fotoğraflarla Gebze, Hizmetler,
 * Haberler, Faaliyet Alanları ve Etkinlikler sayfalarında kullanılır.
 *
 * Include etmeden önce tanımlanabilecek değişkenler:
 *   $galeriSayfa        -> hangi bölümün fotoğrafları gösterilecek (zorunlu, örn: 'tarihce')
 *   $galeriBaslikGoster -> "Fotoğraf Galerisi" başlığı gösterilsin mi (varsayılan: true)
 *   $galeriBaslikMetni  -> başlık metni özelleştirilebilir (varsayılan: 'Fotoğraf Galerisi')
 * -------------------------------------------------------
 */
if (!isset($pdo)) { require_once __DIR__ . '/../config/db.php'; }

$galeriSayfa = $galeriSayfa ?? 'genel';
$galeriBaslikGoster = $galeriBaslikGoster ?? true;
$galeriBaslikMetni = $galeriBaslikMetni ?? 'Fotoğraf Galerisi';

// Tablo yoksa oluştur; "sayfa" sütunu yoksa otomatik ekle (elle migrasyon gerekmez).
// Bu kontrol gebze/kent-rehberi.php'de de kullanıldığı için ortak dosyaya taşındı.
require_once __DIR__ . '/fotograf-galerisi-tablo-kontrol.php';

// Tablo tamamen boşsa, sitede daha önce sabit olarak duran 4 fotoğrafla ilk kaydı oluşturuyoruz
$galeriToplamSayi = $pdo->query("SELECT COUNT(*) AS toplam FROM fotograf_galerisi")->fetch()['toplam'];
if ($galeriToplamSayi == 0) {
    $pdo->exec("INSERT INTO fotograf_galerisi (baslik, resim_url, sayfa) VALUES
        ('Gebze', 'img/galeri-1.jpg', 'fotograflar'),
        ('Gebze', 'img/galeri-2.jpg', 'fotograflar'),
        ('Gebze', 'img/galeri-3.jpg', 'fotograflar'),
        ('Gebze', 'img/galeri-4.webp', 'fotograflar')");
}

$galeriStmt = $pdo->prepare("SELECT * FROM fotograf_galerisi WHERE sayfa = :sayfa ORDER BY id ASC");
$galeriStmt->execute(['sayfa' => $galeriSayfa]);
$galeriFotograflari = $galeriStmt->fetchAll();
?>
<?php // Hiç fotoğraf eklenmemişse bölüm (başlık dahil) tamamen gizlenir; ziyaretçiye
      // "henüz fotoğraf eklenmemiş" gibi boş bir uyarı gösterilmez, sadece fotoğraf
      // eklenince bölüm kendiliğinden ortaya çıkar. ?>
<?php if (count($galeriFotograflari) > 0): ?>
    <?php if ($galeriBaslikGoster): ?>
    <h6 class="fw-bold mt-4 mb-3"><i class="bi bi-images me-1"></i> <?php echo htmlspecialchars($galeriBaslikMetni); ?></h6>
    <?php endif; ?>
    <div class="row g-3 mb-2">
        <?php foreach ($galeriFotograflari as $gf): ?>
            <?php
                // resim_url veritabanında bazen tam bağlantı (https://...) bazen de
                // kök dizine göre kısa yol ("img/x.jpg") olarak saklanıyor. Tam
                // bağlantıysa olduğu gibi kullan, kısa yolsa başına SITE_KOK ekle.
                $gfResimSrc = preg_match('/^https?:\/\//i', $gf['resim_url'])
                    ? $gf['resim_url']
                    : SITE_KOK . '/' . $gf['resim_url'];
            ?>
            <div class="col-md-3 col-6">
                <img src="<?php echo htmlspecialchars($gfResimSrc); ?>" class="img-fluid rounded-3 w-100 galeri-foto-buyutulebilir"
                     style="height:160px;object-fit:cover;cursor:pointer;" alt="<?php echo htmlspecialchars($gf['baslik'] ?: 'Gebze'); ?>"
                     onerror="this.src='https://placehold.co/400x300?text=Fotoğraf+Ekleyin';">
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Fotoğrafa tıklayınca büyük halinin göründüğü kutucuk -->
    <div class="modal fade" id="galeriBuyutmeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <button type="button" class="btn-close btn-close-white position-absolute" data-bs-dismiss="modal" aria-label="Kapat"
                        style="top:-38px;right:0;z-index:2;"></button>
                <button type="button" class="btn btn-light rounded-circle galeri-onceki d-flex align-items-center justify-content-center"
                        aria-label="Önceki fotoğraf" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:44px;height:44px;z-index:2;opacity:.85;">
                    <i class="bi bi-chevron-left fs-5"></i>
                </button>
                <img src="" id="galeriBuyukResim" class="img-fluid rounded-3 w-100" alt="">
                <button type="button" class="btn btn-light rounded-circle galeri-sonraki d-flex align-items-center justify-content-center"
                        aria-label="Sonraki fotoğraf" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);width:44px;height:44px;z-index:2;opacity:.85;">
                    <i class="bi bi-chevron-right fs-5"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var buyutmeModalEl = document.getElementById('galeriBuyutmeModal');
        if (!buyutmeModalEl) return;
        var buyutmeModal = new bootstrap.Modal(buyutmeModalEl);
        var buyukResim = document.getElementById('galeriBuyukResim');
        var oncekiBtn = buyutmeModalEl.querySelector('.galeri-onceki');
        var sonrakiBtn = buyutmeModalEl.querySelector('.galeri-sonraki');
        var fotograflar = Array.prototype.slice.call(document.querySelectorAll('.galeri-foto-buyutulebilir'));
        var aktifIndex = 0;

        if (fotograflar.length <= 1) {
            oncekiBtn.style.display = 'none';
            sonrakiBtn.style.display = 'none';
        }

        function fotografiGoster(index) {
            aktifIndex = (index + fotograflar.length) % fotograflar.length;
            var secilen = fotograflar[aktifIndex];
            buyukResim.src = secilen.src;
            buyukResim.alt = secilen.alt;
        }

        fotograflar.forEach(function (resim, index) {
            resim.addEventListener('click', function () {
                fotografiGoster(index);
                buyutmeModal.show();
            });
        });

        oncekiBtn.addEventListener('click', function () { fotografiGoster(aktifIndex - 1); });
        sonrakiBtn.addEventListener('click', function () { fotografiGoster(aktifIndex + 1); });

        buyutmeModalEl.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowLeft') fotografiGoster(aktifIndex - 1);
            if (e.key === 'ArrowRight') fotografiGoster(aktifIndex + 1);
        });
    });
    </script>
<?php endif; ?>
