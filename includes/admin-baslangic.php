<?php
/**
 * admin/includes/admin-baslangic.php
 * -------------------------------------------------------
 * Yönetim panelinin ortak üst kısmı: <head>, sol menü (sidebar)
 * ve üst bar. Her admin sayfası bu dosyayı include etmeden önce
 * isteğe bağlı olarak şu değişkenleri tanımlayabilir:
 *   $sayfaBasligi -> sayfa başlığı ve üst bardaki metin (varsayılan: 'Yönetim Paneli')
 *   $aktifMenu    -> sol menüde hangi bağlantının aktif görüneceği (anahtar aşağıda)
 * -------------------------------------------------------
 */
if (!isset($sayfaBasligi)) { $sayfaBasligi = 'Yönetim Paneli'; }
if (!isset($aktifMenu)) { $aktifMenu = ''; }

require_once __DIR__ . '/yonetici-yardimci.php';

function adminMenuAktifMi($anahtar, $aktifMenu) {
    return $anahtar === $aktifMenu ? ' active' : '';
}

// Üst bardaki bildirim rozeti için okunmamış mesaj sayısı (varsa $pdo çağıran sayfadan gelir)
$adminBildirimSayisi = 0;
if (isset($pdo)) {
    try {
        $adminBildirimSayisi = (int)($pdo->query("SELECT COUNT(*) AS toplam FROM iletisim_mesajlari WHERE okundu = 0")->fetch()['toplam'] ?? 0);
    } catch (Exception $e) {
        $adminBildirimSayisi = 0;
    }
}
$adminAd = $_SESSION['admin_ad'] ?? 'Yönetici';
$adminFoto = null;
$adminRenk = 'mavi';
$adminUnvan = '';
if (isset($pdo) && isset($_SESSION['admin_id'])) {
    try {
        $adminBilgiStmt = $pdo->prepare("SELECT ad_soyad, fotograf, avatar_rengi, unvan FROM yoneticiler WHERE id = :id");
        $adminBilgiStmt->execute(['id' => $_SESSION['admin_id']]);
        $adminBilgi = $adminBilgiStmt->fetch();
        if ($adminBilgi) {
            $adminAd = $adminBilgi['ad_soyad'];
            $adminFoto = $adminBilgi['fotograf'] ?: null;
            $adminRenk = $adminBilgi['avatar_rengi'] ?: 'mavi';
            $adminUnvan = $adminBilgi['unvan'] ?: '';
        }
    } catch (PDOException $adminBilgiHata) {
        // sütunlar henüz eklenmediyse (migration çalışmadıysa) sessizce geç
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($sayfaBasligi); ?> | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=63" rel="stylesheet">
    <link href="../css/admin-tema.css?v=4" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body class="admin-govde">

<button type="button" class="admin-menu-tetik" onclick="document.querySelector('.admin-sidebar').classList.toggle('admin-sidebar-acik')">
    <i class="bi bi-list"></i>
</button>

<aside class="admin-sidebar">
    <a href="panel.php" class="admin-sidebar-marka">
        <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg" alt="Gebze Belediyesi">
        <span>Yönetim Paneli</span>
    </a>

    <a href="profilim.php" class="admin-profil">
        <?php echo adminAvatarHtml($adminAd, $adminFoto, $adminRenk, 'admin-profil-avatar'); ?>
        <div class="admin-profil-ad"><?php echo htmlspecialchars($adminAd); ?></div>
        <?php if ($adminUnvan !== ''): ?>
            <div class="admin-profil-unvan"><?php echo htmlspecialchars($adminUnvan); ?></div>
        <?php endif; ?>
        <span class="admin-profil-durum"><i class="bi bi-circle-fill"></i> Aktif</span>
    </a>

    <nav class="nav flex-column">
        <a href="panel.php" class="nav-link<?php echo adminMenuAktifMi('anasayfa', $aktifMenu); ?>">
            <i class="bi bi-speedometer2"></i> Anasayfa
        </a>

        <div class="nav-grup-baslik">Ana Sayfa</div>
        <a href="hero-slayt-yonet.php" class="nav-link<?php echo adminMenuAktifMi('hero-slaytlari', $aktifMenu); ?>">
            <i class="bi bi-images"></i> Hero Slaytları
        </a>

        <div class="nav-grup-baslik">Başkan</div>
        <a href="icerik-sayfasi-duzenle.php?sayfa=hakkimizda" class="nav-link<?php echo adminMenuAktifMi('sabit-hakkimizda', $aktifMenu); ?>">
            <i class="bi bi-person-badge-fill"></i> Özgeçmiş
        </a>
        <a href="projeler-yonet.php" class="nav-link<?php echo adminMenuAktifMi('projeler', $aktifMenu); ?>">
            <i class="bi bi-kanban-fill"></i> Projeler
        </a>

        <div class="nav-grup-baslik">Kurumsal</div>
        <a href="meclis-uyesi-yonet.php" class="nav-link<?php echo adminMenuAktifMi('sabit-meclis', $aktifMenu); ?>">
            <i class="bi bi-bank2"></i> Belediye Meclisi (Üyeler)
        </a>
        <a href="yonetim-semasi-yonet.php" class="nav-link<?php echo adminMenuAktifMi('yonetim-semasi', $aktifMenu); ?>">
            <i class="bi bi-diagram-3-fill"></i> Yönetim Şeması
        </a>
        <a href="baskan-yardimcilari-yonet.php" class="nav-link<?php echo adminMenuAktifMi('baskan-yardimcilari', $aktifMenu); ?>">
            <i class="bi bi-person-check-fill"></i> Başkan Yardımcıları
        </a>
        <a href="mudurlukler-yonet.php" class="nav-link<?php echo adminMenuAktifMi('mudurlukler', $aktifMenu); ?>">
            <i class="bi bi-buildings"></i> Müdürlükler
        </a>
        <a href="eski-baskan-yonet.php" class="nav-link<?php echo adminMenuAktifMi('kurumsal-eski-baskanlar', $aktifMenu); ?>">
            <i class="bi bi-clock-history"></i> Eski Başkanlar
        </a>
        <a href="arabuluculuk-uye-yonet.php" class="nav-link<?php echo adminMenuAktifMi('kurumsal-arabuluculuk', $aktifMenu); ?>">
            <i class="bi bi-people-fill"></i> Arabuluculuk Komisyonu
        </a>
        <a href="etik-uye-yonet.php" class="nav-link<?php echo adminMenuAktifMi('kurumsal-etik', $aktifMenu); ?>">
            <i class="bi bi-shield-fill-check"></i> Etik Komisyonu
        </a>
        <a href="meclis-karari-yonet.php" class="nav-link<?php echo adminMenuAktifMi('kurumsal-meclis-kararlari', $aktifMenu); ?>">
            <i class="bi bi-file-earmark-text-fill"></i> Meclis Kararları
        </a>
        <a href="kurumsal-rapor-yonet.php" class="nav-link<?php echo adminMenuAktifMi('kurumsal-raporlar', $aktifMenu); ?>">
            <i class="bi bi-graph-up"></i> Kurumsal Raporlar
        </a>
        <a href="yayinlar-yonet.php" class="nav-link<?php echo adminMenuAktifMi('yayinlar', $aktifMenu); ?>">
            <i class="bi bi-journal-richtext"></i> Yayınlar
        </a>

        <div class="nav-grup-baslik">Gebze</div>
        <a href="icerik-sayfasi-duzenle.php?sayfa=tarihce" class="nav-link<?php echo adminMenuAktifMi('sabit-tarihce', $aktifMenu); ?>">
            <i class="bi bi-clock-history"></i> Tarihçe
        </a>
        <a href="fotograf-galerisi.php?sayfa=tarihce" class="nav-link nav-link-galeri<?php echo adminMenuAktifMi('galeri-tarihce', $aktifMenu); ?>">
            <i class="bi bi-images"></i> Tarihçe Galerisi
        </a>
        <a href="icerik-sayfasi-duzenle.php?sayfa=bugunku-gebze" class="nav-link<?php echo adminMenuAktifMi('sabit-bugunku-gebze', $aktifMenu); ?>">
            <i class="bi bi-graph-up-arrow"></i> Bugünkü Gebze
        </a>
        <a href="fotograf-galerisi.php?sayfa=bugunku-gebze" class="nav-link nav-link-galeri<?php echo adminMenuAktifMi('galeri-bugunku-gebze', $aktifMenu); ?>">
            <i class="bi bi-images"></i> Bugünkü Gebze Galerisi
        </a>
        <a href="muhtar-yonet.php" class="nav-link<?php echo adminMenuAktifMi('muhtarlar', $aktifMenu); ?>">
            <i class="bi bi-signpost-split-fill"></i> Mahalle Muhtarları
        </a>
        <a href="tarihi-yerler-yonet.php" class="nav-link<?php echo adminMenuAktifMi('sabit-tarihi-yerler', $aktifMenu); ?>">
            <i class="bi bi-compass-fill"></i> Tarihi Yerler
        </a>
        <a href="fotograf-galerisi.php?sayfa=tarihi-yerler" class="nav-link nav-link-galeri<?php echo adminMenuAktifMi('galeri-tarihi-yerler', $aktifMenu); ?>">
            <i class="bi bi-images"></i> Tarihi Yerler Galerisi
        </a>
        <a href="fotograf-galerisi.php?sayfa=fotograflar" class="nav-link<?php echo adminMenuAktifMi('galeri-fotograflar', $aktifMenu); ?>">
            <i class="bi bi-images"></i> Fotoğraflarla Gebze
        </a>
        <a href="kardes-sehir-yonet.php" class="nav-link<?php echo adminMenuAktifMi('kardes-sehirler', $aktifMenu); ?>">
            <i class="bi bi-geo-alt-fill"></i> Kardeş Şehirler
        </a>
        <a href="uye-birlik-yonet.php" class="nav-link<?php echo adminMenuAktifMi('uye-birlikler', $aktifMenu); ?>">
            <i class="bi bi-diagram-3-fill"></i> Üye Olduğumuz Birlikler
        </a>

        <div class="nav-grup-baslik">Hizmetler</div>
        <a href="hizmet-karti-yonet.php" class="nav-link<?php echo adminMenuAktifMi('sabit-hizmetler', $aktifMenu); ?>">
            <i class="bi bi-grid-fill"></i> Hizmetler
        </a>
        <a href="fotograf-galerisi.php?sayfa=hizmetler" class="nav-link nav-link-galeri<?php echo adminMenuAktifMi('galeri-hizmetler', $aktifMenu); ?>">
            <i class="bi bi-images"></i> Hizmetler Galerisi
        </a>
        <a href="nikah-talepler-yonet.php" class="nav-link<?php echo adminMenuAktifMi('nikah-talepler', $aktifMenu); ?>">
            <i class="bi bi-heart-fill"></i> Nikah Talepleri
        </a>
        <a href="fen-talepler-yonet.php" class="nav-link<?php echo adminMenuAktifMi('fen-talepler', $aktifMenu); ?>">
            <i class="bi bi-cone-striped"></i> Fen İşleri Talepleri
        </a>
        <a href="zabita-ihbarlar-yonet.php" class="nav-link<?php echo adminMenuAktifMi('zabita-ihbarlar', $aktifMenu); ?>">
            <i class="bi bi-shield-check"></i> Zabıta İhbarları
        </a>
        <a href="emlak-talepler-yonet.php" class="nav-link<?php echo adminMenuAktifMi('emlak-talepler', $aktifMenu); ?>">
            <i class="bi bi-house-door-fill"></i> İmar Talepleri
        </a>
        <a href="temizlik-talepler-yonet.php" class="nav-link<?php echo adminMenuAktifMi('temizlik-talepler', $aktifMenu); ?>">
            <i class="bi bi-trash-fill"></i> Temizlik Talepleri
        </a>
        <a href="kultur-talepler-yonet.php" class="nav-link<?php echo adminMenuAktifMi('kultur-talepler', $aktifMenu); ?>">
            <i class="bi bi-palette-fill"></i> Kültür Talepleri
        </a>
        <a href="veteriner-ihbarlar-yonet.php" class="nav-link<?php echo adminMenuAktifMi('veteriner-ihbarlar', $aktifMenu); ?>">
            <i class="bi bi-heart-pulse-fill"></i> Veteriner İhbarları
        </a>

        <div class="nav-grup-baslik">Faaliyetler</div>
        <a href="faaliyet-alanlari-yonet.php" class="nav-link<?php echo adminMenuAktifMi('faaliyet-alanlari', $aktifMenu); ?>">
            <i class="bi bi-grid-3x3-gap-fill"></i> Faaliyet Alanları
        </a>

        <div class="nav-grup-baslik">E-Belediye</div>
        <a href="e-belediye-yonet.php" class="nav-link<?php echo adminMenuAktifMi('e-belediye', $aktifMenu); ?>">
            <i class="bi bi-laptop"></i> E-Belediye Hizmetleri
        </a>

        <div class="nav-grup-baslik">Etkinlikler</div>
        <a href="etkinlik-yonet.php" class="nav-link<?php echo adminMenuAktifMi('etkinlikler', $aktifMenu); ?>">
            <i class="bi bi-calendar-event-fill"></i> Etkinlikler
        </a>
        <a href="fotograf-galerisi.php?sayfa=etkinlikler" class="nav-link nav-link-galeri<?php echo adminMenuAktifMi('galeri-etkinlikler', $aktifMenu); ?>">
            <i class="bi bi-images"></i> Etkinlikler Galerisi
        </a>

        <div class="nav-grup-baslik">Haberler</div>
        <a href="haberler-yonet.php" class="nav-link<?php echo adminMenuAktifMi('haberler', $aktifMenu); ?>">
            <i class="bi bi-newspaper"></i> Haberler
        </a>
        <a href="fotograf-galerisi.php?sayfa=haberler" class="nav-link nav-link-galeri<?php echo adminMenuAktifMi('galeri-haberler', $aktifMenu); ?>">
            <i class="bi bi-images"></i> Haberler Galerisi
        </a>
        <a href="duyurular-yonet.php" class="nav-link<?php echo adminMenuAktifMi('duyurular', $aktifMenu); ?>">
            <i class="bi bi-megaphone-fill"></i> Duyurular
        </a>
        <a href="fotograf-galerisi.php?sayfa=duyurular" class="nav-link nav-link-galeri<?php echo adminMenuAktifMi('galeri-duyurular', $aktifMenu); ?>">
            <i class="bi bi-images"></i> Duyurular Galerisi
        </a>
        <a href="videolar.php" class="nav-link<?php echo adminMenuAktifMi('videolar', $aktifMenu); ?>">
            <i class="bi bi-play-circle-fill"></i> Videolar
        </a>

        <div class="nav-grup-baslik">İletişim</div>
        <a href="mesajlar-yonet.php" class="nav-link<?php echo adminMenuAktifMi('mesajlar', $aktifMenu); ?>">
            <i class="bi bi-envelope-fill"></i> Mesajlar
        </a>
        <a href="site-ayarlari.php" class="nav-link<?php echo adminMenuAktifMi('site-ayarlari', $aktifMenu); ?>">
            <i class="bi bi-gear-fill"></i> Site Bilgileri
        </a>
        <a href="sosyal-medya-yonet.php" class="nav-link<?php echo adminMenuAktifMi('sosyal-medya', $aktifMenu); ?>">
            <i class="bi bi-share-fill"></i> Sosyal Medya Hesapları
        </a>

        <div class="nav-grup-baslik">Sistem</div>
        <a href="bakim-modu.php" class="nav-link<?php echo adminMenuAktifMi('bakim-modu', $aktifMenu); ?>">
            <i class="bi bi-cone-striped"></i> Bakım Modu
        </a>
        <a href="hata-sayfalari-yonet.php" class="nav-link<?php echo adminMenuAktifMi('hata-sayfalari', $aktifMenu); ?>">
            <i class="bi bi-exclamation-octagon-fill"></i> Hata Sayfaları
        </a>
        <a href="yoneticiler-yonet.php" class="nav-link<?php echo adminMenuAktifMi('yoneticiler', $aktifMenu); ?>">
            <i class="bi bi-person-badge-fill"></i> Yöneticiler
        </a>
        <a href="islem-gecmisi.php" class="nav-link<?php echo adminMenuAktifMi('islem-gecmisi', $aktifMenu); ?>">
            <i class="bi bi-clock-history"></i> İşlem Geçmişi
        </a>
        <a href="logout.php" class="nav-link">
            <i class="bi bi-box-arrow-right"></i> Çıkış Yap
        </a>
    </nav>
</aside>

<div class="admin-icerik">
    <div class="admin-ust-bar">
        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($sayfaBasligi); ?></h5>
        <div class="admin-ust-bar-sag">
            <form class="admin-arama-genel" method="GET" action="arama.php">
                <i class="bi bi-search"></i>
                <input type="text" name="q" placeholder="Ara yap..." autocomplete="off" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            </form>
            <a href="mesajlar-yonet.php" class="admin-bildirim-btn" title="Mesajlar">
                <i class="bi bi-bell-fill"></i>
                <?php if ($adminBildirimSayisi > 0): ?>
                    <span class="admin-bildirim-rozet"><?php echo $adminBildirimSayisi > 9 ? '9+' : $adminBildirimSayisi; ?></span>
                <?php endif; ?>
            </a>
            <a href="logout.php" class="admin-cikis-btn" title="Çıkış Yap" onclick="return confirm('Çıkış yapmak istediğinize emin misiniz?');">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="container-fluid py-4 px-4">
