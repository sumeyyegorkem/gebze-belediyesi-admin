<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$kategoriler = $pdo->query("SELECT * FROM faaliyet_kategorileri ORDER BY sira ASC, id ASC")->fetchAll();
$seciliKategoriId = (int)($_GET['kategori_id'] ?? 0);

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategoriId = (int)($_POST['kategori_id'] ?? 0);
    $baslik = trim($_POST['baslik'] ?? '');
    $href = trim($_POST['href'] ?? '');
    $img = trim($_POST['img'] ?? '');
    $detay = trim($_POST['detay'] ?? '');
    $icBaglanti = isset($_POST['ic_baglanti']) ? 1 : 0;

    if (isset($_FILES['gorsel']) && $_FILES['gorsel']['error'] === UPLOAD_ERR_OK) {
        $uzanti = strtolower(pathinfo($_FILES['gorsel']['name'], PATHINFO_EXTENSION));
        $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($uzanti, $izinliUzantilar, true)) {
            $yeniAd = 'faaliyet_' . uniqid() . '.' . $uzanti;
            if (move_uploaded_file($_FILES['gorsel']['tmp_name'], '../uploads/' . $yeniAd)) {
                $img = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($baslik === '' || $kategoriId === 0) {
        $hataMesaji = 'Lütfen kategori seçin ve başlığı doldurun.';
    } else {
        $sonraSiraStmt = $pdo->prepare("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM faaliyet_ogeleri WHERE kategori_id = :kategori_id");
        $sonraSiraStmt->execute(['kategori_id' => $kategoriId]);
        $sonraSira = (int)$sonraSiraStmt->fetch()['sonraki'];

        $stmt = $pdo->prepare(
            "INSERT INTO faaliyet_ogeleri (kategori_id, baslik, href, img, detay, ic_baglanti, sira)
             VALUES (:kategori_id, :baslik, :href, :img, :detay, :ic, :sira)"
        );
        $stmt->execute([
            'kategori_id' => $kategoriId,
            'baslik' => $baslik,
            'href' => $href,
            'img' => $img,
            'detay' => $detay !== '' ? $detay : null,
            'ic' => $icBaglanti,
            'sira' => $sonraSira,
        ]);

        islemKaydet($pdo, 'Faaliyet Öğesi Ekledi', $baslik);
        header('Location: faaliyet-alanlari-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Faaliyet Ögesi Ekle';
$aktifMenu = 'faaliyet-alanlari';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Faaliyet Ögesi Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <?php if (count($kategoriler) === 0): ?>
        <div class="alert alert-warning">Önce en az bir kategori eklemelisiniz.</div>
    <?php else: ?>
    <form method="POST" action="faaliyet-oge-ekle.php" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Kategori *</label>
                <select name="kategori_id" class="form-select" required>
                    <option value="">Seçiniz</option>
                    <?php foreach ($kategoriler as $k): ?>
                        <option value="<?php echo $k['id']; ?>" <?php echo ((int)($_POST['kategori_id'] ?? $seciliKategoriId) === (int)$k['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($k['baslik']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <div class="form-check">
                    <input type="checkbox" name="ic_baglanti" id="icBaglanti" class="form-check-input" value="1" <?php echo isset($_POST['ic_baglanti']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="icBaglanti">Bu bir site-içi bağlantıdır (örn: hizmetler.php?... ya da hizmetler/dosya-adi.php)</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bağlantı (href)</label>
                <input type="text" name="href" class="form-control" placeholder="https://... veya hizmetler.php?hizmet=..." title="Not: sistem 'hizmetler.php' ile başlayan site-içi bağlantıları otomatik olarak hizmetler/ klasörüne yönlendirir."
                       value="<?php echo htmlspecialchars($_POST['href'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bilgisayardan Fotoğraf Yükle (varsa URL'nin önüne geçer)</label>
                <input type="file" name="gorsel" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Fotoğraf URL</label>
                <input type="text" name="img" class="form-control" placeholder="https://..."
                       value="<?php echo htmlspecialchars($_POST['img'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Detay Metni</label>
                <textarea name="detay" rows="8" class="form-control"><?php echo htmlspecialchars($_POST['detay'] ?? ''); ?></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="faaliyet-alanlari-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php include 'includes/admin-bitis.php'; ?>
