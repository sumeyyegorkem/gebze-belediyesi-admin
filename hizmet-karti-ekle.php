<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';

$sonraSira = (int)$pdo->query("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM hizmet_kartlari")->fetch()['sonraki'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $ozet = trim($_POST['ozet'] ?? '');
    $detay = trim($_POST['detay'] ?? '');
    $ikon = trim($_POST['ikon'] ?? '') ?: 'bi-grid-fill';
    $link = trim($_POST['link'] ?? '');
    $resimUrl = trim($_POST['resim_url'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : $sonraSira;

    if (isset($_FILES['resim_dosya']) && $_FILES['resim_dosya']['error'] === UPLOAD_ERR_OK) {
        $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $uzanti = strtolower(pathinfo($_FILES['resim_dosya']['name'], PATHINFO_EXTENSION));
        if (in_array($uzanti, $izinliUzantilar)) {
            $yeniAd = 'hizmet_' . uniqid() . '.' . $uzanti;
            if (move_uploaded_file($_FILES['resim_dosya']['tmp_name'], '../uploads/' . $yeniAd)) {
                $resimUrl = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($baslik === '' || $detay === '') {
        $hataMesaji = 'Lütfen başlık ve detay alanlarını doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO hizmet_kartlari (ikon, resim_url, baslik, ozet, detay, link, sira)
             VALUES (:ikon, :resim_url, :baslik, :ozet, :detay, :link, :sira)"
        );
        $stmt->execute([
            'ikon' => $ikon,
            'resim_url' => $resimUrl,
            'baslik' => $baslik,
            'ozet' => $ozet,
            'detay' => $detay,
            'link' => $link,
            'sira' => $sira,
        ]);

        islemKaydet($pdo, 'Hizmet Kartı Ekledi', $baslik);
        header('Location: hizmet-karti-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Hizmet Ekle';
$aktifMenu = 'sabit-hizmetler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Hizmet Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="hizmet-karti-ekle.php" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">İkon</label>
                <input type="text" name="ikon" class="form-control" placeholder="bi-grid-fill"
                       value="<?php echo htmlspecialchars($_POST['ikon'] ?? 'bi-grid-fill'); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)($_POST['sira'] ?? $sonraSira); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Özet (kutucukta görünür kısa metin)</label>
                <input type="text" name="ozet" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['ozet'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Detay (tıklanınca açılan pencerede görünür) *</label>
                <textarea name="detay" rows="4" class="form-control" required><?php echo htmlspecialchars($_POST['detay'] ?? ''); ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Bağlantı</label>
                <input type="text" name="link" class="form-control" placeholder="hizmetler/fen-isleri.php"
                       value="<?php echo htmlspecialchars($_POST['link'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bilgisayardan Görsel Yükle (varsa URL'nin önüne geçer)</label>
                <input type="file" name="resim_dosya" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Görsel URL</label>
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
