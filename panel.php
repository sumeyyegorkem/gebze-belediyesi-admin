<?php
require_once 'oturum_kontrol.php'; // giriş kontrolü (session_start burada yapılıyor)
require_once '../config/db.php';

// Haber ve duyuru yönetimi artık kendi sayfalarında (haberler-yonet.php / duyurular-yonet.php);
// anasayfada sadece toplam sayıları gösterildiği için hafif COUNT sorguları yeterli
$haberSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM haberler")->fetch()['toplam'];
$duyuruSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM duyurular")->fetch()['toplam'];

// Okunmamış mesaj sayısı
$mesajSayisi = $pdo->query("SELECT COUNT(*) AS toplam FROM iletisim_mesajlari WHERE okundu = 0")->fetch()['toplam'];
$emlakTalepSayisi = $pdo->query("SELECT COUNT(*) AS toplam FROM emlak_sorgu_talepleri WHERE durum = 'beklemede'")->fetch()['toplam'];
$nikahTalepSayisi = $pdo->query("SELECT COUNT(*) AS toplam FROM nikah_randevu_talepleri WHERE durum = 'beklemede'")->fetch()['toplam'];
$fenTalepSayisi = $pdo->query("SELECT COUNT(*) AS toplam FROM fen_isleri_talepleri WHERE durum = 'beklemede'")->fetch()['toplam'];
$zabitaIhbarSayisi = $pdo->query("SELECT COUNT(*) AS toplam FROM zabita_ihbarlari WHERE durum = 'beklemede'")->fetch()['toplam'];
$temizlikTalepSayisi = $pdo->query("SELECT COUNT(*) AS toplam FROM temizlik_talepleri WHERE durum = 'beklemede'")->fetch()['toplam'];
$kulturTalepSayisi = $pdo->query("SELECT COUNT(*) AS toplam FROM kultur_talepleri WHERE durum = 'beklemede'")->fetch()['toplam'];
$veterinerIhbarSayisi = $pdo->query("SELECT COUNT(*) AS toplam FROM veteriner_ihbarlari WHERE durum = 'beklemede'")->fetch()['toplam'];

$toplamBeklemedeTalep = $mesajSayisi + $emlakTalepSayisi + $nikahTalepSayisi + $fenTalepSayisi
    + $zabitaIhbarSayisi + $temizlikTalepSayisi + $kulturTalepSayisi + $veterinerIhbarSayisi;

// Son Aktiviteler paneli için gerçek, en güncel kayıtlar (arama filtresinden bağımsız)
$sonHaberler = $pdo->query("SELECT id, baslik, yayin_tarihi FROM haberler ORDER BY yayin_tarihi DESC LIMIT 3")->fetchAll();
$sonDuyurular = $pdo->query("SELECT id, baslik, yayin_tarihi FROM duyurular ORDER BY yayin_tarihi DESC LIMIT 3")->fetchAll();
$sonMesajlar = $pdo->query("SELECT id, ad_soyad, konu, gonderim_tarihi FROM iletisim_mesajlari ORDER BY gonderim_tarihi DESC LIMIT 3")->fetchAll();

$aktiviteAkisi = [];
foreach ($sonHaberler as $h) {
    $aktiviteAkisi[] = ['tarih' => $h['yayin_tarihi'], 'renk' => 'text-primary', 'metin' => 'Yeni haber eklendi: ' . $h['baslik'], 'link' => 'haber-duzenle.php?id=' . $h['id']];
}
foreach ($sonDuyurular as $d) {
    $aktiviteAkisi[] = ['tarih' => $d['yayin_tarihi'], 'renk' => 'text-success', 'metin' => 'Yeni duyuru eklendi: ' . $d['baslik'], 'link' => 'duyuru-duzenle.php?id=' . $d['id']];
}
foreach ($sonMesajlar as $m) {
    $aktiviteAkisi[] = ['tarih' => $m['gonderim_tarihi'], 'renk' => 'text-warning', 'metin' => $m['ad_soyad'] . ' mesaj gönderdi: ' . $m['konu'], 'link' => 'mesaj-detay.php?id=' . $m['id']];
}
usort($aktiviteAkisi, function ($a, $b) { return strtotime($b['tarih']) <=> strtotime($a['tarih']); });
$aktiviteAkisi = array_slice($aktiviteAkisi, 0, 6);

$adminAd = $_SESSION['admin_ad'] ?? 'Yönetici';
$yoneticiSayisi = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM yoneticiler")->fetch()['toplam'];

// Anasayfadaki "Yöneticiler" widget'ı için son eklenen yöneticiler
$panelYoneticiler = $pdo->query("SELECT id, ad_soyad, kullanici_adi, fotograf, avatar_rengi, unvan FROM yoneticiler ORDER BY olusturma_tarihi ASC LIMIT 5")->fetchAll();
$mevcutAdminId = (int)($_SESSION['admin_id'] ?? 0);

// SEO alanları henüz kurulmadıysa anasayfada bir kerelik hatırlatma göster
$seoAlanlariHazir = $pdo->query("SHOW COLUMNS FROM haberler LIKE 'seo_baslik'")->rowCount() > 0;

$sayfaBasligi = 'Anasayfa';
$aktifMenu = 'anasayfa';
include 'includes/admin-baslangic.php';
?>

<div class="admin-karsilama">
    <h4><i class="bi bi-emoji-smile me-1"></i> Hoş Geldiniz, <?php echo htmlspecialchars($adminAd); ?>!</h4>
    <p>Gebze Belediyesi Yönetim Paneline hoş geldiniz. Sistem durumunu ve son aktiviteleri buradan takip edebilirsiniz.</p>
</div>

<?php if (!$seoAlanlariHazir): ?>
    <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-search-heart me-2"></i>Haberler, Duyurular, Etkinlikler ve Projeler için SEO alanları henüz kurulmadı.</span>
        <a href="seo_migrasyon.php" class="btn btn-sm btn-belediye">SEO Alanlarını Kur</a>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="admin-istatistik-kutu admin-istatistik-kutu--mavi">
            <div class="ikon-cip"><i class="bi bi-newspaper"></i></div>
            <div>
                <div class="sayi"><?php echo $haberSayisi; ?></div>
                <div class="etiket">Toplam Haber</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="admin-istatistik-kutu admin-istatistik-kutu--yesil">
            <div class="ikon-cip"><i class="bi bi-megaphone-fill"></i></div>
            <div>
                <div class="sayi"><?php echo $duyuruSayisi; ?></div>
                <div class="etiket">Toplam Duyuru</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="admin-istatistik-kutu admin-istatistik-kutu--turuncu">
            <div class="ikon-cip"><i class="bi bi-envelope-fill"></i></div>
            <div>
                <div class="sayi"><?php echo (int)$mesajSayisi; ?></div>
                <div class="etiket">Okunmamış Mesaj</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="admin-istatistik-kutu admin-istatistik-kutu--mor">
            <div class="ikon-cip"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="sayi"><?php echo (int)$toplamBeklemedeTalep; ?></div>
                <div class="etiket">Bekleyen Talep/İhbar</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-4 d-flex flex-column gap-3">
        <div class="card admin-kart admin-kart-yatay">
            <div class="card-body">
                <div class="admin-panel-baslik">Hızlı İşlemler</div>
                <div class="admin-hizli-grid">
                    <a href="haberler-yonet.php" class="admin-hizli-islem admin-hizli-islem--mavi">
                        <div class="ikon-cip"><i class="bi bi-newspaper"></i></div>
                        <span class="baslik">Haberler Yönetimi</span>
                    </a>
                    <a href="etkinlik-yonet.php" class="admin-hizli-islem admin-hizli-islem--yesil">
                        <div class="ikon-cip"><i class="bi bi-calendar-event-fill"></i></div>
                        <span class="baslik">Etkinlik Yönetimi</span>
                    </a>
                    <a href="projeler-yonet.php" class="admin-hizli-islem admin-hizli-islem--mor">
                        <div class="ikon-cip"><i class="bi bi-kanban-fill"></i></div>
                        <span class="baslik">Proje Yönetimi</span>
                    </a>
                    <a href="mesajlar-yonet.php" class="admin-hizli-islem admin-hizli-islem--turuncu">
                        <div class="ikon-cip"><i class="bi bi-envelope-fill"></i></div>
                        <span class="baslik">Mesaj Yönetimi</span>
                        <?php if ($mesajSayisi > 0): ?><span class="admin-hizli-rozet"><?php echo $mesajSayisi; ?></span><?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="card admin-kart admin-kart-yatay">
            <div class="card-body">
                <div class="admin-panel-baslik">Yöneticiler</div>
                <?php if (count($panelYoneticiler) === 0): ?>
                    <div class="admin-bos-aktivite">Henüz yönetici eklenmemiş.</div>
                <?php else: ?>
                    <div class="admin-yonetici-liste">
                        <?php foreach ($panelYoneticiler as $y): ?>
                            <div class="admin-yonetici-satir">
                                <?php echo adminAvatarHtml($y['ad_soyad'], $y['fotograf'] ?? null, $y['avatar_rengi'] ?? 'mavi', 'admin-mini-avatar'); ?>
                                <div>
                                    <span class="admin-yonetici-ad">
                                        <?php echo htmlspecialchars($y['ad_soyad']); ?>
                                        <?php if ((int)$y['id'] === $mevcutAdminId): ?>
                                            <span class="badge bg-info">Siz</span>
                                        <?php endif; ?>
                                    </span>
                                    <span class="admin-yonetici-kullanici">
                                        @<?php echo htmlspecialchars($y['kullanici_adi']); ?>
                                        <?php if (!empty($y['unvan'])): ?> · <?php echo htmlspecialchars($y['unvan']); ?><?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <a href="yoneticiler-yonet.php" class="admin-panel-tumunu-gor">Tümünü Gör <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card admin-kart h-100">
            <div class="card-body">
                <div class="admin-panel-baslik">Son Aktiviteler</div>
                <?php if (count($aktiviteAkisi) === 0): ?>
                    <div class="admin-bos-aktivite">Henüz bir aktivite kaydı yok.</div>
                <?php else: ?>
                    <?php foreach ($aktiviteAkisi as $a): ?>
                        <a href="<?php echo htmlspecialchars($a['link']); ?>" class="admin-aktivite-satir admin-aktivite-satir--tiklanabilir">
                            <i class="bi bi-circle-fill admin-aktivite-nokta <?php echo $a['renk']; ?>"></i>
                            <div>
                                <span class="admin-aktivite-metin"><?php echo htmlspecialchars($a['metin']); ?></span>
                                <span class="admin-aktivite-zaman"><?php echo date('d.m.Y H:i', strtotime($a['tarih'])); ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<h6 class="fw-bold text-muted mb-3">Sistem Durumu</h6>
<div class="admin-durum-seridi mb-4">
    <div class="admin-durum-kutu admin-durum-kutu--basarili"><i class="bi bi-circle-fill"></i> Veritabanı <span class="deger">Bağlı</span></div>
    <div class="admin-durum-kutu admin-durum-kutu--basarili"><i class="bi bi-circle-fill"></i> Oturum <span class="deger">Aktif</span></div>
    <div class="admin-durum-kutu admin-durum-kutu--bilgi"><i class="bi bi-clock-fill"></i> Sunucu Saati <span class="deger"><?php echo date('d.m.Y H:i'); ?></span></div>
    <div class="admin-durum-kutu admin-durum-kutu--bilgi"><i class="bi bi-person-badge-fill"></i> Yönetici Sayısı <span class="deger"><?php echo $yoneticiSayisi; ?></span></div>
</div>

<?php include 'includes/admin-bitis.php'; ?>

