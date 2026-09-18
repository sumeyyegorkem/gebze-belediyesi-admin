<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$kategoriEtiket = ['kultur' => 'Kültür Yayınları', 'projeler' => 'Gebze Belediyesi Projeleri', 'manset' => 'Gebze Manşet'];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM yayinlar WHERE id = :id");
$stmt->execute(['id' => $id]);
$yayin = $stmt->fetch();

if (!$yayin) {
    header('Location: yayinlar-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $tarih = trim($_POST['tarih'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'kultur');
    $pdfUrl = trim($_POST['pdf_url'] ?? '');

    if ($baslik === '') {
        $hataMesaji = 'Lütfen başlık alanını doldurun.';
    } else {
        $guncelle = $pdo->prepare(
            "UPDATE yayinlar
             SET baslik = :baslik, tarih = :tarih, kategori = :kategori, pdf_url = :pdf_url
             WHERE id = :id"
        );
        $guncelle->execute([
            'baslik' => $baslik,
            'tarih' => $tarih,
            'kategori' => $kategori,
            'pdf_url' => $pdfUrl,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Yayın Düzenledi', $baslik);
        header('Location: yayinlar-yonet.php?basarili=1');
        exit;
    }
    $yayin = array_merge($yayin, $_POST);
}

$sayfaBasligi = 'Yayın Düzenle';
$aktifMenu = 'yayinlar';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Yayın Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="yayinlar-duzenle.php?id=<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($yayin['baslik']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <?php foreach ($kategoriEtiket as $val => $etiket): ?>
                        <option value="<?php echo $val; ?>" <?php echo ($yayin['kategori'] === $val) ? 'selected' : ''; ?>><?php echo $etiket; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tarih</label>
                <input type="text" name="tarih" class="form-control" placeholder="2 Ocak 2026"
                       value="<?php echo htmlspecialchars($yayin['tarih']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">PDF Bağlantısı</label>
                <input type="text" name="pdf_url" class="form-control" placeholder="https://..."
                       value="<?php echo htmlspecialchars($yayin['pdf_url']); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>

