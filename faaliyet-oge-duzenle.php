<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM faaliyet_ogeleri WHERE id = :id");
$stmt->execute(['id' => $id]);
$oge = $stmt->fetch();

if (!$oge) {
    header('Location: faaliyet-alanlari-yonet.php');
    exit;
}

$kategoriler = $pdo->query("SELECT * FROM faaliyet_kategorileri ORDER BY sira ASC, id ASC")->fetchAll();

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategoriId = (int)($_POST['kategori_id'] ?? 0);
    $baslik = trim($_POST['baslik'] ?? '');
    $href = trim($_POST['href'] ?? '');
    $img = trim($_POST['img'] ?? '');
    $detay = trim($_POST['detay'] ?? '');
    $icBaglanti = isset($_POST['ic_baglanti']) ? 1 : 0;
    $sira = (int)($_POST['sira'] ?? 0);

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
        $stmt = $pdo->prepare(
            "UPDATE faaliyet_ogeleri
             SET kategori_id = :kategori_id, baslik = :baslik, href = :href, img = :img, detay = :detay, ic_baglanti = :ic, sira = :sira
             WHERE id = :id"
        );
        $stmt->execute([
            'kategori_id' => $kategoriId,
            'baslik' => $baslik,
            'href' => $href,
            'img' => $img,
            'detay' => $detay !== '' ? $detay : null,
            'ic' => $icBaglanti,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Faaliyet Öğesi Düzenledi', $baslik);
        header('Location: faaliyet-alanlari-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Faaliyet Ögesi Düzenle';
$aktifMenu = 'faaliyet-alanlari';
include 'includes/admin-baslangic.php';

$gosterilecekGorsel = $_POST['img'] ?? $oge['img'];
$gorselYolu = (strpos($gosterilecekGorsel, 'http://') === 0 || strpos($gosterilecekGorsel, 'https://') === 0)
    ? $gosterilecekGorsel
    : '../' . $gosterilecekGorsel;
$icChecked = $_SERVER['REQUEST_METHOD'] === 'POST' ? isset($_POST['ic_baglanti']) : ((int)$oge['ic_baglanti'] === 1);
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-fill me-2"></i>Faaliyet Ögesi Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="faaliyet-oge-duzenle.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Kategori *</label>
                <select name="kategori_id" class="form-select" required>
                    <?php foreach ($kategoriler as $k): ?>
                        <option value="<?php echo $k['id']; ?>" <?php echo ((int)($_POST['kategori_id'] ?? $oge['kategori_id']) === (int)$k['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($k['baslik']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['sira'] ?? $oge['sira']); ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check">
                    <input type="checkbox" name="ic_baglanti" id="icBaglanti" class="form-check-input" value="1"
                           <?php echo $icChecked ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="icBaglanti">Site-içi bağlantı</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? $oge['baslik']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bağlantı (href)</label>
                <input type="text" name="href" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['href'] ?? $oge['href']); ?>">
            </div>
            <?php if (!empty($oge['img'])): ?>
            <div class="col-12">
                <label class="form-label d-block">Mevcut Fotoğraf</label>
                <img src="<?php echo htmlspecialchars($gorselYolu); ?>" class="admin-thumb" style="width:120px;height:80px;" onerror="this.style.display='none';">
            </div>
            <?php endif; ?>
            <div class="col-12">
                <label class="form-label">Bilgisayardan Fotoğraf Yükle (değiştirmek için)</label>
                <input type="file" name="gorsel" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
                <label class="form-label">ya da Fotoğraf URL</label>
                <input type="text" name="img" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['img'] ?? $oge['img']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Detay Metni</label>
                <textarea name="detay" rows="8" class="form-control"><?php echo htmlspecialchars($_POST['detay'] ?? $oge['detay']); ?></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="faaliyet-alanlari-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
