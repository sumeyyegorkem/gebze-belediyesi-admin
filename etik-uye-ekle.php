<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';

$sonraSira = (int)$pdo->query("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM etik_komisyonu_uyeleri")->fetch()['sonraki'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $unvan = trim($_POST['unvan'] ?? '');
    $gorev = trim($_POST['gorev'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : $sonraSira;

    if ($adSoyad === '') {
        $hataMesaji = 'Lütfen ad soyad alanını doldurun.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO etik_komisyonu_uyeleri (ad_soyad, unvan, gorev, sira) VALUES (:ad, :unvan, :gorev, :sira)");
        $stmt->execute([
            'ad' => $adSoyad,
            'unvan' => $unvan,
            'gorev' => $gorev,
            'sira' => $sira,
        ]);

        islemKaydet($pdo, 'Etik Komisyonu Üyesi Ekledi', $adSoyad);
        header('Location: etik-uye-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Üye Ekle';
$aktifMenu = 'kurumsal-etik';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Etik Komisyonu Üyesi Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="etik-uye-ekle.php">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Ad Soyad *</label>
                <input type="text" name="ad_soyad" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ad_soyad'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Ünvanı</label>
                <input type="text" name="unvan" class="form-control" placeholder="Başkan Yardımcısı"
                       value="<?php echo htmlspecialchars($_POST['unvan'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)($_POST['sira'] ?? $sonraSira); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Görevi <span class="text-muted small">(örn: Komisyon Başkanı)</span></label>
                <input type="text" name="gorev" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['gorev'] ?? ''); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
