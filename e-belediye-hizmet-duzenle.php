<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM e_belediye_hizmetleri WHERE id = :id");
$stmt->execute(['id' => $id]);
$hizmet = $stmt->fetch();

if (!$hizmet) {
    header('Location: e-belediye-yonet.php');
    exit;
}

$ozelMi = !empty($hizmet['ozel_hedef']);

$bolumler = $pdo->query(
    "SELECT b.*, k.baslik AS kategori_baslik FROM e_belediye_bolumler b
     JOIN e_belediye_kategoriler k ON k.id = b.kategori_id
     ORDER BY k.sira ASC, b.sira ASC"
)->fetchAll();

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bolumId = (int)($_POST['bolum_id'] ?? 0);
    $baslik = trim($_POST['baslik'] ?? '');
    $ikon = trim($_POST['ikon'] ?? '') ?: 'bi-info-circle-fill';
    $href = trim($_POST['href'] ?? '');
    $sira = (int)($_POST['sira'] ?? 0);

    if ($baslik === '' || $bolumId === 0 || (!$ozelMi && $href === '')) {
        $hataMesaji = 'Lütfen bölüm seçin, başlık' . (!$ozelMi ? ' ve bağlantı (href)' : '') . ' alanlarını doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "UPDATE e_belediye_hizmetleri SET bolum_id = :bolum_id, ikon = :ikon, baslik = :baslik, href = :href, sira = :sira WHERE id = :id"
        );
        $stmt->execute([
            'bolum_id' => $bolumId,
            'ikon' => $ikon,
            'baslik' => $baslik,
            'href' => $ozelMi ? null : $href,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'E-Belediye Hizmeti Düzenledi', $baslik);
        header('Location: e-belediye-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'E-Belediye Hizmeti Düzenle';
$aktifMenu = 'e-belediye';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-fill me-2"></i>E-Belediye Hizmeti Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="e-belediye-hizmet-duzenle.php?id=<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Bölüm *</label>
                <select name="bolum_id" class="form-select" required>
                    <?php foreach ($bolumler as $b): ?>
                        <option value="<?php echo $b['id']; ?>" <?php echo ((int)($_POST['bolum_id'] ?? $hizmet['bolum_id']) === (int)$b['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($b['kategori_baslik'] . ' — ' . $b['baslik']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['sira'] ?? $hizmet['sira']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">İkon</label>
                <input type="text" name="ikon" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['ikon'] ?? $hizmet['ikon']); ?>">
            </div>
            <div class="col-md-8">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? $hizmet['baslik']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bağlantı<?php echo $ozelMi ? '' : ' *'; ?></label>
                <input type="text" name="href" class="form-control" <?php echo $ozelMi ? 'disabled' : 'required'; ?>
                       value="<?php echo $ozelMi ? '' : htmlspecialchars($_POST['href'] ?? $hizmet['href']); ?>"
                       placeholder="<?php echo $ozelMi ? 'Bu hizmet bir modal ile çalışıyor, bağlantısı yok' : 'https://...'; ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="e-belediye-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
