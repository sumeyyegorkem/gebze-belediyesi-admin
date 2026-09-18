<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM iletisim_mesajlari WHERE id = :id");
$stmt->execute(['id' => $id]);
$mesaj = $stmt->fetch();

if (!$mesaj) {
    header('Location: mesajlar-yonet.php');
    exit;
}

// Görüntülenince okundu olarak işaretle
if (!$mesaj['okundu']) {
    $guncelle = $pdo->prepare("UPDATE iletisim_mesajlari SET okundu = 1 WHERE id = :id");
    $guncelle->execute(['id' => $id]);
    $mesaj['okundu'] = 1;
}

$sayfaBasligi = 'Mesaj Detayı';
$aktifMenu = 'mesajlar';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-envelope-open-fill me-2"></i>Mesaj Detayı</h4>

    <dl class="row">
        <dt class="col-sm-3">Ad Soyad</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($mesaj['ad_soyad']); ?></dd>

        <dt class="col-sm-3">E-posta</dt>
        <dd class="col-sm-9"><a href="mailto:<?php echo htmlspecialchars($mesaj['eposta']); ?>"><?php echo htmlspecialchars($mesaj['eposta']); ?></a></dd>

        <dt class="col-sm-3">Telefon</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($mesaj['telefon'] ?: '-'); ?></dd>

        <dt class="col-sm-3">Konu</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($mesaj['konu']); ?></dd>

        <dt class="col-sm-3">Tarih</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($mesaj['gonderim_tarihi']); ?></dd>

        <dt class="col-sm-3">Mesaj</dt>
        <dd class="col-sm-9" style="white-space: pre-wrap;"><?php echo htmlspecialchars($mesaj['mesaj']); ?></dd>
    </dl>

    <a href="mesaj-sil.php?id=<?php echo $mesaj['id']; ?>" class="btn btn-outline-danger"
       onclick="return confirm('Bu mesajı silmek istediğinize emin misiniz?');">
        <i class="bi bi-trash-fill"></i> Mesajı Sil
    </a>
</div>

<?php include 'includes/admin-bitis.php'; ?>
