<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM meclis_kararlari WHERE id = :id");
$stmt->execute(['id' => $id]);
$karar = $stmt->fetch();

if (!$karar) {
    header('Location: meclis-karari-yonet.php');
    exit;
}

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
        $guncelle = $pdo->prepare(
            "UPDATE meclis_kararlari
             SET baslik = :baslik, aciklama = :aciklama, yil = :yil, ay = :ay, pdf_url = :pdf
             WHERE id = :id"
        );
        $guncelle->execute([
            'baslik' => $baslik,
            'aciklama' => $aciklama,
            'yil' => $yil,
            'ay' => $ay,
            'pdf' => $pdfUrl,
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Meclis Kararı Düzenledi', $baslik);
        header('Location: meclis-karari-yonet.php?basarili=1');
        exit;
    }
    $karar = array_merge($karar, $_POST);
}

$sayfaBasligi = 'Karar Düzenle';
$aktifMenu = 'kurumsal-meclis-kararlari';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Meclis Kararı Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="meclis-karari-duzenle.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($karar['baslik']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Açıklama</label>
                <input type="text" name="aciklama" class="form-control"
                       value="<?php echo htmlspecialchars($karar['aciklama']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Yıl *</label>
                <input type="number" name="yil" class="form-control" required min="1900" max="2100"
                       value="<?php echo htmlspecialchars($karar['yil']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Ay *</label>
                <select name="ay" class="form-select" required>
                    <?php foreach ($ayAdlari as $sayi => $ad): ?>
                        <option value="<?php echo $sayi; ?>" <?php echo ($karar['ay'] === $sayi) ? 'selected' : ''; ?>><?php echo $ad; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <?php if ($karar['pdf_url']): ?>
                    <p class="small">Mevcut dosya: <a href="<?php echo htmlspecialchars($karar['pdf_url']); ?>" target="_blank"><?php echo htmlspecialchars($karar['pdf_url']); ?></a></p>
                <?php endif; ?>
                <label class="form-label">Bilgisayardan Yeni PDF Yükle (seçilirse mevcudun yerine geçer)</label>
                <input type="file" name="pdf_dosya" class="form-control" accept="application/pdf">
            </div>
            <div class="col-12">
                <label class="form-label">ya da PDF URL</label>
                <input type="text" name="pdf_url" class="form-control"
                       value="<?php echo htmlspecialchars($karar['pdf_url']); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
