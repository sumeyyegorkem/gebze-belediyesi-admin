<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

function slugOlustur($metin) {
    $harfler = ['ç'=>'c','Ç'=>'c','ğ'=>'g','Ğ'=>'g','ı'=>'i','İ'=>'i','ö'=>'o','Ö'=>'o','ş'=>'s','Ş'=>'s','ü'=>'u','Ü'=>'u'];
    $metin = strtr($metin, $harfler);
    $metin = mb_strtolower($metin, 'UTF-8');
    $metin = preg_replace('/[^a-z0-9]+/', '-', $metin);
    return trim($metin, '-');
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $foto = trim($_POST['foto'] ?? '');
    $sira = (int)($_POST['sira'] ?? 0);
    $slug = slugOlustur($ad);

    if ($ad === '') {
        $hataMesaji = 'Lütfen ad soyad alanını doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO baskan_yardimcilari (ad, foto, slug, sira)
             VALUES (:ad, :foto, :slug, :sira)"
        );
        $stmt->execute([
            'ad' => $ad,
            'foto' => $foto,
            'slug' => $slug,
            'sira' => $sira,
        ]);

        islemKaydet($pdo, 'Başkan Yardımcısı Ekledi', $ad);
        header('Location: baskan-yardimcilari-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Başkan Yardımcısı Ekle';
$aktifMenu = 'baskan-yardimcilari';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Başkan Yardımcısı Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="baskan-yardimcilari-ekle.php">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Ad Soyad *</label>
                <input type="text" name="ad" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ad'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo htmlspecialchars($_POST['sira'] ?? '0'); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Fotoğraf URL</label>
                <input type="text" name="foto" class="form-control" placeholder="https://..."
                       value="<?php echo htmlspecialchars($_POST['foto'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>

