<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$kategoriId = (int)($_GET['kategori_id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM e_belediye_kategoriler WHERE id = :id");
$stmt->execute(['id' => $kategoriId]);
$kategori = $stmt->fetch();

if (!$kategori) {
    header('Location: e-belediye-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');

    if ($baslik === '') {
        $hataMesaji = 'Lütfen bölüm başlığını doldurun.';
    } else {
        $stmtSira = $pdo->prepare("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM e_belediye_bolumler WHERE kategori_id = :id");
        $stmtSira->execute(['id' => $kategoriId]);
        $sonraSira = (int)$stmtSira->fetch()['sonraki'];

        $stmt = $pdo->prepare("INSERT INTO e_belediye_bolumler (kategori_id, baslik, sira) VALUES (:kategori_id, :baslik, :sira)");
        $stmt->execute([
            'kategori_id' => $kategoriId,
            'baslik' => $baslik,
            'sira' => $sonraSira,
        ]);

        islemKaydet($pdo, 'E-Belediye Bölümü Ekledi', $baslik);
        header('Location: e-belediye-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Bölüm Ekle';
$aktifMenu = 'e-belediye';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>"<?php echo htmlspecialchars($kategori['baslik']); ?>" içine Yeni Bölüm Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="e-belediye-bolum-ekle.php?kategori_id=<?php echo $kategoriId; ?>">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Bölüm Başlığı *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="e-belediye-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
