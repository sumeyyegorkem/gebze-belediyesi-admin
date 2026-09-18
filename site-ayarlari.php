<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$ayar = $pdo->query("SELECT * FROM site_ayarlari WHERE id = 1")->fetch();
if (!$ayar) {
    // Güvenlik amaçlı: satır hiç yoksa (normalde db.php seed'i garanti eder) boş bir satır varsay
    $ayar = ['id' => 1, 'adres' => '', 'telefon' => '', 'eposta' => ''];
}

$hataMesaji = '';
$basariMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adres = trim($_POST['adres'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $eposta = trim($_POST['eposta'] ?? '');

    if ($adres === '' || $telefon === '' || $eposta === '') {
        $hataMesaji = 'Lütfen adres, telefon ve e-posta alanlarının tamamını doldurun.';
    } else {
        $pdo->prepare("INSERT INTO site_ayarlari (id, adres, telefon, eposta) VALUES (1, :adres, :telefon, :eposta)
                        ON DUPLICATE KEY UPDATE adres = :adres2, telefon = :telefon2, eposta = :eposta2")
            ->execute([
                'adres' => $adres, 'telefon' => $telefon, 'eposta' => $eposta,
                'adres2' => $adres, 'telefon2' => $telefon, 'eposta2' => $eposta,
            ]);

        $basariMesaji = 'İletişim bilgileri güncellendi.';
        $ayar = ['id' => 1, 'adres' => $adres, 'telefon' => $telefon, 'eposta' => $eposta];
        islemKaydet($pdo, 'Site Bilgilerini Güncelledi', $adres);
    }
}

$sayfaBasligi = 'Site Bilgileri';
$aktifMenu = 'site-ayarlari';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-gear-fill me-2"></i>Site Bilgileri (İletişim)</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>
    <?php if ($basariMesaji): ?>
        <div class="alert alert-success"><?php echo $basariMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="site-ayarlari.php">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Adres *</label>
                <input type="text" name="adres" class="form-control" required
                       value="<?php echo htmlspecialchars($ayar['adres']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Telefon *</label>
                <input type="text" name="telefon" class="form-control" required
                       value="<?php echo htmlspecialchars($ayar['telefon']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">E-posta *</label>
                <input type="email" name="eposta" class="form-control" required
                       value="<?php echo htmlspecialchars($ayar['eposta']); ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
