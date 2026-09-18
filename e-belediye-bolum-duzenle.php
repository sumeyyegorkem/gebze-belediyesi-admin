<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM e_belediye_bolumler WHERE id = :id");
$stmt->execute(['id' => $id]);
$bolum = $stmt->fetch();

if (!$bolum) {
    header('Location: e-belediye-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $sira = (int)($_POST['sira'] ?? 0);

    if ($baslik === '') {
        $hataMesaji = 'Lütfen bölüm başlığını doldurun.';
    } else {
        $stmt = $pdo->prepare("UPDATE e_belediye_bolumler SET baslik = :baslik, sira = :sira WHERE id = :id");
        $stmt->execute(['baslik' => $baslik, 'sira' => $sira, 'id' => $id]);

        islemKaydet($pdo, 'E-Belediye Bölümü Düzenledi', $baslik);
        header('Location: e-belediye-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Bölüm Düzenle';
$aktifMenu = 'e-belediye';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-fill me-2"></i>Bölüm Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="e-belediye-bolum-duzenle.php?id=<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Bölüm Başlığı *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? $bolum['baslik']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['sira'] ?? $bolum['sira']); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="e-belediye-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
