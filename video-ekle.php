<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $youtubeGiris = trim($_POST['youtube_id'] ?? '');
    $tarih = trim($_POST['tarih'] ?? '') ?: date('Y-m-d');

    // Kullanıcı tam YouTube linki de yapıştırmış olabilir, sadece video ID'sini çıkarıyoruz
    $youtubeId = $youtubeGiris;
    if (preg_match('/(?:youtu\.be\/|v=|\/embed\/)([A-Za-z0-9_-]{6,20})/', $youtubeGiris, $eslesme)) {
        $youtubeId = $eslesme[1];
    }

    if ($baslik === '' || $youtubeId === '') {
        $hataMesaji = 'Lütfen başlık ve YouTube video ID/linkini girin.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO videolar (baslik, youtube_id, tarih) VALUES (:baslik, :youtube_id, :tarih)");
        $stmt->execute(['baslik' => $baslik, 'youtube_id' => $youtubeId, 'tarih' => $tarih]);

        islemKaydet($pdo, 'Video Ekledi', $baslik);
        header('Location: panel.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Video Ekle';
$aktifMenu = 'videolar';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-play-circle-fill me-2"></i>Yeni Video Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="video-ekle.php">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Video Başlığı *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tarih</label>
                <input type="date" name="tarih" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['tarih'] ?? date('Y-m-d')); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">YouTube Video Linki ya da ID *</label>
                <input type="text" name="youtube_id" class="form-control" required
                       placeholder="https://www.youtube.com/watch?v=xxxxxxxxxxx ya da direkt xxxxxxxxxxx"
                       value="<?php echo htmlspecialchars($_POST['youtube_id'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
