<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';
require_once 'includes/sayfalama-yardimci.php';

$mevcutSayfa = adminMevcutSayfa();
$sayfaBoyutu = ADMIN_SAYFA_BOYUTU;
$offset = ($mevcutSayfa - 1) * $sayfaBoyutu;

// Tablo daha önce oluşturulmadıysa burada oluşturulur (genel sitedeki fotograflarla-gebze.php ile aynı)
$pdo->exec("CREATE TABLE IF NOT EXISTS fotograf_galerisi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(150) DEFAULT '',
    resim_url VARCHAR(255) NOT NULL,
    eklenme_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");
if ($pdo->query("SHOW COLUMNS FROM fotograf_galerisi LIKE 'sayfa'")->rowCount() === 0) {
    $pdo->exec("ALTER TABLE fotograf_galerisi ADD COLUMN sayfa VARCHAR(30) NOT NULL DEFAULT 'fotograflar' AFTER baslik");
}

$fotoSayisi = $pdo->query("SELECT COUNT(*) AS toplam FROM fotograf_galerisi")->fetch()['toplam'];
if ($fotoSayisi == 0) {
    $pdo->exec("INSERT INTO fotograf_galerisi (baslik, resim_url, sayfa) VALUES
        ('Gebze', 'img/galeri-1.jpg', 'fotograflar'),
        ('Gebze', 'img/galeri-2.jpg', 'fotograflar'),
        ('Gebze', 'img/galeri-3.jpg', 'fotograflar'),
        ('Gebze', 'img/galeri-4.webp', 'fotograflar')");
}

// Fotoğrafın hangi sayfada/bölümde göründüğü (admin/fotograf-ekle.php ile aynı liste)
$galeriBolumleri = [
    'fotograflar'    => 'Fotoğraflarla Gebze',
    'tarihce'        => 'Tarihçe',
    'bugunku-gebze'  => 'Bugünkü Gebze',
    'projeler'       => 'Projeler',
    // Hizmetler artık tek ortak galeri değil, her hizmet alt sayfasının kendi galerisi var:
    'nikah-islemleri'      => 'Hizmetler ➜ Nikah İşlemleri',
    'fen-isleri'           => 'Hizmetler ➜ Fen İşleri',
    'emlak-istimlak'       => 'Hizmetler ➜ Emlak ve İstimlak',
    'kultur-sosyal-isler'  => 'Hizmetler ➜ Kültür ve Sosyal İşler',
    'temizlik-isleri'      => 'Hizmetler ➜ Temizlik İşleri',
    'veteriner-hizmetleri' => 'Hizmetler ➜ Veteriner Hizmetleri',
    'zabita'               => 'Hizmetler ➜ Zabıta',
];

// Tarihi Yerler, Haberler, Etkinlikler ve Duyurular artık tek ortak galeri değil;
// her mekanın/haberin/etkinliğin/duyurunun kendi galerisi var. Liste, ilgili
// tablolardaki mevcut kayıtlardan anlık olarak oluşturulur.
$galeriTarihiYerler = $pdo->query("SELECT id, baslik FROM tarihi_yerler ORDER BY sira ASC, id ASC")->fetchAll();
foreach ($galeriTarihiYerler as $gty) {
    $galeriBolumleri['tarihi-yer-' . $gty['id']] = 'Tarihi Yerler ➜ ' . $gty['baslik'];
}
$galeriHaberler = $pdo->query("SELECT id, baslik FROM haberler ORDER BY yayin_tarihi DESC")->fetchAll();
foreach ($galeriHaberler as $gh) {
    $galeriBolumleri['haber-' . $gh['id']] = 'Haberler ➜ ' . $gh['baslik'];
}
$galeriEtkinlikler = $pdo->query("SELECT id, baslik FROM etkinlikler ORDER BY etkinlik_tarihi DESC")->fetchAll();
foreach ($galeriEtkinlikler as $ge) {
    $galeriBolumleri['etkinlik-' . $ge['id']] = 'Etkinlikler ➜ ' . $ge['baslik'];
}
$galeriDuyurular = $pdo->query("SELECT id, baslik FROM duyurular ORDER BY yayin_tarihi DESC")->fetchAll();
foreach ($galeriDuyurular as $gd) {
    $galeriBolumleri['duyuru-' . $gd['id']] = 'Duyurular ➜ ' . $gd['baslik'];
}

// Soldaki menüde "... Galerisi" olarak görünen genel/toplu sekmeler: tek bir sabit
// "sayfa" değeri yerine, altındaki tüm alt öğelerin (mekan/haber/etkinlik/duyuru/hizmet)
// fotoğraflarını bir arada gösterir. Böylece her sekmeye tıklandığında sadece o
// bölüme ait fotoğraflar görünür, havuzdaki diğer her şey değil.
$galeriGruplari = [
    'hizmetler'     => ['nikah-islemleri', 'fen-isleri', 'emlak-istimlak', 'kultur-sosyal-isler', 'temizlik-isleri', 'veteriner-hizmetleri', 'zabita'],
    'tarihi-yerler' => array_map(function ($x) { return 'tarihi-yer-' . $x['id']; }, $galeriTarihiYerler),
    'haberler'      => array_map(function ($x) { return 'haber-' . $x['id']; }, $galeriHaberler),
    'etkinlikler'   => array_map(function ($x) { return 'etkinlik-' . $x['id']; }, $galeriEtkinlikler),
    'duyurular'     => array_map(function ($x) { return 'duyuru-' . $x['id']; }, $galeriDuyurular),
];
$galeriGrupBasliklari = [
    'hizmetler'     => 'Hizmetler Galerisi',
    'tarihi-yerler' => 'Tarihi Yerler Galerisi',
    'haberler'      => 'Haberler Galerisi',
    'etkinlikler'   => 'Etkinlikler Galerisi',
    'duyurular'     => 'Duyurular Galerisi',
];
foreach ($galeriGrupBasliklari as $gKey => $gLabel) {
    $galeriBolumleri[$gKey] = $gLabel;
}

$sayfaFiltre = trim($_GET['sayfa'] ?? '');
if ($sayfaFiltre !== '' && !array_key_exists($sayfaFiltre, $galeriBolumleri)) {
    $sayfaFiltre = '';
}
$grupFiltresiMi = array_key_exists($sayfaFiltre, $galeriGruplari);

$arama = trim($_GET['q'] ?? '');

$kosullar = [];
$parametreler = [];
if ($arama !== '') {
    // Fotoğrafın başlığı dışında, ait olduğu "Bölüm" adıyla da (ör. "Projeler",
    // "Tarihçe", "Haberler ➜ ..." gibi) aranabilsin; bölüm adı eşleşirse o
    // bölümdeki tüm fotoğraflar da sonuçlara dahil edilir.
    $esleşenBolumKodlari = [];
    foreach ($galeriBolumleri as $bKey => $bLabel) {
        if (mb_stripos($bLabel, $arama, 0, 'UTF-8') !== false) {
            $esleşenBolumKodlari[] = $bKey;
        }
    }

    $aramaKosullari = ['baslik LIKE :q'];
    $parametreler['q'] = '%' . $arama . '%';

    if (count($esleşenBolumKodlari) > 0) {
        $bolumYerTutucular = [];
        foreach ($esleşenBolumKodlari as $i => $bKod) {
            $bolumYerTutucular[] = ':barama' . $i;
            $parametreler['barama' . $i] = $bKod;
        }
        $aramaKosullari[] = 'sayfa IN (' . implode(', ', $bolumYerTutucular) . ')';
    }

    $kosullar[] = '(' . implode(' OR ', $aramaKosullari) . ')';
}
if ($sayfaFiltre !== '') {
    if ($grupFiltresiMi) {
        $altSayfalar = $galeriGruplari[$sayfaFiltre];
        if (count($altSayfalar) > 0) {
            $yerTutucular = [];
            foreach ($altSayfalar as $i => $altSayfa) {
                $yerTutucular[] = ':grp' . $i;
                $parametreler['grp' . $i] = $altSayfa;
            }
            $kosullar[] = 'sayfa IN (' . implode(', ', $yerTutucular) . ')';
        } else {
            // Bu grupta henüz hiç alt öğe (haber/etkinlik/duyuru/mekan) yoksa sonuç boş kalsın
            $kosullar[] = '1 = 0';
        }
    } else {
        $kosullar[] = 'sayfa = :sayfa';
        $parametreler['sayfa'] = $sayfaFiltre;
    }
}
$whereIfadesi = count($kosullar) > 0 ? ('WHERE ' . implode(' AND ', $kosullar)) : '';

$sayimStmt = $pdo->prepare("SELECT COUNT(*) AS toplam FROM fotograf_galerisi $whereIfadesi");
$sayimStmt->execute($parametreler);
$toplamKayit = (int)$sayimStmt->fetch()['toplam'];
$toplamSayfa = adminToplamSayfa($toplamKayit, $sayfaBoyutu);

$stmt = $pdo->prepare("SELECT * FROM fotograf_galerisi $whereIfadesi ORDER BY id ASC LIMIT :limit OFFSET :offset");
foreach ($parametreler as $pKey => $pVal) {
    $stmt->bindValue(':' . $pKey, $pVal);
}
$stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$fotograflar = $stmt->fetchAll();

// Sayfa başlığı, hangi sekimde/bölümde olunduğunu göstersin (soldaki menüdeki
// isimlerle aynı) — böylece her sekmeye tıklandığında sadece genel "Fotoğraf
// Galerisi Yönetimi" değil, o sekmenin kendi adı görünür.
$galeriSekmeBasliklari = [
    'fotograflar'    => 'Fotoğraflarla Gebze',
    'tarihce'        => 'Tarihçe Galerisi',
    'bugunku-gebze'  => 'Bugünkü Gebze Galerisi',
    'tarihi-yerler'  => 'Tarihi Yerler Galerisi',
    'hizmetler'      => 'Hizmetler Galerisi',
    'etkinlikler'    => 'Etkinlikler Galerisi',
    'haberler'       => 'Haberler Galerisi',
    'duyurular'      => 'Duyurular Galerisi',
];
$galeriSayfaBasligi = 'Fotoğraf Galerisi Yönetimi';
if ($sayfaFiltre !== '') {
    if (isset($galeriSekmeBasliklari[$sayfaFiltre])) {
        $galeriSayfaBasligi = $galeriSekmeBasliklari[$sayfaFiltre];
    } elseif (isset($galeriBolumleri[$sayfaFiltre])) {
        $galeriSayfaBasligi = $galeriBolumleri[$sayfaFiltre];
    }
}

$sayfaBasligi = $galeriSayfaBasligi;
$aktifMenu = $sayfaFiltre !== '' ? ('galeri-' . $sayfaFiltre) : '';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($galeriSayfaBasligi); ?></h4>
    <?php if (!$grupFiltresiMi): ?>
        <a href="fotograf-ekle.php?sayfa=<?php echo urlencode($sayfaFiltre ?: 'fotograflar'); ?>" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Fotoğraf Ekle</a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['basarili'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>İşlem başarıyla tamamlandı.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="GET" class="mb-3">
    <?php if ($sayfaFiltre !== ''): ?><input type="hidden" name="sayfa" value="<?php echo htmlspecialchars($sayfaFiltre); ?>"><?php endif; ?>
    <div class="input-group admin-arama-kutusu">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" name="q" class="form-control" placeholder="Arama yapınız..." value="<?php echo htmlspecialchars($arama); ?>">
    </div>
</form>

<div class="card admin-kart">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Önizleme</th>
                    <th>Başlık</th>
                    <th>Bölüm</th>
                    <th>Eklenme Tarihi</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($fotograflar) === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">
                        <?php echo ($arama !== '' || $sayfaFiltre !== '') ? 'Aramanızla eşleşen fotoğraf bulunamadı.' : 'Henüz fotoğraf eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($fotograflar as $fSira => $f): ?>
                    <tr>
                        <td><?php echo $offset + $fSira + 1; ?></td>
                        <td>
                            <img src="../<?php echo htmlspecialchars($f['resim_url']); ?>"
                                 style="width:90px;height:60px;object-fit:cover;border-radius:6px;"
                                 onerror="this.src='https://placehold.co/90x60?text=Yok';">
                        </td>
                        <td><?php echo htmlspecialchars($f['baslik'] ?: '—'); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($galeriBolumleri[$f['sayfa']] ?? $f['sayfa']); ?></span></td>
                        <td class="small text-muted"><?php echo date('d.m.Y H:i', strtotime($f['eklenme_tarihi'])); ?></td>
                        <td class="text-end">
                            <a href="fotograf-sil.php?id=<?php echo $f['id']; ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Bu fotoğrafı silmek istediğinize emin misiniz?');">
                                <i class="bi bi-trash-fill"></i> Sil
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($toplamSayfa > 1): ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-top">
        <span class="admin-sayfalama-bilgi">
            Toplam <?php echo $toplamKayit; ?> kayıttan <?php echo $offset + 1; ?>-<?php echo min($offset + $sayfaBoyutu, $toplamKayit); ?> arası gösteriliyor
        </span>
        <?php
            $ekParametreler = [];
            if ($arama !== '') { $ekParametreler['q'] = $arama; }
            if ($sayfaFiltre !== '') { $ekParametreler['sayfa'] = $sayfaFiltre; }
            adminSayfalamaCiz($mevcutSayfa, $toplamSayfa, $ekParametreler);
        ?>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/admin-bitis.php'; ?>

