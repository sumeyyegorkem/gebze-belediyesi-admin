<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$kategoriEtiket = ['kultur' => 'Kültür Yayınları', 'projeler' => 'Gebze Belediyesi Projeleri', 'manset' => 'Gebze Manşet'];

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $tarih = trim($_POST['tarih'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'kultur');
    $pdfUrl = trim($_POST['pdf_url'] ?? '');

    if ($baslik === '') {
        $hataMesaji = 'Lütfen başlık alanını doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO yayinlar (baslik, tarih, kategori, pdf_url)
             VALUES (:baslik, :tarih, :kategori, :pdf_url)"
        );
        $stmt->execute([
            'baslik' => $baslik,
            'tarih' => $tarih,
            'kategori' => $kategori,
            'pdf_url' => $pdfUrl,
        ]);

        islemKaydet($pdo, 'Yayın Ekledi', $baslik);
        header('Location: yayinlar-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Yayın Ekle';
$aktifMenu = 'yayinlar';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Yayın Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="yayinlar-ekle.php">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <?php foreach ($kategoriEtiket as $val => $etiket): ?>
                        <option value="<?php echo $val; ?>"><?php echo $etiket; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tarih</label>
                <input type="text" name="tarih" class="form-control" placeholder="2 Ocak 2026"
                       value="<?php echo htmlspecialchars($_POST['tarih'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">PDF Bağlantısı</label>
                <input type="text" name="pdf_url" class="form-control" placeholder="https://..."
                       value="<?php echo htmlspecialchars($_POST['pdf_url'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>

