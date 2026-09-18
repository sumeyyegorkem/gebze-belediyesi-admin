<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM kardes_sehirler WHERE id = :id");
$stmt->execute(['id' => $id]);
$kardesSehir = $stmt->fetch();

if (!$kardesSehir) {
    header('Location: kardes-sehir-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tur = $kardesSehir['tur']; // tür değiştirilemez, sadece bilgiler güncellenir
    $ad = trim($_POST['ad'] ?? '');
    $il = trim($_POST['il'] ?? '');
    $sehir = trim($_POST['sehir'] ?? '');
    $ulke = trim($_POST['ulke'] ?? '');
    $sira = (int)($_POST['sira'] ?? 0);

    $eksik = $tur === 'ici' ? ($ad === '' || $il === '') : ($ad === '' || $sehir === '' || $ulke === '');

    if ($eksik) {
        $hataMesaji = $tur === 'ici'
            ? 'Lütfen belediye adı ve il alanlarını doldurun.'
            : 'Lütfen belediye/şehir adı, şehir ve ülke alanlarını doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "UPDATE kardes_sehirler SET ad = :ad, il = :il, sehir = :sehir, ulke = :ulke, sira = :sira WHERE id = :id"
        );
        $stmt->execute([
            'ad' => $ad,
            'il' => $tur === 'ici' ? $il : null,
            'sehir' => $tur === 'disi' ? $sehir : null,
            'ulke' => $tur === 'disi' ? $ulke : null,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Kardeş Şehir Düzenledi', $ad);
        header('Location: kardes-sehir-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Kardeş Şehir Düzenle';
$aktifMenu = 'kardes-sehirler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-fill me-2"></i>Kardeş Şehir Düzenle</h4>

    <p class="text-muted small">
        Tür: <strong><?php echo $kardesSehir['tur'] === 'ici' ? 'Yurt İçi' : 'Yurt Dışı'; ?></strong>
    </p>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="kardes-sehir-duzenle.php?id=<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Belediye / Şehir Adı *</label>
                <input type="text" name="ad" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ad'] ?? $kardesSehir['ad']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['sira'] ?? $kardesSehir['sira']); ?>">
            </div>

            <?php if ($kardesSehir['tur'] === 'ici'): ?>
            <div class="col-md-6">
                <label class="form-label">İl *</label>
                <input type="text" name="il" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['il'] ?? $kardesSehir['il']); ?>">
            </div>
            <?php else: ?>
            <div class="col-md-6">
                <label class="form-label">Şehir *</label>
                <input type="text" name="sehir" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['sehir'] ?? $kardesSehir['sehir']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Ülke *</label>
                <input type="text" name="ulke" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ulke'] ?? $kardesSehir['ulke']); ?>">
            </div>
            <?php endif; ?>

            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="kardes-sehir-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
