<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM kurumsal_raporlar WHERE id = :id");
$stmt->execute(['id' => $id]);
$rapor = $stmt->fetch();

if (!$rapor) {
    header('Location: kurumsal-rapor-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $yayinTarihi = trim($_POST['yayin_tarihi'] ?? '');
    $pdfUrl = trim($_POST['pdf_url'] ?? '');
    $sira = isset($_POST['sira']) ? (int)$_POST['sira'] : (int)$rapor['sira'];

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
        $guncelle = $pdo->prepare(
            "UPDATE kurumsal_raporlar
             SET baslik = :baslik, yayin_tarihi = :tarih, pdf_url = :pdf, sira = :sira
             WHERE id = :id"
        );
        $guncelle->execute([
            'baslik' => $baslik,
            'tarih' => $yayinTarihi,
            'pdf' => $pdfUrl,
            'sira' => $sira,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Kurumsal Rapor Düzenledi', $baslik);
        header('Location: kurumsal-rapor-yonet.php?basarili=1');
        exit;
    }
    $rapor = array_merge($rapor, $_POST);
}

$sayfaBasligi = 'Rapor Düzenle';
$aktifMenu = 'kurumsal-raporlar';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Kurumsal Rapor Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="kurumsal-rapor-duzenle.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($rapor['baslik']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)$rapor['sira']; ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Yayın Tarihi</label>
                <input type="text" name="yayin_tarihi" class="form-control" placeholder="gg.aa.yyyy"
                       value="<?php echo htmlspecialchars($rapor['yayin_tarihi']); ?>">
            </div>
            <div class="col-12">
                <?php if ($rapor['pdf_url']): ?>
                    <p class="small">Mevcut dosya: <a href="<?php echo htmlspecialchars($rapor['pdf_url']); ?>" target="_blank"><?php echo htmlspecialchars($rapor['pdf_url']); ?></a></p>
                <?php endif; ?>
                <label class="form-label">Bilgisayardan Yeni PDF Yükle (seçilirse mevcudun yerine geçer)</label>
                <input type="file" name="pdf_dosya" class="form-control" accept="application/pdf">
            </div>
            <div class="col-12">
                <label class="form-label">ya da PDF URL</label>
                <input type="text" name="pdf_url" class="form-control"
                       value="<?php echo htmlspecialchars($rapor['pdf_url']); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
