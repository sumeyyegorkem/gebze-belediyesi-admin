<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM sosyal_medya WHERE id = :id");
$stmt->execute(['id' => $id]);
$hesap = $stmt->fetch();

if (!$hesap) {
    header('Location: sosyal-medya-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $platform = trim($_POST['platform'] ?? '');
    $ikon = trim($_POST['ikon'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $sira = (int)($_POST['sira'] ?? 0);

    if ($platform === '' || $ikon === '' || $url === '') {
        $hataMesaji = 'Lütfen platform adı, ikon ve bağlantı alanlarının tamamını doldurun.';
    } else {
        $stmt = $pdo->prepare("UPDATE sosyal_medya SET platform = :platform, ikon = :ikon, url = :url, sira = :sira WHERE id = :id");
        $stmt->execute([
            'platform' => $platform,
            'ikon' => $ikon,
            'url' => $url,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Sosyal Medya Düzenledi', $platform);
        header('Location: sosyal-medya-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Sosyal Medya Hesabı Düzenle';
$aktifMenu = 'sosyal-medya';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-fill me-2"></i>Sosyal Medya Hesabı Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="sosyal-medya-duzenle.php?id=<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Platform Adı *</label>
                <input type="text" name="platform" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['platform'] ?? $hesap['platform']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">İkon *</label>
                <input type="text" name="ikon" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ikon'] ?? $hesap['ikon']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bağlantı *</label>
                <input type="text" name="url" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['url'] ?? $hesap['url']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['sira'] ?? $hesap['sira']); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="sosyal-medya-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
