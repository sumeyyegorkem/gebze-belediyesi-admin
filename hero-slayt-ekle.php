<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';

$sonraSira = (int)$pdo->query("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM hero_slaytlari")->fetch()['sonraki'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslikOn = trim($_POST['baslik_on'] ?? '');
    $baslikVurgu = trim($_POST['baslik_vurgu'] ?? '');
    $baslikSon = trim($_POST['baslik_son'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    $resimUrl = trim($_POST['resim_url'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : $sonraSira;

    if (isset($_FILES['resim_dosya']) && $_FILES['resim_dosya']['error'] === UPLOAD_ERR_OK) {
        $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $uzanti = strtolower(pathinfo($_FILES['resim_dosya']['name'], PATHINFO_EXTENSION));
        if (in_array($uzanti, $izinliUzantilar)) {
            $yeniAd = 'hero_' . uniqid() . '.' . $uzanti;
            if (move_uploaded_file($_FILES['resim_dosya']['tmp_name'], '../uploads/' . $yeniAd)) {
                $resimUrl = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($baslikOn === '' && $baslikVurgu === '') {
        $hataMesaji = 'Lütfen en azından başlık metnini girin.';
    } elseif ($resimUrl === '') {
        $hataMesaji = 'Lütfen bir görsel yükleyin ya da görsel URL girin.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO hero_slaytlari (resim_url, baslik_on, baslik_vurgu, baslik_son, aciklama, sira)
             VALUES (:resim_url, :baslik_on, :baslik_vurgu, :baslik_son, :aciklama, :sira)"
        );
        $stmt->execute([
            'resim_url' => $resimUrl,
            'baslik_on' => $baslikOn,
            'baslik_vurgu' => $baslikVurgu,
            'baslik_son' => $baslikSon,
            'aciklama' => $aciklama,
            'sira' => $sira,
        ]);

        islemKaydet($pdo, 'Hero Slayt Ekledi', $baslikOn);
        header('Location: hero-slayt-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Hero Slaytı Ekle';
$aktifMenu = 'hero-slaytlari';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Hero Slaytı Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="hero-slayt-ekle.php" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Başlık - Vurgudan Önceki Kısım</label>
                <input type="text" name="baslik_on" class="form-control" placeholder="Gebze'nin "
                       value="<?php echo htmlspecialchars($_POST['baslik_on'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Başlık - Vurgulu Kelime <span class="text-muted small">(renkli gösterilir)</span></label>
                <input type="text" name="baslik_vurgu" class="form-control" placeholder="Geleceğine"
                       value="<?php echo htmlspecialchars($_POST['baslik_vurgu'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Başlık - Vurgudan Sonraki Kısım</label>
                <input type="text" name="baslik_son" class="form-control" placeholder=" Birlikte Yön Veriyoruz"
                       value="<?php echo htmlspecialchars($_POST['baslik_son'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Açıklama <span class="text-muted small">(opsiyonel, başlığın altındaki metin)</span></label>
                <textarea name="aciklama" rows="2" class="form-control"><?php echo htmlspecialchars($_POST['aciklama'] ?? ''); ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)($_POST['sira'] ?? $sonraSira); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bilgisayardan Görsel Yükle (varsa URL'nin önüne geçer)</label>
                <input type="file" name="resim_dosya" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Görsel URL</label>
                <input type="text" name="resim_url" class="form-control" placeholder="img/hero-5.jpg"
                       value="<?php echo htmlspecialchars($_POST['resim_url'] ?? ''); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
            <a href="hero-slayt-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
