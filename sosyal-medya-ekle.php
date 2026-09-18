<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $platform = trim($_POST['platform'] ?? '');
    $ikon = trim($_POST['ikon'] ?? '');
    $url = trim($_POST['url'] ?? '');

    if ($platform === '' || $ikon === '' || $url === '') {
        $hataMesaji = 'Lütfen platform adı, ikon ve bağlantı alanlarının tamamını doldurun.';
    } else {
        $sonraSira = (int)$pdo->query("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM sosyal_medya")->fetch()['sonraki'];

        $stmt = $pdo->prepare("INSERT INTO sosyal_medya (platform, ikon, url, sira) VALUES (:platform, :ikon, :url, :sira)");
        $stmt->execute([
            'platform' => $platform,
            'ikon' => $ikon,
            'url' => $url,
            'sira' => $sonraSira,
        ]);

        islemKaydet($pdo, 'Sosyal Medya Ekledi', $platform);
        header('Location: sosyal-medya-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Sosyal Medya Hesabı Ekle';
$aktifMenu = 'sosyal-medya';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Sosyal Medya Hesabı Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="sosyal-medya-ekle.php">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Platform Adı *</label>
                <input type="text" name="platform" class="form-control" placeholder="Facebook" required
                       value="<?php echo htmlspecialchars($_POST['platform'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">İkon *</label>
                <input type="text" name="ikon" class="form-control" placeholder="bi-facebook" required
                       value="<?php echo htmlspecialchars($_POST['ikon'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bağlantı *</label>
                <input type="text" name="url" class="form-control" placeholder="https://..." required
                       value="<?php echo htmlspecialchars($_POST['url'] ?? ''); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
            <a href="sosyal-medya-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
