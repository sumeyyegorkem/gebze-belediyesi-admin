<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM videolar WHERE id = :id");
$stmt->execute(['id' => $id]);
$video = $stmt->fetch();

if (!$video) {
    header('Location: panel.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $youtubeGiris = trim($_POST['youtube_id'] ?? '');
    $tarih = trim($_POST['tarih'] ?? '') ?: date('Y-m-d');

    $youtubeId = $youtubeGiris;
    if (preg_match('/(?:youtu\.be\/|v=|\/embed\/)([A-Za-z0-9_-]{6,20})/', $youtubeGiris, $eslesme)) {
        $youtubeId = $eslesme[1];
    }

    if ($baslik === '' || $youtubeId === '') {
        $hataMesaji = 'Lütfen başlık ve YouTube video ID/linkini girin.';
    } else {
        $guncelle = $pdo->prepare("UPDATE videolar SET baslik = :baslik, youtube_id = :youtube_id, tarih = :tarih WHERE id = :id");
        $guncelle->execute(['baslik' => $baslik, 'youtube_id' => $youtubeId, 'tarih' => $tarih, 'id' => $id]);

        islemKaydet($pdo, 'Video Düzenledi', $baslik);
        header('Location: panel.php?basarili=1');
        exit;
    }
    $video = array_merge($video, $_POST);
}

$sayfaBasligi = 'Video Düzenle';
$aktifMenu = 'videolar';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Video Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($video['youtube_id']); ?>/hqdefault.jpg"
         class="mb-3 rounded-3" style="max-height:160px;">

    <form method="POST" action="video-duzenle.php?id=<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Video Başlığı *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($video['baslik']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tarih</label>
                <input type="date" name="tarih" class="form-control"
                       value="<?php echo htmlspecialchars($video['tarih']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">YouTube Video Linki ya da ID *</label>
                <input type="text" name="youtube_id" class="form-control" required
                       value="<?php echo htmlspecialchars($video['youtube_id']); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
