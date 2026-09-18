<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';

// Yeni kayıt için varsayılan sıra: mevcut en büyük sıradan bir fazlası
$sonraSira = (int)$pdo->query("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM tarihi_yerler")->fetch()['sonraki'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $kisaAciklama = trim($_POST['kisa_aciklama'] ?? '');
    $detay = trim($_POST['detay'] ?? '');
    $ikon = trim($_POST['ikon'] ?? '') ?: 'bi-geo-alt-fill';
    $resimUrl = trim($_POST['resim_url'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : $sonraSira;

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

    if ($resimUrl === '') {
        $resimUrl = 'https://placehold.co/500x350?text=Tarihi+Yer';
    }

    if ($baslik === '' || $detay === '') {
        $hataMesaji = 'Lütfen başlık ve detay alanlarını doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO tarihi_yerler (ikon, baslik, kisa_aciklama, resim_url, detay, sira)
             VALUES (:ikon, :baslik, :kisa, :resim_url, :detay, :sira)"
        );
        $stmt->execute([
            'ikon' => $ikon,
            'baslik' => $baslik,
            'kisa' => $kisaAciklama,
            'resim_url' => $resimUrl,
            'detay' => $detay,
            'sira' => $sira,
        ]);

        islemKaydet($pdo, 'Tarihi Yer Ekledi', $baslik);
        header('Location: tarihi-yerler-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Tarihi Yer Ekle';
$aktifMenu = 'sabit-tarihi-yerler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Tarihi Yer Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="tarihi-yerler-ekle.php" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? ''); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)($_POST['sira'] ?? $sonraSira); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">İkon</label>
                <input type="text" name="ikon" class="form-control" placeholder="bi-building"
                       value="<?php echo htmlspecialchars($_POST['ikon'] ?? 'bi-geo-alt-fill'); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Kısa Açıklama <span class="text-muted small">(kutucukta görünür)</span></label>
                <input type="text" name="kisa_aciklama" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['kisa_aciklama'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Detay (tıklanınca açılan panelde görünür) *</label>
                <textarea name="detay" rows="5" class="form-control" required><?php echo htmlspecialchars($_POST['detay'] ?? ''); ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Bilgisayardan Görsel Yükle (varsa URL'nin önüne geçer)</label>
                <input type="file" name="resim_dosya" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Görsel URL (ikisi de boşsa otomatik görsel kullanılır)</label>
                <input type="text" name="resim_url" class="form-control" placeholder="https://..."
                       value="<?php echo htmlspecialchars($_POST['resim_url'] ?? ''); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
