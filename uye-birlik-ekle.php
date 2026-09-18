<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $url = trim($_POST['url'] ?? '');

    if ($ad === '') {
        $hataMesaji = 'Lütfen birlik/platform adını girin.';
    } else {
        $stmtSira = $pdo->query("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM uye_birlikler");
        $sonraSira = (int)$stmtSira->fetch()['sonraki'];

        $stmt = $pdo->prepare("INSERT INTO uye_birlikler (ad, url, sira) VALUES (:ad, :url, :sira)");
        $stmt->execute([
            'ad' => $ad,
            'url' => $url !== '' ? $url : null,
            'sira' => $sonraSira,
        ]);

        islemKaydet($pdo, 'Üye Birlik Ekledi', $ad);
        header('Location: uye-birlik-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Birlik Ekle';
$aktifMenu = 'uye-birlikler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Birlik Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="uye-birlik-ekle.php">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Adı *</label>
                <input type="text" name="ad" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ad'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bağlantı (URL) <span class="text-muted small">— boş bırakılabilir</span></label>
                <input type="text" name="url" class="form-control" placeholder="https://..."
                       value="<?php echo htmlspecialchars($_POST['url'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="uye-birlik-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
