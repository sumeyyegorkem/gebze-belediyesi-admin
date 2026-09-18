<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM uye_birlikler WHERE id = :id");
$stmt->execute(['id' => $id]);
$birlik = $stmt->fetch();

if (!$birlik) {
    header('Location: uye-birlik-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $sira = (int)($_POST['sira'] ?? 0);

    if ($ad === '') {
        $hataMesaji = 'Lütfen birlik/platform adını girin.';
    } else {
        $stmt = $pdo->prepare("UPDATE uye_birlikler SET ad = :ad, url = :url, sira = :sira WHERE id = :id");
        $stmt->execute([
            'ad' => $ad,
            'url' => $url !== '' ? $url : null,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Üye Birlik Düzenledi', $ad);
        header('Location: uye-birlik-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Birlik Düzenle';
$aktifMenu = 'uye-birlikler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-fill me-2"></i>Birlik Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="uye-birlik-duzenle.php?id=<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Adı *</label>
                <input type="text" name="ad" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ad'] ?? $birlik['ad']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['sira'] ?? $birlik['sira']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bağlantı (URL) <span class="text-muted small">— boş bırakılabilir</span></label>
                <input type="text" name="url" class="form-control" placeholder="https://..."
                       value="<?php echo htmlspecialchars($_POST['url'] ?? $birlik['url']); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="uye-birlik-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
