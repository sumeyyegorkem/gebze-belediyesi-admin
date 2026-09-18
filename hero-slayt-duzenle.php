<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM hero_slaytlari WHERE id = :id");
$stmt->execute(['id' => $id]);
$slayt = $stmt->fetch();

if (!$slayt) {
    header('Location: hero-slayt-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslikOn = trim($_POST['baslik_on'] ?? '');
    $baslikVurgu = trim($_POST['baslik_vurgu'] ?? '');
    $baslikSon = trim($_POST['baslik_son'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    $resimUrl = trim($_POST['resim_url'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : (int)$slayt['sira'];

    if (isset($_FILES['resim_dosya']) && $_FILES['resim_dosya']['error'] === UPLOAD_ERR_OK) {
        $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $uzanti = strtolower(pathinfo($_FILES['resim_dosya']['name'], PATHINFO_EXTENSION));
        if (in_array($uzanti, $izinliUzantilar)) {
            $yeniAd = 'hero_' . uniqid() . '.' . $uzanti;
            if (move_uploaded_file($_FILES['resim_dosya']['tmp_name'], '../uploads/' . $yeniAd)) {
                $resimUrl = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($baslikOn === '' && $baslikVurgu === '') {
        $hataMesaji = 'Lütfen en azından başlık metnini girin.';
    } elseif ($resimUrl === '') {
        $hataMesaji = 'Lütfen bir görsel yükleyin ya da görsel URL girin.';
    } else {
        $guncelle = $pdo->prepare(
            "UPDATE hero_slaytlari
             SET resim_url = :resim_url, baslik_on = :baslik_on, baslik_vurgu = :baslik_vurgu, baslik_son = :baslik_son, aciklama = :aciklama, sira = :sira
             WHERE id = :id"
        );
        $guncelle->execute([
            'resim_url' => $resimUrl,
            'baslik_on' => $baslikOn,
            'baslik_vurgu' => $baslikVurgu,
            'baslik_son' => $baslikSon,
            'aciklama' => $aciklama,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Hero Slayt Düzenledi', $baslikOn);
        header('Location: hero-slayt-yonet.php?basarili=1');
        exit;
    }
    $slayt = array_merge($slayt, $_POST);
}

$sayfaBasligi = 'Hero Slaytı Düzenle';
$aktifMenu = 'hero-slaytlari';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Hero Slaytı Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="hero-slayt-duzenle.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Başlık - Vurgudan Önceki Kısım</label>
                <input type="text" name="baslik_on" class="form-control"
                       value="<?php echo htmlspecialchars($slayt['baslik_on']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Başlık - Vurgulu Kelime <span class="text-muted small">(renkli gösterilir)</span></label>
                <input type="text" name="baslik_vurgu" class="form-control"
                       value="<?php echo htmlspecialchars($slayt['baslik_vurgu']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Başlık - Vurgudan Sonraki Kısım</label>
                <input type="text" name="baslik_son" class="form-control"
                       value="<?php echo htmlspecialchars($slayt['baslik_son']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Açıklama <span class="text-muted small">(opsiyonel)</span></label>
                <textarea name="aciklama" rows="2" class="form-control"><?php echo htmlspecialchars($slayt['aciklama'] ?? ''); ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)$slayt['sira']; ?>">
            </div>
            <div class="col-12">
                <img src="../<?php echo htmlspecialchars($slayt['resim_url']); ?>" class="mb-2 rounded-3" style="max-height:120px;"
                     onerror="this.style.display='none';">
                <label class="form-label d-block">Bilgisayardan Yeni Görsel Yükle (seçilirse mevcut görselin yerine geçer)</label>
                <input type="file" name="resim_dosya" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Görsel URL</label>
                <input type="text" name="resim_url" class="form-control"
                       value="<?php echo htmlspecialchars($slayt['resim_url']); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
            <a href="hero-slayt-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
