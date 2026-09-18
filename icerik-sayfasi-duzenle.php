<?php
/**
 * admin/icerik-sayfasi-duzenle.php
 * -------------------------------------------------------
 * Tarihçe ve Bugünkü Gebze gibi, veritabanında tek bir "içerik"
 * bloğu olarak tutulan sabit sayfaların düzenleme ekranı.
 * "sayfa" GET parametresi sabit bir beyaz listeden gelir.
 * -------------------------------------------------------
 */
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$izinliSayfalar = [
    'tarihce'       => ['baslik' => 'Tarihçe',        'menu' => 'sabit-tarihce',        'ornek' => '../gebze/gebze.php'],
    'bugunku-gebze' => ['baslik' => 'Bugünkü Gebze',  'menu' => 'sabit-bugunku-gebze',   'ornek' => '../gebze/bugunku-gebze.php'],
    'hakkimizda'    => ['baslik' => 'Özgeçmiş',       'menu' => 'sabit-hakkimizda',      'ornek' => '../genel/hakkimizda.php'],
];

$sayfa = $_GET['sayfa'] ?? $_POST['sayfa'] ?? '';
if (!isset($izinliSayfalar[$sayfa])) {
    $sayfa = 'tarihce';
}
$ayar = $izinliSayfalar[$sayfa];

$hataMesaji = '';
$basariliMi = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $icerik = $_POST['icerik'] ?? '';
    if (trim(strip_tags($icerik)) === '') {
        $hataMesaji = 'İçerik boş olamaz.';
    } else {
        $guncelle = $pdo->prepare("UPDATE sabit_sayfa_icerikleri SET icerik = :icerik WHERE sayfa_anahtari = :sayfa");
        $guncelle->execute(['icerik' => $icerik, 'sayfa' => $sayfa]);
        if ($guncelle->rowCount() === 0) {
            // Satır hiç yoksa (beklenmedik durum) burada oluşturulur
            $ekle = $pdo->prepare("INSERT INTO sabit_sayfa_icerikleri (sayfa_anahtari, icerik) VALUES (:sayfa, :icerik)");
            $ekle->execute(['sayfa' => $sayfa, 'icerik' => $icerik]);
        }
        $basariliMi = true;
        islemKaydet($pdo, $ayar['baslik'] . ' İçeriğini Güncelledi');
    }
}

$stmt = $pdo->prepare("SELECT icerik FROM sabit_sayfa_icerikleri WHERE sayfa_anahtari = :sayfa");
$stmt->execute(['sayfa' => $sayfa]);
$mevcutIcerik = $stmt->fetchColumn();
if ($mevcutIcerik === false) {
    $mevcutIcerik = '';
}

$sayfaBasligi = $ayar['baslik'] . ' Düzenle';
$aktifMenu = $ayar['menu'];
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-file-earmark-text-fill me-2"></i><?php echo htmlspecialchars($ayar['baslik']); ?> Sayfa İçeriği</h4>
    <a href="<?php echo htmlspecialchars($ayar['ornek']); ?>" target="_blank" class="btn-goruntule-kutu">Görüntüle</a>
</div>

<?php if ($basariliMi): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>İçerik başarıyla güncellendi. Sayfayı yenilediğinizde değişikliği göreceksiniz.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($hataMesaji): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($hataMesaji); ?></div>
<?php endif; ?>

<div class="card admin-kart p-4">
    <form method="POST" action="icerik-sayfasi-duzenle.php?sayfa=<?php echo htmlspecialchars($sayfa); ?>">
        <input type="hidden" name="sayfa" value="<?php echo htmlspecialchars($sayfa); ?>">
        <textarea name="icerik" id="icerikEditor"><?php echo htmlspecialchars($mevcutIcerik); ?></textarea>
        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#icerikEditor',
        height: 520,
        menubar: false,
        plugins: 'lists link image table code',
        toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link | code',
        branding: false
    });
</script>

<?php include 'includes/admin-bitis.php'; ?>
