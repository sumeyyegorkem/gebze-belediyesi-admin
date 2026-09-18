<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$bolumler = $pdo->query(
    "SELECT b.*, k.baslik AS kategori_baslik FROM e_belediye_bolumler b
     JOIN e_belediye_kategoriler k ON k.id = b.kategori_id
     ORDER BY k.sira ASC, b.sira ASC"
)->fetchAll();
$seciliBolumId = (int)($_GET['bolum_id'] ?? 0);

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bolumId = (int)($_POST['bolum_id'] ?? 0);
    $baslik = trim($_POST['baslik'] ?? '');
    $ikon = trim($_POST['ikon'] ?? '') ?: 'bi-info-circle-fill';
    $href = trim($_POST['href'] ?? '');

    if ($baslik === '' || $bolumId === 0 || $href === '') {
        $hataMesaji = 'Lütfen bölüm seçin, başlık ve bağlantı (href) alanlarını doldurun.';
    } else {
        $stmtSira = $pdo->prepare("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM e_belediye_hizmetleri WHERE bolum_id = :bolum_id");
        $stmtSira->execute(['bolum_id' => $bolumId]);
        $sonraSira = (int)$stmtSira->fetch()['sonraki'];

        $stmt = $pdo->prepare(
            "INSERT INTO e_belediye_hizmetleri (bolum_id, ikon, baslik, href, ozel_hedef, sira)
             VALUES (:bolum_id, :ikon, :baslik, :href, NULL, :sira)"
        );
        $stmt->execute([
            'bolum_id' => $bolumId,
            'ikon' => $ikon,
            'baslik' => $baslik,
            'href' => $href,
            'sira' => $sonraSira,
        ]);

        islemKaydet($pdo, 'E-Belediye Hizmeti Ekledi', $baslik);
        header('Location: e-belediye-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni E-Belediye Hizmeti Ekle';
$aktifMenu = 'e-belediye';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni E-Belediye Hizmeti Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <?php if (count($bolumler) === 0): ?>
        <div class="alert alert-warning">Önce en az bir bölüm eklemelisiniz.</div>
    <?php else: ?>
    <form method="POST" action="e-belediye-hizmet-ekle.php">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Bölüm *</label>
                <select name="bolum_id" class="form-select" required>
                    <option value="">Seçiniz</option>
                    <?php foreach ($bolumler as $b): ?>
                        <option value="<?php echo $b['id']; ?>" <?php echo ((int)($_POST['bolum_id'] ?? $seciliBolumId) === (int)$b['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($b['kategori_baslik'] . ' — ' . $b['baslik']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">İkon</label>
                <input type="text" name="ikon" class="form-control" placeholder="bi-info-circle-fill"
                       value="<?php echo htmlspecialchars($_POST['ikon'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bağlantı *</label>
                <input type="text" name="href" class="form-control" required placeholder="https://..."
                       value="<?php echo htmlspecialchars($_POST['href'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="e-belediye-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php include 'includes/admin-bitis.php'; ?>
