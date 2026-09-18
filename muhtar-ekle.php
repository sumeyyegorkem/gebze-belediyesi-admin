<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';

$sonraSira = (int)$pdo->query("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM muhtarlar")->fetch()['sonraki'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $mahalle = trim($_POST['mahalle'] ?? '');
    $tel = trim($_POST['tel'] ?? '');
    $eposta = trim($_POST['eposta'] ?? '');
    $adres = trim($_POST['adres'] ?? '');
    $harita = trim($_POST['harita'] ?? '');
    $foto = trim($_POST['foto'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : $sonraSira;

    if (isset($_FILES['foto_dosya']) && $_FILES['foto_dosya']['error'] === UPLOAD_ERR_OK) {
        $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $uzanti = strtolower(pathinfo($_FILES['foto_dosya']['name'], PATHINFO_EXTENSION));
        if (in_array($uzanti, $izinliUzantilar)) {
            $yeniAd = 'muhtar_' . uniqid() . '.' . $uzanti;
            if (move_uploaded_file($_FILES['foto_dosya']['tmp_name'], '../uploads/' . $yeniAd)) {
                $foto = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($ad === '' || $mahalle === '' || $tel === '') {
        $hataMesaji = 'Lütfen ad soyad, mahalle ve telefon alanlarının tamamını doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO muhtarlar (ad, mahalle, tel, eposta, adres, harita, foto, sira)
             VALUES (:ad, :mahalle, :tel, :eposta, :adres, :harita, :foto, :sira)"
        );
        $stmt->execute([
            'ad' => $ad,
            'mahalle' => $mahalle,
            'tel' => $tel,
            'eposta' => $eposta !== '' ? $eposta : null,
            'adres' => $adres !== '' ? $adres : null,
            'harita' => $harita !== '' ? $harita : null,
            'foto' => $foto !== '' ? $foto : null,
            'sira' => $sira,
        ]);

        islemKaydet($pdo, 'Muhtar Ekledi', $ad);
        header('Location: muhtar-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Muhtar Ekle';
$aktifMenu = 'muhtarlar';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Muhtar Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="muhtar-ekle.php" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Ad Soyad *</label>
                <input type="text" name="ad" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ad'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Mahalle *</label>
                <input type="text" name="mahalle" class="form-control" placeholder="... Mahallesi" required
                       value="<?php echo htmlspecialchars($_POST['mahalle'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Telefon *</label>
                <input type="text" name="tel" class="form-control" placeholder="0532 000 00 00" required
                       value="<?php echo htmlspecialchars($_POST['tel'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">E-posta</label>
                <input type="email" name="eposta" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['eposta'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Adres</label>
                <input type="text" name="adres" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['adres'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Harita Bağlantısı</label>
                <input type="text" name="harita" class="form-control" placeholder="https://goo.gl/maps/..."
                       value="<?php echo htmlspecialchars($_POST['harita'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)($_POST['sira'] ?? $sonraSira); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Fotoğraf</label>
                <input type="file" name="foto_dosya" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Fotoğraf URL</label>
                <input type="text" name="foto" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['foto'] ?? ''); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
            <a href="muhtar-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
