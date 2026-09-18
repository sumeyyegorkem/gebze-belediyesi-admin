<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM muhtarlar WHERE id = :id");
$stmt->execute(['id' => $id]);
$muhtar = $stmt->fetch();

if (!$muhtar) {
    header('Location: muhtar-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $mahalle = trim($_POST['mahalle'] ?? '');
    $tel = trim($_POST['tel'] ?? '');
    $eposta = trim($_POST['eposta'] ?? '');
    $adres = trim($_POST['adres'] ?? '');
    $harita = trim($_POST['harita'] ?? '');
    $foto = trim($_POST['foto'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : (int)$muhtar['sira'];

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
        $guncelle = $pdo->prepare(
            "UPDATE muhtarlar
             SET ad = :ad, mahalle = :mahalle, tel = :tel, eposta = :eposta, adres = :adres, harita = :harita, foto = :foto, sira = :sira
             WHERE id = :id"
        );
        $guncelle->execute([
            'ad' => $ad,
            'mahalle' => $mahalle,
            'tel' => $tel,
            'eposta' => $eposta !== '' ? $eposta : null,
            'adres' => $adres !== '' ? $adres : null,
            'harita' => $harita !== '' ? $harita : null,
            'foto' => $foto !== '' ? $foto : null,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Muhtar Düzenledi', $ad);
        header('Location: muhtar-yonet.php?basarili=1');
        exit;
    }
    $muhtar = array_merge($muhtar, $_POST);
}

$sayfaBasligi = 'Muhtar Düzenle';
$aktifMenu = 'muhtarlar';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Muhtar Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="muhtar-duzenle.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Ad Soyad *</label>
                <input type="text" name="ad" class="form-control" required
                       value="<?php echo htmlspecialchars($muhtar['ad']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Mahalle *</label>
                <input type="text" name="mahalle" class="form-control" required
                       value="<?php echo htmlspecialchars($muhtar['mahalle']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Telefon *</label>
                <input type="text" name="tel" class="form-control" required
                       value="<?php echo htmlspecialchars($muhtar['tel']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">E-posta</label>
                <input type="email" name="eposta" class="form-control"
                       value="<?php echo htmlspecialchars($muhtar['eposta'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Adres</label>
                <input type="text" name="adres" class="form-control"
                       value="<?php echo htmlspecialchars($muhtar['adres'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Harita Bağlantısı</label>
                <input type="text" name="harita" class="form-control"
                       value="<?php echo htmlspecialchars($muhtar['harita'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)$muhtar['sira']; ?>">
            </div>
            <div class="col-12">
                <?php if (!empty($muhtar['foto'])): ?>
                <img src="../<?php echo htmlspecialchars($muhtar['foto']); ?>" class="mb-2 rounded-circle" style="width:80px;height:80px;object-fit:cover;"
                     onerror="this.style.display='none';">
                <?php endif; ?>
                <label class="form-label d-block">Bilgisayardan Yeni Fotoğraf Yükle</label>
                <input type="file" name="foto_dosya" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Fotoğraf URL</label>
                <input type="text" name="foto" class="form-control"
                       value="<?php echo htmlspecialchars($muhtar['foto'] ?? ''); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
            <a href="muhtar-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
