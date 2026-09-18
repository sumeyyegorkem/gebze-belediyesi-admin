<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';

$sonraSira = (int)$pdo->query("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM kurumsal_raporlar")->fetch()['sonraki'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $yayinTarihi = trim($_POST['yayin_tarihi'] ?? '');
    $pdfUrl = trim($_POST['pdf_url'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : $sonraSira;

    if (isset($_FILES['pdf_dosya']) && $_FILES['pdf_dosya']['error'] === UPLOAD_ERR_OK) {
        $uzanti = strtolower(pathinfo($_FILES['pdf_dosya']['name'], PATHINFO_EXTENSION));
        if ($uzanti === 'pdf') {
            $yeniAd = 'rapor_' . uniqid() . '.pdf';
            if (move_uploaded_file($_FILES['pdf_dosya']['tmp_name'], '../uploads/' . $yeniAd)) {
                $pdfUrl = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($baslik === '') {
        $hataMesaji = 'Lütfen başlık alanını doldurun.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO kurumsal_raporlar (baslik, yayin_tarihi, pdf_url, sira) VALUES (:baslik, :tarih, :pdf, :sira)");
        $stmt->execute([
            'baslik' => $baslik,
            'tarih' => $yayinTarihi,
            'pdf' => $pdfUrl,
            'sira' => $sira,
        ]);

        islemKaydet($pdo, 'Kurumsal Rapor Ekledi', $baslik);
        header('Location: kurumsal-rapor-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Rapor Ekle';
$aktifMenu = 'kurumsal-raporlar';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Kurumsal Rapor Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="kurumsal-rapor-ekle.php" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Başlık * <span class="text-muted small">(örn: 2025 Mali Yılı İdare Faaliyet Raporu)</span></label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)($_POST['sira'] ?? $sonraSira); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Yayın Tarihi <span class="text-muted small">(örn: 30.04.2026)</span></label>
                <input type="text" name="yayin_tarihi" class="form-control" placeholder="gg.aa.yyyy"
                       value="<?php echo htmlspecialchars($_POST['yayin_tarihi'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bilgisayardan PDF Yükle (varsa URL'nin önüne geçer)</label>
                <input type="file" name="pdf_dosya" class="form-control" accept="application/pdf">
            </div>
            <div class="col-12">
                <label class="form-label">ya da PDF URL <span class="text-muted small">(boş bırakılırsa "Bağlantı paylaşılmadı" gösterilir)</span></label>
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
