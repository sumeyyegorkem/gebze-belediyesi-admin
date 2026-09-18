<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$tur = ($_GET['tur'] ?? $_POST['tur'] ?? 'ici') === 'disi' ? 'disi' : 'ici';
$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tur = ($_POST['tur'] ?? 'ici') === 'disi' ? 'disi' : 'ici';
    $ad = trim($_POST['ad'] ?? '');
    $il = trim($_POST['il'] ?? '');
    $sehir = trim($_POST['sehir'] ?? '');
    $ulke = trim($_POST['ulke'] ?? '');

    $eksik = $tur === 'ici' ? ($ad === '' || $il === '') : ($ad === '' || $sehir === '' || $ulke === '');

    if ($eksik) {
        $hataMesaji = $tur === 'ici'
            ? 'Lütfen belediye adı ve il alanlarını doldurun.'
            : 'Lütfen belediye/şehir adı, şehir ve ülke alanlarını doldurun.';
    } else {
        $stmtSira = $pdo->prepare("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM kardes_sehirler WHERE tur = :tur");
        $stmtSira->execute(['tur' => $tur]);
        $sonraSira = (int)$stmtSira->fetch()['sonraki'];

        $stmt = $pdo->prepare(
            "INSERT INTO kardes_sehirler (tur, ad, il, sehir, ulke, sira) VALUES (:tur, :ad, :il, :sehir, :ulke, :sira)"
        );
        $stmt->execute([
            'tur' => $tur,
            'ad' => $ad,
            'il' => $tur === 'ici' ? $il : null,
            'sehir' => $tur === 'disi' ? $sehir : null,
            'ulke' => $tur === 'disi' ? $ulke : null,
            'sira' => $sonraSira,
        ]);

        islemKaydet($pdo, 'Kardeş Şehir Ekledi', $ad);
        header('Location: kardes-sehir-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Kardeş Şehir Ekle';
$aktifMenu = 'kardes-sehirler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Kardeş Şehir Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="kardes-sehir-ekle.php" id="kardesForm">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Tür *</label>
                <select name="tur" id="turSecim" class="form-select" onchange="turDegisti()">
                    <option value="ici" <?php echo $tur === 'ici' ? 'selected' : ''; ?>>Yurt İçi</option>
                    <option value="disi" <?php echo $tur === 'disi' ? 'selected' : ''; ?>>Yurt Dışı</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Belediye / Şehir Adı *</label>
                <input type="text" name="ad" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ad'] ?? ''); ?>">
            </div>

            <div class="col-md-4 alan-ici">
                <label class="form-label">İl *</label>
                <input type="text" name="il" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['il'] ?? ''); ?>">
            </div>

            <div class="col-md-4 alan-disi">
                <label class="form-label">Şehir *</label>
                <input type="text" name="sehir" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['sehir'] ?? ''); ?>">
            </div>
            <div class="col-md-4 alan-disi">
                <label class="form-label">Ülke *</label>
                <input type="text" name="ulke" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['ulke'] ?? ''); ?>">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="kardes-sehir-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
</div>

<script>
function turDegisti() {
    var tur = document.getElementById('turSecim').value;
    var iciAlanlar = document.querySelectorAll('.alan-ici');
    var disiAlanlar = document.querySelectorAll('.alan-disi');
    iciAlanlar.forEach(function (el) { el.style.display = tur === 'ici' ? '' : 'none'; });
    disiAlanlar.forEach(function (el) { el.style.display = tur === 'disi' ? '' : 'none'; });
}
turDegisti();
</script>

<?php include 'includes/admin-bitis.php'; ?>
