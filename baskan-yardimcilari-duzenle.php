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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM baskan_yardimcilari WHERE id = :id");
$stmt->execute(['id' => $id]);
$yardimci = $stmt->fetch();

if (!$yardimci) {
    header('Location: baskan-yardimcilari-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $foto = trim($_POST['foto'] ?? '');
    $sira = (int)($_POST['sira'] ?? 0);
    $slugDegismesin = ($ad === $yardimci['ad']);
    $slug = $slugDegismesin ? $yardimci['slug'] : slugOlustur($ad);

    if ($ad === '') {
        $hataMesaji = 'Lütfen ad soyad alanını doldurun.';
    } else {
        $guncelle = $pdo->prepare(
            "UPDATE baskan_yardimcilari
             SET ad = :ad, foto = :foto, slug = :slug, sira = :sira
             WHERE id = :id"
        );
        $guncelle->execute([
            'ad' => $ad,
            'foto' => $foto,
            'slug' => $slug,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Başkan Yardımcısı Düzenledi', $ad);
        header('Location: baskan-yardimcilari-yonet.php?basarili=1');
        exit;
    }
    $yardimci = array_merge($yardimci, $_POST);
}

$sayfaBasligi = 'Başkan Yardımcısı Düzenle';
$aktifMenu = 'baskan-yardimcilari';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Başkan Yardımcısı Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="baskan-yardimcilari-duzenle.php?id=<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Ad Soyad *</label>
                <input type="text" name="ad" class="form-control" required
                       value="<?php echo htmlspecialchars($yardimci['ad']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)$yardimci['sira']; ?>">
            </div>
            <div class="col-12">
                <img src="<?php echo htmlspecialchars($yardimci['foto']); ?>" class="mb-2 rounded-circle" style="width:80px;height:80px;object-fit:cover;" onerror="this.style.display='none';">
                <label class="form-label d-block">Fotoğraf URL</label>
                <input type="text" name="foto" class="form-control" placeholder="https://..."
                       value="<?php echo htmlspecialchars($yardimci['foto']); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>

