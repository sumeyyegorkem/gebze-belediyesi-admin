<?php
/**
 * admin/hizli-durum-degistir.php
 * -------------------------------------------------------
 * Haberler, duyurular, etkinlikler ve projeler listeleme sayfalarındaki
 * İşlem menüsünden gelen "Aktif Yap / Pasife Al" bağlantısı buraya düşer.
 * "tur" parametresi sabit bir beyaz listeden geldiği için tablo adı
 * kullanıcıdan gelen serbest metinle değil, bu listeden seçilir.
 * -------------------------------------------------------
 */
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$izinliTurler = [
    'haber'    => ['tablo' => 'haberler',    'yonet' => 'haberler-yonet.php',  'etiket' => 'Haber'],
    'duyuru'   => ['tablo' => 'duyurular',   'yonet' => 'duyurular-yonet.php', 'etiket' => 'Duyuru'],
    'etkinlik' => ['tablo' => 'etkinlikler', 'yonet' => 'etkinlik-yonet.php',  'etiket' => 'Etkinlik'],
    'proje'    => ['tablo' => 'projeler',    'yonet' => 'projeler-yonet.php',  'etiket' => 'Proje'],

    'arabuluculuk'        => ['tablo' => 'arabuluculuk_uyeleri',    'yonet' => 'arabuluculuk-uye-yonet.php',   'etiket' => 'Arabuluculuk Üyesi'],
    'baskan-yardimcisi'   => ['tablo' => 'baskan_yardimcilari',     'yonet' => 'baskan-yardimcilari-yonet.php','etiket' => 'Başkan Yardımcısı'],
    'mudurluk'            => ['tablo' => 'mudurlukler',             'yonet' => 'mudurlukler-yonet.php',        'etiket' => 'Müdürlük'],
    'eski-baskan'         => ['tablo' => 'eski_baskanlar',          'yonet' => 'eski-baskan-yonet.php',        'etiket' => 'Eski Başkan'],
    'etik-uye'            => ['tablo' => 'etik_komisyonu_uyeleri',  'yonet' => 'etik-uye-yonet.php',           'etiket' => 'Etik Komisyonu Üyesi'],
    'faaliyet-kategori'   => ['tablo' => 'faaliyet_kategorileri',   'yonet' => 'faaliyet-alanlari-yonet.php',  'etiket' => 'Faaliyet Kategorisi'],
    'faaliyet-oge'        => ['tablo' => 'faaliyet_ogeleri',        'yonet' => 'faaliyet-alanlari-yonet.php',  'etiket' => 'Faaliyet Öğesi'],
    'hizmet-karti'        => ['tablo' => 'hizmet_kartlari',         'yonet' => 'hizmet-karti-yonet.php',       'etiket' => 'Hizmet Kartı'],
    'kardes-sehir'        => ['tablo' => 'kardes_sehirler',         'yonet' => 'kardes-sehir-yonet.php',       'etiket' => 'Kardeş Şehir'],
    'kurumsal-rapor'      => ['tablo' => 'kurumsal_raporlar',       'yonet' => 'kurumsal-rapor-yonet.php',     'etiket' => 'Kurumsal Rapor'],
    'meclis-karari'       => ['tablo' => 'meclis_kararlari',        'yonet' => 'meclis-karari-yonet.php',      'etiket' => 'Meclis Kararı'],
    'meclis-uyesi'        => ['tablo' => 'meclis_uyeleri',          'yonet' => 'meclis-uyesi-yonet.php',       'etiket' => 'Meclis Üyesi'],
    'tarihi-yer'          => ['tablo' => 'tarihi_yerler',           'yonet' => 'tarihi-yerler-yonet.php',      'etiket' => 'Tarihi Yer'],
    'uye-birlik'          => ['tablo' => 'uye_birlikler',           'yonet' => 'uye-birlik-yonet.php',         'etiket' => 'Üye Birlik'],
    'video'               => ['tablo' => 'videolar',                'yonet' => 'videolar.php',                 'etiket' => 'Video'],
    'yayin'               => ['tablo' => 'yayinlar',                'yonet' => 'yayinlar-yonet.php',           'etiket' => 'Yayın'],
    'e-belediye-kategori' => ['tablo' => 'e_belediye_kategoriler',  'yonet' => 'e-belediye-yonet.php',         'etiket' => 'E-Belediye Kategorisi'],
    'e-belediye-bolum'    => ['tablo' => 'e_belediye_bolumler',     'yonet' => 'e-belediye-yonet.php',         'etiket' => 'E-Belediye Bölümü'],
    'e-belediye-hizmet'   => ['tablo' => 'e_belediye_hizmetleri',   'yonet' => 'e-belediye-yonet.php',         'etiket' => 'E-Belediye Hizmeti'],
    'hero-slayt'          => ['tablo' => 'hero_slaytlari',          'yonet' => 'hero-slayt-yonet.php',         'etiket' => 'Hero Slayt'],
    'sosyal-medya'        => ['tablo' => 'sosyal_medya',            'yonet' => 'sosyal-medya-yonet.php',       'etiket' => 'Sosyal Medya'],
    'muhtar'              => ['tablo' => 'muhtarlar',               'yonet' => 'muhtar-yonet.php',             'etiket' => 'Muhtar'],
];

$tur = $_GET['tur'] ?? '';
$id  = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!isset($izinliTurler[$tur]) || $id <= 0) {
    header('Location: panel.php');
    exit;
}

$ayar = $izinliTurler[$tur];
$tablo = $ayar['tablo'];

$stmt = $pdo->prepare("SELECT * FROM `$tablo` WHERE id = :id");
$stmt->execute(['id' => $id]);
$satir = $stmt->fetch();

if ($satir) {
    $yeniDurum = ((int)$satir['aktif'] === 1) ? 0 : 1;
    $guncelle = $pdo->prepare("UPDATE `$tablo` SET aktif = :aktif WHERE id = :id");
    $guncelle->execute(['aktif' => $yeniDurum, 'id' => $id]);

    // Kayıt için okunabilir bir isim bulmaya çalış (tablodan tabloya sütun adı değişebilir)
    $baslikAdaylari = ['baslik', 'ad_soyad', 'ad', 'isim', 'sehir_adi', 'mahalle_adi', 'platform', 'unvan'];
    $hedefAdi = '';
    foreach ($baslikAdaylari as $sutun) {
        if (!empty($satir[$sutun])) {
            $hedefAdi = $satir[$sutun];
            break;
        }
    }
    if ($hedefAdi === '') {
        $hedefAdi = '#' . $id;
    }

    $durumMetni = ($yeniDurum === 1) ? 'Aktif Yaptı' : 'Pasife Aldı';
    islemKaydet($pdo, ($ayar['etiket'] ?? ucfirst($tur)) . ' Durumunu ' . $durumMetni, $hedefAdi);
}

header('Location: ' . $ayar['yonet'] . '?basarili=1');
exit;
