<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM eski_baskanlar WHERE id = :id");
$stmt->execute(['id' => $id]);
$baskan = $stmt->fetch();

if (!$baskan) {
    header('Location: eski-baskan-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donemGrubu = trim($_POST['donem_grubu'] ?? '');
    $yilAraligi = trim($_POST['yil_araligi'] ?? '');
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $fotoUrl = trim($_POST['foto_url'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : (int)$baskan['sira'];

    if (isset($_FILES['foto_dosya']) && $_FILES['foto_dosya']['error'] === UPLOAD_ERR_OK) {
        $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $uzanti = strtolower(pathinfo($_FILES['foto_dosya']['name'], PATHINFO_EXTENSION));
        if (in_array($uzanti, $izinliUzantilar)) {
            $yeniAd = 'baskan_' . uniqid() . '.' . $uzanti;
            if (move_uploaded_file($_FILES['foto_dosya']['tmp_name'], '../uploads/' . $yeniAd)) {
                $fotoUrl = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($donemGrubu === '' || $yilAraligi === '' || $adSoyad === '') {
        $hataMesaji = 'Lütfen dönem grubu, yıl aralığı ve ad soyad alanlarını doldurun.';
    } else {
        $guncelle = $pdo->prepare(
            "UPDATE eski_baskanlar
             SET donem_grubu = :grup, yil_araligi = :yil, ad_soyad = :ad, foto_url = :foto, sira = :sira
             WHERE id = :id"
        );
        $guncelle->execute([
            'grup' => $donemGrubu,
            'yil' => $yilAraligi,
            'ad' => $adSoyad,
            'foto' => $fotoUrl,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Eski Başkan Düzenledi', $adSoyad);
        header('Location: eski-baskan-yonet.php?basarili=1');
        exit;
    }
    $baskan = array_merge($baskan, $_POST);
}

$baskanFotoYolu = (strpos($baskan['foto_url'], 'http://') === 0 || strpos($baskan['foto_url'], 'https://') === 0)
    ? $baskan['foto_url']
    : '../' . $baskan['foto_url'];

$sayfaBasligi = 'Başkan Düzenle';
$aktifMenu = 'kurumsal-eski-baskanlar';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Başkan Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="eski-baskan-duzenle.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Dönem Grubu *</label>
                <input type="text" name="donem_grubu" class="form-control" required
                       value="<?php echo htmlspecialchars($baskan['donem_grubu']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Yıl Aralığı *</label>
                <input type="text" name="yil_araligi" class="form-control" required
                       value="<?php echo htmlspecialchars($baskan['yil_araligi']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)$baskan['sira']; ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Ad Soyad *</label>
                <input type="text" name="ad_soyad" class="form-control" required
                       value="<?php echo htmlspecialchars($baskan['ad_soyad']); ?>">
            </div>
            <div class="col-12">
                <img src="<?php echo htmlspecialchars($baskanFotoYolu); ?>" class="mb-2 rounded-3" style="max-height:120px;"
                     onerror="this.style.display='none';">
                <label class="form-label d-block">Bilgisayardan Yeni Görsel Yükle (seçilirse mevcut görselin yerine geçer)</label>
                <input type="file" name="foto_dosya" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Görsel URL</label>
                <input type="text" name="foto_url" class="form-control"
                       value="<?php echo htmlspecialchars($baskan['foto_url']); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
