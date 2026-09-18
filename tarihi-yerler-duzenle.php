<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM tarihi_yerler WHERE id = :id");
$stmt->execute(['id' => $id]);
$yer = $stmt->fetch();

if (!$yer) {
    header('Location: tarihi-yerler-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $kisaAciklama = trim($_POST['kisa_aciklama'] ?? '');
    $detay = trim($_POST['detay'] ?? '');
    $ikon = trim($_POST['ikon'] ?? '') ?: 'bi-geo-alt-fill';
    $resimUrl = trim($_POST['resim_url'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : (int)$yer['sira'];

    if (isset($_FILES['resim_dosya']) && $_FILES['resim_dosya']['error'] === UPLOAD_ERR_OK) {
        $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $uzanti = strtolower(pathinfo($_FILES['resim_dosya']['name'], PATHINFO_EXTENSION));
        if (in_array($uzanti, $izinliUzantilar)) {
            $yeniAd = 'tarihiyer_' . uniqid() . '.' . $uzanti;
            if (move_uploaded_file($_FILES['resim_dosya']['tmp_name'], '../uploads/' . $yeniAd)) {
                $resimUrl = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($baslik === '' || $detay === '') {
        $hataMesaji = 'Lütfen başlık ve detay alanlarını doldurun.';
    } else {
        $guncelle = $pdo->prepare(
            "UPDATE tarihi_yerler
             SET ikon = :ikon, baslik = :baslik, kisa_aciklama = :kisa, resim_url = :resim_url, detay = :detay, sira = :sira
             WHERE id = :id"
        );
        $guncelle->execute([
            'ikon' => $ikon,
            'baslik' => $baslik,
            'kisa' => $kisaAciklama,
            'resim_url' => $resimUrl,
            'detay' => $detay,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Tarihi Yer Düzenledi', $baslik);
        header('Location: tarihi-yerler-yonet.php?basarili=1');
        exit;
    }
    $yer = array_merge($yer, $_POST);
}

$sayfaBasligi = 'Tarihi Yer Düzenle';
$aktifMenu = 'sabit-tarihi-yerler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Tarihi Yer Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="tarihi-yerler-duzenle.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($yer['baslik']); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)$yer['sira']; ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">İkon</label>
                <input type="text" name="ikon" class="form-control" placeholder="bi-building"
                       value="<?php echo htmlspecialchars($yer['ikon']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Kısa Açıklama</label>
                <input type="text" name="kisa_aciklama" class="form-control"
                       value="<?php echo htmlspecialchars($yer['kisa_aciklama']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Detay *</label>
                <textarea name="detay" rows="5" class="form-control" required><?php echo htmlspecialchars($yer['detay']); ?></textarea>
            </div>
            <div class="col-12">
                <img src="../<?php echo htmlspecialchars($yer['resim_url']); ?>" class="mb-2 rounded-3" style="max-height:120px;"
                     onerror="this.style.display='none';">
                <label class="form-label d-block">Bilgisayardan Yeni Görsel Yükle (seçilirse mevcut görselin yerine geçer)</label>
                <input type="file" name="resim_dosya" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Görsel URL</label>
                <input type="text" name="resim_url" class="form-control"
                       value="<?php echo htmlspecialchars($yer['resim_url']); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
