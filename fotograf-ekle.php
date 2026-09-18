<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS fotograf_galerisi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(150) DEFAULT '',
    resim_url VARCHAR(255) NOT NULL,
    eklenme_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");
if ($pdo->query("SHOW COLUMNS FROM fotograf_galerisi LIKE 'sayfa'")->rowCount() === 0) {
    $pdo->exec("ALTER TABLE fotograf_galerisi ADD COLUMN sayfa VARCHAR(30) NOT NULL DEFAULT 'fotograflar' AFTER baslik");
}

// Fotoğrafın hangi sayfada/bölümde görüneceği
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

// Listeleme sayfasındaki bir bölüm sekmesinden geldiyse formda o bölüm seçili gelsin
$sayfaVarsayilan = trim($_POST['sayfa'] ?? $_GET['sayfa'] ?? 'fotograflar');
if (!array_key_exists($sayfaVarsayilan, $galeriBolumleri)) {
    $sayfaVarsayilan = 'fotograflar';
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $resimUrl = trim($_POST['resim_url'] ?? '');
    $sayfa = $sayfaVarsayilan;

    // Bilgisayardan dosya seçildiyse, URL'nin önüne geçer
    if (isset($_FILES['resim_dosya']) && $_FILES['resim_dosya']['error'] === UPLOAD_ERR_OK) {
        $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $uzanti = strtolower(pathinfo($_FILES['resim_dosya']['name'], PATHINFO_EXTENSION));
        if (in_array($uzanti, $izinliUzantilar)) {
            $yeniAd = 'galeri_' . uniqid() . '.' . $uzanti;
            if (move_uploaded_file($_FILES['resim_dosya']['tmp_name'], '../uploads/' . $yeniAd)) {
                $resimUrl = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($resimUrl === '') {
        $hataMesaji = 'Lütfen bilgisayardan bir görsel yükleyin ya da görsel URL girin.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO fotograf_galerisi (baslik, resim_url, sayfa) VALUES (:baslik, :resim_url, :sayfa)");
        $stmt->execute(['baslik' => $baslik, 'resim_url' => $resimUrl, 'sayfa' => $sayfa]);

        $hedefAdi = ($baslik !== '') ? $baslik : ($galeriBolumleri[$sayfa] ?? $sayfa);
        islemKaydet($pdo, 'Galeri Fotoğrafı Ekledi', $hedefAdi);
        header('Location: fotograf-galerisi.php?basarili=1&sayfa=' . urlencode($sayfa));
        exit;
    }
}

$sayfaBasligi = 'Yeni Fotoğraf Ekle';
$aktifMenu = 'fotograf-galerisi';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-image-fill me-2"></i>Yeni Fotoğraf Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="fotograf-ekle.php" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Bölüm *</label>
                <select name="sayfa" class="form-select" required>
                    <?php foreach ($galeriBolumleri as $bKey => $bLabel): ?>
                        <option value="<?php echo htmlspecialchars($bKey); ?>" <?php echo ($sayfaVarsayilan === $bKey) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($bLabel); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted">Fotoğraf, seçtiğiniz bölümün sayfasında "Fotoğraf Galerisi" içinde görünür.</small>
            </div>
            <div class="col-12">
                <label class="form-label">Başlık / Açıklama (opsiyonel)</label>
                <input type="text" name="baslik" class="form-control"
                       placeholder="Örn: Sultan Orhan Meydanı"
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bilgisayardan Görsel Yükle</label>
                <input type="file" name="resim_dosya" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Görsel URL</label>
                <input type="text" name="resim_url" class="form-control" placeholder="https://... ya da img/dosya.jpg"
                       value="<?php echo htmlspecialchars($_POST['resim_url'] ?? ''); ?>">
                <small class="text-muted">Dosya yüklerseniz bu alan yok sayılır.</small>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
