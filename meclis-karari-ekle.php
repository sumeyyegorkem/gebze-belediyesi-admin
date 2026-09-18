<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';
$ayAdlari = ['01' => 'Ocak', '02' => 'Şubat', '03' => 'Mart', '04' => 'Nisan', '05' => 'Mayıs', '06' => 'Haziran',
             '07' => 'Temmuz', '08' => 'Ağustos', '09' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    $yil = trim($_POST['yil'] ?? '');
    $ay = trim($_POST['ay'] ?? '');
    $pdfUrl = trim($_POST['pdf_url'] ?? '');

    if (isset($_FILES['pdf_dosya']) && $_FILES['pdf_dosya']['error'] === UPLOAD_ERR_OK) {
        $uzanti = strtolower(pathinfo($_FILES['pdf_dosya']['name'], PATHINFO_EXTENSION));
        if ($uzanti === 'pdf') {
            $yeniAd = 'karar_' . uniqid() . '.pdf';
            if (move_uploaded_file($_FILES['pdf_dosya']['tmp_name'], '../uploads/' . $yeniAd)) {
                $pdfUrl = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($baslik === '' || $yil === '' || $ay === '') {
        $hataMesaji = 'Lütfen başlık, yıl ve ay alanlarını doldurun.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO meclis_kararlari (baslik, aciklama, yil, ay, pdf_url) VALUES (:baslik, :aciklama, :yil, :ay, :pdf)");
        $stmt->execute([
            'baslik' => $baslik,
            'aciklama' => $aciklama,
            'yil' => $yil,
            'ay' => $ay,
            'pdf' => $pdfUrl,
        ]);

        islemKaydet($pdo, 'Meclis Kararı Ekledi', $baslik);
        header('Location: meclis-karari-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Karar Ekle';
$aktifMenu = 'kurumsal-meclis-kararlari';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Meclis Kararı Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="meclis-karari-ekle.php" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Başlık * <span class="text-muted small">(örn: Meclis Toplantısı: 4 Ağustos 2026)</span></label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Açıklama <span class="text-muted small">(örn: Karar Yayın Tarihi: 10 Ağustos 2026)</span></label>
                <input type="text" name="aciklama" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['aciklama'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Yıl *</label>
                <input type="number" name="yil" class="form-control" required min="1900" max="2100" placeholder="2026"
                       value="<?php echo htmlspecialchars($_POST['yil'] ?? date('Y')); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Ay *</label>
                <select name="ay" class="form-select" required>
                    <option value="">Seçiniz</option>
                    <?php foreach ($ayAdlari as $sayi => $ad): ?>
                        <option value="<?php echo $sayi; ?>" <?php echo (($_POST['ay'] ?? '') === $sayi) ? 'selected' : ''; ?>><?php echo $ad; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Bilgisayardan PDF Yükle (varsa URL'nin önüne geçer)</label>
                <input type="file" name="pdf_dosya" class="form-control" accept="application/pdf">
            </div>
            <div class="col-12">
                <label class="form-label">ya da PDF URL</label>
                <input type="text" name="pdf_url" class="form-control" placeholder="https://..."
                       value="<?php echo htmlspecialchars($_POST['pdf_url'] ?? ''); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
