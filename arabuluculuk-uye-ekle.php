<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tip = ($_POST['tip'] ?? 'asil') === 'yedek' ? 'yedek' : 'asil';
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $gorev = trim($_POST['gorev'] ?? '');
    $stmtSira = $pdo->prepare("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM arabuluculuk_uyeleri WHERE tip = :tip");
    $stmtSira->execute(['tip' => $tip]);
    $sonraSira = (int)$stmtSira->fetch()['sonraki'];
    $sira = isset($_POST['sira']) && $_POST['sira'] !== '' ? (int)$_POST['sira'] : $sonraSira;

    if ($adSoyad === '') {
        $hataMesaji = 'Lütfen ad soyad alanını doldurun.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO arabuluculuk_uyeleri (tip, ad_soyad, gorev, sira) VALUES (:tip, :ad, :gorev, :sira)");
        $stmt->execute([
            'tip' => $tip,
            'ad' => $adSoyad,
            'gorev' => $gorev,
            'sira' => $sira,
        ]);

        islemKaydet($pdo, 'Arabuluculuk Üyesi Ekledi', $adSoyad);
        header('Location: arabuluculuk-uye-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Üye Ekle';
$aktifMenu = 'kurumsal-arabuluculuk';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Arabuluculuk Komisyonu Üyesi Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="arabuluculuk-uye-ekle.php">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tip *</label>
                <select name="tip" class="form-select">
                    <option value="asil" <?php echo (($_POST['tip'] ?? '') === 'asil') ? 'selected' : ''; ?>>Asıl Üye</option>
                    <option value="yedek" <?php echo (($_POST['tip'] ?? '') === 'yedek') ? 'selected' : ''; ?>>Yedek Üye</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ad Soyad *</label>
                <input type="text" name="ad_soyad" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ad_soyad'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra <span class="text-muted small">(boş bırakılırsa sona eklenir)</span></label>
                <input type="number" name="sira" class="form-control" value="<?php echo htmlspecialchars($_POST['sira'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Görev <span class="text-muted small">(örn: Hukuk İşleri Müdürlüğü)</span></label>
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
