<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

function faaliyetSlugUret($metin) {
    $donusum = [
        'ç' => 'c', 'Ç' => 'c', 'ğ' => 'g', 'Ğ' => 'g', 'ı' => 'i', 'I' => 'i',
        'İ' => 'i', 'ö' => 'o', 'Ö' => 'o', 'ş' => 's', 'Ş' => 's', 'ü' => 'u', 'Ü' => 'u',
    ];
    $metin = strtr($metin, $donusum);
    $metin = mb_strtolower($metin, 'UTF-8');
    $metin = preg_replace('/[^a-z0-9]+/', '-', $metin);
    return trim($metin, '-');
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $ikon = trim($_POST['ikon'] ?? '') ?: 'bi-grid-fill';
    $anahtar = trim($_POST['anahtar'] ?? '');

    if ($baslik === '') {
        $hataMesaji = 'Lütfen kategori başlığını doldurun.';
    } else {
        if ($anahtar === '') {
            $anahtar = faaliyetSlugUret($baslik);
        } else {
            $anahtar = faaliyetSlugUret($anahtar);
        }
        if ($anahtar === '') {
            $anahtar = 'kategori-' . uniqid();
        }
        // Anahtar benzersiz olmalı; çakışırsa sonuna sayı ekle
        $temelAnahtar = $anahtar;
        $sayac = 2;
        while (true) {
            $stmtKontrol = $pdo->prepare("SELECT COUNT(*) AS toplam FROM faaliyet_kategorileri WHERE anahtar = :anahtar");
            $stmtKontrol->execute(['anahtar' => $anahtar]);
            if ((int)$stmtKontrol->fetch()['toplam'] === 0) break;
            $anahtar = $temelAnahtar . '-' . $sayac;
            $sayac++;
        }

        $sonraSira = (int)$pdo->query("SELECT COALESCE(MAX(sira), -1) + 1 AS sonraki FROM faaliyet_kategorileri")->fetch()['sonraki'];

        $stmt = $pdo->prepare("INSERT INTO faaliyet_kategorileri (anahtar, baslik, ikon, sira) VALUES (:anahtar, :baslik, :ikon, :sira)");
        $stmt->execute([
            'anahtar' => $anahtar,
            'baslik' => $baslik,
            'ikon' => $ikon,
            'sira' => $sonraSira,
        ]);

        islemKaydet($pdo, 'Faaliyet Kategorisi Ekledi', $baslik);
        header('Location: faaliyet-alanlari-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Faaliyet Kategorisi Ekle';
$aktifMenu = 'faaliyet-alanlari';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Faaliyet Kategorisi Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="faaliyet-kategori-ekle.php">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Kategori Başlığı *</label>
                <input type="text" name="baslik" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['baslik'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">İkon</label>
                <input type="text" name="ikon" class="form-control" placeholder="bi-easel-fill"
                       value="<?php echo htmlspecialchars($_POST['ikon'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Anahtar</label>
                <input type="text" name="anahtar" class="form-control" placeholder="atolyeler"
                       value="<?php echo htmlspecialchars($_POST['anahtar'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="faaliyet-alanlari-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
