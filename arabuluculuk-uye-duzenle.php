<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM arabuluculuk_uyeleri WHERE id = :id");
$stmt->execute(['id' => $id]);
$uye = $stmt->fetch();

if (!$uye) {
    header('Location: arabuluculuk-uye-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tip = ($_POST['tip'] ?? 'asil') === 'yedek' ? 'yedek' : 'asil';
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $gorev = trim($_POST['gorev'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : (int)$uye['sira'];

    if ($adSoyad === '') {
        $hataMesaji = 'Lütfen ad soyad alanını doldurun.';
    } else {
        $guncelle = $pdo->prepare(
            "UPDATE arabuluculuk_uyeleri
             SET tip = :tip, ad_soyad = :ad, gorev = :gorev, sira = :sira
             WHERE id = :id"
        );
        $guncelle->execute([
            'tip' => $tip,
            'ad' => $adSoyad,
            'gorev' => $gorev,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Arabuluculuk Üyesi Düzenledi', $adSoyad);
        header('Location: arabuluculuk-uye-yonet.php?basarili=1');
        exit;
    }
    $uye = array_merge($uye, $_POST);
}

$sayfaBasligi = 'Üye Düzenle';
$aktifMenu = 'kurumsal-arabuluculuk';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Arabuluculuk Komisyonu Üyesi Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="arabuluculuk-uye-duzenle.php?id=<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tip *</label>
                <select name="tip" class="form-select">
                    <option value="asil" <?php echo ($uye['tip'] === 'asil') ? 'selected' : ''; ?>>Asıl Üye</option>
                    <option value="yedek" <?php echo ($uye['tip'] === 'yedek') ? 'selected' : ''; ?>>Yedek Üye</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ad Soyad *</label>
                <input type="text" name="ad_soyad" class="form-control" required
                       value="<?php echo htmlspecialchars($uye['ad_soyad']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)$uye['sira']; ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Görev</label>
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
