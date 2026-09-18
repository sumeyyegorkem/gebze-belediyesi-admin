<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM etik_komisyonu_uyeleri WHERE id = :id");
$stmt->execute(['id' => $id]);
$uye = $stmt->fetch();

if (!$uye) {
    header('Location: etik-uye-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $unvan = trim($_POST['unvan'] ?? '');
    $gorev = trim($_POST['gorev'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : (int)$uye['sira'];

    if ($adSoyad === '') {
        $hataMesaji = 'Lütfen ad soyad alanını doldurun.';
    } else {
        $guncelle = $pdo->prepare(
            "UPDATE etik_komisyonu_uyeleri
             SET ad_soyad = :ad, unvan = :unvan, gorev = :gorev, sira = :sira
             WHERE id = :id"
        );
        $guncelle->execute([
            'ad' => $adSoyad,
            'unvan' => $unvan,
            'gorev' => $gorev,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Etik Komisyonu Üyesi Düzenledi', $adSoyad);
        header('Location: etik-uye-yonet.php?basarili=1');
        exit;
    }
    $uye = array_merge($uye, $_POST);
}

$sayfaBasligi = 'Üye Düzenle';
$aktifMenu = 'kurumsal-etik';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Etik Komisyonu Üyesi Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="etik-uye-duzenle.php?id=<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Ad Soyad *</label>
                <input type="text" name="ad_soyad" class="form-control" required
                       value="<?php echo htmlspecialchars($uye['ad_soyad']); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Ünvanı</label>
                <input type="text" name="unvan" class="form-control"
                       value="<?php echo htmlspecialchars($uye['unvan']); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)$uye['sira']; ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Görevi</label>
                <input type="text" name="gorev" class="form-control"
                       value="<?php echo htmlspecialchars($uye['gorev']); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
