<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM meclis_uyeleri WHERE id = :id");
$stmt->execute(['id' => $id]);
$uye = $stmt->fetch();

if (!$uye) {
    header('Location: meclis-uyesi-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $fotoUrl = trim($_POST['foto_url'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : (int)$uye['sira'];

    if (isset($_FILES['foto_dosya']) && $_FILES['foto_dosya']['error'] === UPLOAD_ERR_OK) {
        $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $uzanti = strtolower(pathinfo($_FILES['foto_dosya']['name'], PATHINFO_EXTENSION));
        if (in_array($uzanti, $izinliUzantilar)) {
            $yeniAd = 'meclis_' . uniqid() . '.' . $uzanti;
            if (move_uploaded_file($_FILES['foto_dosya']['tmp_name'], '../uploads/' . $yeniAd)) {
                $fotoUrl = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($adSoyad === '') {
        $hataMesaji = 'Lütfen ad soyad alanını doldurun.';
    } else {
        $guncelle = $pdo->prepare(
            "UPDATE meclis_uyeleri
             SET ad_soyad = :ad, foto_url = :foto, sira = :sira
             WHERE id = :id"
        );
        $guncelle->execute([
            'ad' => $adSoyad,
            'foto' => $fotoUrl,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Meclis Üyesi Düzenledi', $adSoyad);
        header('Location: meclis-uyesi-yonet.php?basarili=1');
        exit;
    }
    $uye = array_merge($uye, $_POST);
}

$uyeFotoYolu = (strpos($uye['foto_url'], 'http://') === 0 || strpos($uye['foto_url'], 'https://') === 0)
    ? $uye['foto_url']
    : '../' . $uye['foto_url'];

$sayfaBasligi = 'Meclis Üyesi Düzenle';
$aktifMenu = 'sabit-meclis';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Meclis Üyesi Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="meclis-uyesi-duzenle.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-9">
                <label class="form-label">Ad Soyad *</label>
                <input type="text" name="ad_soyad" class="form-control" required
                       value="<?php echo htmlspecialchars($uye['ad_soyad']); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)$uye['sira']; ?>">
            </div>
            <div class="col-12">
                <img src="<?php echo htmlspecialchars($uyeFotoYolu); ?>" class="mb-2 rounded-3" style="max-height:120px;"
                     onerror="this.style.display='none';">
                <label class="form-label d-block">Bilgisayardan Yeni Görsel Yükle (seçilirse mevcut görselin yerine geçer)</label>
                <input type="file" name="foto_dosya" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Görsel URL</label>
                <input type="text" name="foto_url" class="form-control"
                       value="<?php echo htmlspecialchars($uye['foto_url']); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
