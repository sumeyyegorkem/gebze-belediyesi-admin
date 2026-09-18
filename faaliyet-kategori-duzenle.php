<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM faaliyet_kategorileri WHERE id = :id");
$stmt->execute(['id' => $id]);
$kategori = $stmt->fetch();

if (!$kategori) {
    header('Location: faaliyet-alanlari-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $ikon = trim($_POST['ikon'] ?? '') ?: 'bi-grid-fill';
    $sira = (int)($_POST['sira'] ?? 0);

    if ($baslik === '') {
        $hataMesaji = 'Lütfen kategori başlığını doldurun.';
    } else {
        $stmt = $pdo->prepare("UPDATE faaliyet_kategorileri SET baslik = :baslik, ikon = :ikon, sira = :sira WHERE id = :id");
        $stmt->execute([
            'baslik' => $baslik,
            'ikon' => $ikon,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Faaliyet Kategorisi Düzenledi', $baslik);
        header('Location: faaliyet-alanlari-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Faaliyet Kategorisi Düzenle';
$aktifMenu = 'faaliyet-alanlari';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-fill me-2"></i>Faaliyet Kategorisi Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="faaliyet-kategori-duzenle.php?id=<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Kategori Başlığı *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? $kategori['baslik']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['sira'] ?? $kategori['sira']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">İkon</label>
                <input type="text" name="ikon" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['ikon'] ?? $kategori['ikon']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Anahtar</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($kategori['anahtar']); ?>" disabled>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="faaliyet-alanlari-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
