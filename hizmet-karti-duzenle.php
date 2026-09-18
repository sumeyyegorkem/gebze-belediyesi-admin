<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM hizmet_kartlari WHERE id = :id");
$stmt->execute(['id' => $id]);
$hizmet = $stmt->fetch();

if (!$hizmet) {
    header('Location: hizmet-karti-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $ozet = trim($_POST['ozet'] ?? '');
    $detay = trim($_POST['detay'] ?? '');
    $ikon = trim($_POST['ikon'] ?? '') ?: 'bi-grid-fill';
    $link = trim($_POST['link'] ?? '');
    $resimUrl = trim($_POST['resim_url'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : (int)$hizmet['sira'];

    if (isset($_FILES['resim_dosya']) && $_FILES['resim_dosya']['error'] === UPLOAD_ERR_OK) {
        $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $uzanti = strtolower(pathinfo($_FILES['resim_dosya']['name'], PATHINFO_EXTENSION));
        if (in_array($uzanti, $izinliUzantilar)) {
            $yeniAd = 'hizmet_' . uniqid() . '.' . $uzanti;
            if (move_uploaded_file($_FILES['resim_dosya']['tmp_name'], '../uploads/' . $yeniAd)) {
                $resimUrl = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($baslik === '' || $detay === '') {
        $hataMesaji = 'Lütfen başlık ve detay alanlarını doldurun.';
    } else {
        $guncelle = $pdo->prepare(
            "UPDATE hizmet_kartlari
             SET ikon = :ikon, resim_url = :resim_url, baslik = :baslik, ozet = :ozet, detay = :detay, link = :link, sira = :sira
             WHERE id = :id"
        );
        $guncelle->execute([
            'ikon' => $ikon,
            'resim_url' => $resimUrl,
            'baslik' => $baslik,
            'ozet' => $ozet,
            'detay' => $detay,
            'link' => $link,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Hizmet Kartı Düzenledi', $baslik);
        header('Location: hizmet-karti-yonet.php?basarili=1');
        exit;
    }
    $hizmet = array_merge($hizmet, $_POST);
}

$sayfaBasligi = 'Hizmet Düzenle';
$aktifMenu = 'sabit-hizmetler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Hizmet Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="hizmet-karti-duzenle.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($hizmet['baslik']); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">İkon</label>
                <input type="text" name="ikon" class="form-control" placeholder="bi-grid-fill"
                       value="<?php echo htmlspecialchars($hizmet['ikon']); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)$hizmet['sira']; ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Özet</label>
                <input type="text" name="ozet" class="form-control"
                       value="<?php echo htmlspecialchars($hizmet['ozet']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Detay *</label>
                <textarea name="detay" rows="4" class="form-control" required><?php echo htmlspecialchars($hizmet['detay']); ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Bağlantı</label>
                <input type="text" name="link" class="form-control"
                       value="<?php echo htmlspecialchars($hizmet['link']); ?>">
            </div>
            <div class="col-12">
                <img src="../<?php echo htmlspecialchars($hizmet['resim_url']); ?>" class="mb-2 rounded-3" style="max-height:120px;"
                     onerror="this.style.display='none';">
                <label class="form-label d-block">Bilgisayardan Yeni Görsel Yükle (seçilirse mevcut görselin yerine geçer)</label>
                <input type="file" name="resim_dosya" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Görsel URL</label>
                <input type="text" name="resim_url" class="form-control"
                       value="<?php echo htmlspecialchars($hizmet['resim_url']); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
