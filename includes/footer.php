<?php
// footer.php, $pdo bağlantısı zaten hazır olan bir sayfadan include edildiği için
// (tüm sayfalar en başta config/db.php'yi require eder) doğrudan kullanılabilir.
$footerSosyalHesaplar = $pdo->query("SELECT * FROM sosyal_medya WHERE aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();
$footerSiteAyar = $pdo->query("SELECT * FROM site_ayarlari WHERE id = 1")->fetch();
if (!$footerSiteAyar) {
    $footerSiteAyar = ['adres' => '', 'telefon' => '', 'eposta' => ''];
}
?>
<footer class="footer-alan text-light pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-3 d-flex align-items-center">
                    <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg"
                         alt="Gebze Belediyesi Logo" class="navbar-logo me-2" style="filter: brightness(0) invert(1);">
                    GEBZE BELEDİYESİ
                </h5>
                <div class="mt-3 mb-3">
                    <?php foreach ($footerSosyalHesaplar as $fsIndex => $fs): ?>
                    <a href="<?php echo htmlspecialchars($fs['url']); ?>" target="_blank" class="text-light<?php echo $fsIndex < count($footerSosyalHesaplar) - 1 ? ' me-3' : ''; ?>"><i class="bi <?php echo htmlspecialchars($fs['ikon']); ?> fs-5"></i></a>
                    <?php endforeach; ?>
                </div>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="bi bi-telephone-fill me-2"></i><?php echo htmlspecialchars($footerSiteAyar['telefon']); ?></li>
                    <li class="mb-2"><i class="bi bi-envelope-fill me-2"></i><?php echo htmlspecialchars($footerSiteAyar['eposta']); ?></li>
                    <li class="mb-2"><i class="bi bi-geo-alt-fill me-2"></i><?php echo htmlspecialchars($footerSiteAyar['adres']); ?></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Hızlı Erişim</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/index.php" class="footer-link"><i class="bi bi-house-door-fill me-2"></i>Ana Sayfa</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/e-belediye/e-belediye.php" class="footer-link"><i class="bi bi-laptop me-2"></i>E-Belediye</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/hizmetler/hizmetler.php" class="footer-link"><i class="bi bi-grid-fill me-2"></i>Hizmetlerimiz</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/etkinlikler/etkinlikler.php" class="footer-link"><i class="bi bi-calendar-event-fill me-2"></i>Etkinlikler</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/haberler/haberler.php" class="footer-link"><i class="bi bi-newspaper me-2"></i>Haberler</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/haberler/duyurular.php" class="footer-link"><i class="bi bi-megaphone-fill me-2"></i>Duyurular</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/genel/iletisim.php" class="footer-link"><i class="bi bi-telephone-fill me-2"></i>İletişim</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Kurumsal</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/genel/hakkimizda.php" class="footer-link">Başkan</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php?sayfa=vizyon" class="footer-link">Vizyonumuz</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php?sayfa=misyon" class="footer-link">Misyonumuz</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php?sayfa=ilkeler" class="footer-link">İlkelerimiz</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/enerji-politikamiz.php" class="footer-link">Enerji Politikamız</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/belediye-meclisi.php" class="footer-link">Belediye Meclisi</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/yonetim-semasi.php" class="footer-link">Yönetim Şeması</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/baskan-yardimcilari.php" class="footer-link">Başkan Yardımcıları</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/baskan-danismanlari.php" class="footer-link">Başkan Danışmanları</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/mudurlukler.php" class="footer-link">Müdürlükler</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/eski-baskanlar.php" class="footer-link">Eski Başkanlar</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/arabuluculuk-komisyonu.php" class="footer-link">Arabuluculuk Komisyonu</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/etik-komisyonu.php" class="footer-link">Etik Komisyonu</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/meclis-kararlari.php" class="footer-link">Meclis Kararları</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal-kimlik.php" class="footer-link">Kurumsal Kimlik</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal-raporlar.php" class="footer-link">Kurumsal Raporlar</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/kurumsal.php?sayfa=dokumanlar" class="footer-link">Kurumsal Dökümanlar</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/yayinlar.php" class="footer-link">Yayınlar</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/kurumsal/kvkk.php" class="footer-link">KVKK Aydınlatma Metni</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Gebze</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/gebze/gebze.php" class="footer-link">Tarihçe</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/gebze/bugunku-gebze.php" class="footer-link">Bugünkü Gebze</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/gebze/muhtarlar.php" class="footer-link">Mahalle Muhtarları</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/gebze/kent-rehberi.php" class="footer-link">Tarihi Yerler</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/gebze/fotograflarla-gebze.php" class="footer-link">Fotoğraflarla Gebze</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/gebze/kardes-sehirler.php" class="footer-link">Kardeş Şehirler</a></li>
                    <li class="mb-2"><a href="<?php echo SITE_KOK; ?>/gebze/uye-birlikler.php" class="footer-link">Üye Olduğumuz Birlikler</a></li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <p class="text-center small text-light-50 mb-0">
            &copy; <?php echo date('Y'); ?> Gebze Belediyesi. Tüm hakları saklıdır.
        </p>
    </div>
</footer>

<!-- Video Oynatma Modalı: Videolar bölümündeki thumbnail'lara tıklanınca YouTube'a
     yönlendirmek yerine videoyu sayfanın ortasında bir kutucuk içinde açar. -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content video-modal-icerik">
            <button type="button" class="btn-close btn-close-white video-modal-kapat" data-bs-dismiss="modal" aria-label="Kapat"></button>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="videoModalIframe" src="" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo SITE_KOK; ?>/js/script.js?v=2"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var videoModalEl = document.getElementById('videoModal');
    if (!videoModalEl) return;
    var iframe = document.getElementById('videoModalIframe');

    videoModalEl.addEventListener('show.bs.modal', function (event) {
        var tetikleyici = event.relatedTarget;
        if (!tetikleyici) return;
        var videoId = tetikleyici.getAttribute('data-video-id');
        if (videoId) {
            iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
        }
    });

    videoModalEl.addEventListener('hidden.bs.modal', function () {
        iframe.src = '';
    });
});
</script>
