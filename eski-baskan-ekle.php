<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';

$sonraSira = (int)$pdo->query("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM eski_baskanlar")->fetch()['sonraki'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donemGrubu = trim($_POST['donem_grubu'] ?? '');
    $yilAraligi = trim($_POST['yil_araligi'] ?? '');
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $fotoUrl = trim($_POST['foto_url'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : $sonraSira;

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

    if ($fotoUrl === '') {
        $fotoUrl = 'https://placehold.co/300x190?text=Foto%C4%9Fraf';
    }

    if ($donemGrubu === '' || $yilAraligi === '' || $adSoyad === '') {
        $hataMesaji = 'Lütfen dönem grubu, yıl aralığı ve ad soyad alanlarını doldurun.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO eski_baskanlar (donem_grubu, yil_araligi, ad_soyad, foto_url, sira) VALUES (:grup, :yil, :ad, :foto, :sira)");
        $stmt->execute([
            'grup' => $donemGrubu,
            'yil' => $yilAraligi,
            'ad' => $adSoyad,
            'foto' => $fotoUrl,
            'sira' => $sira,
        ]);

        islemKaydet($pdo, 'Eski Başkan Ekledi', $adSoyad);
        header('Location: eski-baskan-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Başkan Ekle';
$aktifMenu = 'kurumsal-eski-baskanlar';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Başkan Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="eski-baskan-ekle.php" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Dönem Grubu * <span class="text-muted small">(örn: 1980'ler)</span></label>
                <input type="text" name="donem_grubu" class="form-control" required placeholder="1980'ler"
                       value="<?php echo htmlspecialchars($_POST['donem_grubu'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Yıl Aralığı * <span class="text-muted small">(örn: 1984-1987)</span></label>
                <input type="text" name="yil_araligi" class="form-control" required placeholder="1984-1987"
                       value="<?php echo htmlspecialchars($_POST['yil_araligi'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)($_POST['sira'] ?? $sonraSira); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Ad Soyad *</label>
                <input type="text" name="ad_soyad" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ad_soyad'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bilgisayardan Görsel Yükle (varsa URL'nin önüne geçer)</label>
                <input type="file" name="foto_dosya" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Görsel URL (ikisi de boşsa otomatik görsel kullanılır)</label>
                <input type="text" name="foto_url" class="form-control" placeholder="https://..."
                       value="<?php echo htmlspecialchars($_POST['foto_url'] ?? ''); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
