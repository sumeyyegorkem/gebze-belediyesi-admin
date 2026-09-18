<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';
$basariMesaji = '';

// 403/404/500/503 hata sayfalarının mesaj metinleri, birer dosyada tutulur.
// Dosya yoksa/boşsa sayfanın kendi varsayılan mesajı gösterilir; buradan
// özel bir metin girilirse o kullanılır.
$hataListesi = [
    '404' => [
        'baslik'  => 'Sayfa Bulunamadı',
        'ikon'    => 'bi-signpost-2-fill',
        'renk'    => '#6c757d',
        'tetik'   => 'Var olmayan ya da taşınmış bir adrese gidildiğinde gösterilir.',
        'varsayilan' => 'Bu sayfa taşınmış, adı değişmiş ya da hiç var olmamış olabilir. Yazım hatası olup olmadığını kontrol edebilir, ya da aşağıdaki bağlantılarla devam edebilirsiniz.',
        'yol'     => '../config/hata-mesaji-404.txt',
    ],
    '403' => [
        'baslik'  => 'Erişim Engellendi',
        'ikon'    => 'bi-shield-lock-fill',
        'renk'    => '#b8860b',
        'tetik'   => 'İzin verilmeyen bir klasöre (örn. uploads/, config/) doğrudan girilmeye çalışıldığında gösterilir.',
        'varsayilan' => 'Bu sayfaya ya da klasöre erişim izniniz yok. Yanlış bir bağlantıya tıklamış olabilirsiniz.',
        'yol'     => '../config/hata-mesaji-403.txt',
    ],
    '500' => [
        'baslik'  => 'Beklenmedik Hata',
        'ikon'    => 'bi-tools',
        'renk'    => '#b02a2a',
        'tetik'   => 'Sitede beklenmedik bir teknik hata (kod hatası, veritabanı sorunu vb.) oluştuğunda otomatik olarak devreye girer.',
        'varsayilan' => 'Sayfayı açmaya çalışırken sistemde beklenmedik bir sorun meydana geldi. Bu durum kayıt altına alındı, lütfen daha sonra tekrar deneyin.',
        'yol'     => '../config/hata-mesaji-500.txt',
    ],
    '503' => [
        'baslik'  => 'Hizmet Dışı',
        'ikon'    => 'bi-exclamation-octagon-fill',
        'renk'    => '#0b3d62',
        'tetik'   => 'Sunucu geçici olarak aşırı yüklendiğinde ya da hizmet veremediğinde gösterilir.',
        'varsayilan' => 'Sunucumuzda geçici bir yoğunluk ya da teknik bir sorun yaşanıyor. Lütfen birkaç dakika sonra tekrar deneyin.',
        'yol'     => '../config/hata-mesaji-503.txt',
    ],
];

foreach ($hataListesi as $kod => &$bilgi) {
    $bilgi['mesaj'] = file_exists($bilgi['yol']) ? file_get_contents($bilgi['yol']) : '';
}
unset($bilgi);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hata_kodu'], $hataListesi[$_POST['hata_kodu']])) {
    $kod = $_POST['hata_kodu'];
    $yeniMetin = trim($_POST['mesaj'] ?? '');
    $yol = $hataListesi[$kod]['yol'];

    if ($yeniMetin === '') {
        if (file_exists($yol)) {
            unlink($yol);
        }
    } else {
        file_put_contents($yol, $yeniMetin);
    }
    $hataListesi[$kod]['mesaj'] = $yeniMetin;
    $basariMesaji = $kod . ' — ' . $hataListesi[$kod]['baslik'] . ' mesajı güncellendi.';
    islemKaydet($pdo, 'Hata Sayfası Mesajını Güncelledi', $kod . ' - ' . $hataListesi[$kod]['baslik']);
}

$sayfaBasligi = 'Hata Sayfaları';
$aktifMenu = 'hata-sayfalari';
include 'includes/admin-baslangic.php';
?>

<?php if ($hataMesaji): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($hataMesaji); ?></div>
<?php endif; ?>
<?php if ($basariMesaji): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($basariMesaji); ?></div>
<?php endif; ?>

<h4 class="fw-bold mb-3"><i class="bi bi-exclamation-octagon-fill me-2"></i>Hata Sayfaları</h4>
<div class="row g-4">
    <?php foreach ($hataListesi as $kod => $bilgi): ?>
    <div class="col-lg-6">
        <div class="card admin-kart p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:48px;height:48px;flex:0 0 auto;border-radius:12px;background:<?php echo $bilgi['renk']; ?>1a;color:<?php echo $bilgi['renk']; ?>;display:flex;align-items:center;justify-content:center;font-size:1.35rem;">
                    <i class="bi <?php echo $bilgi['ikon']; ?>"></i>
                </div>
                <div>
                    <span class="badge" style="background:<?php echo $bilgi['renk']; ?>;color:#fff;font-size:.8rem;"><?php echo $kod; ?></span>
                    <span class="fw-bold ms-1"><?php echo htmlspecialchars($bilgi['baslik']); ?></span>
                </div>
            </div>
            <p class="small text-muted"><?php echo htmlspecialchars($bilgi['tetik']); ?></p>
            <form method="POST" action="hata-sayfalari-yonet.php" class="mt-auto">
                <input type="hidden" name="hata_kodu" value="<?php echo $kod; ?>">
                <textarea name="mesaj" rows="3" class="form-control mb-3"
                          placeholder="<?php echo htmlspecialchars($bilgi['varsayilan']); ?>"><?php echo htmlspecialchars($bilgi['mesaj']); ?></textarea>
                <button type="submit" class="btn btn-outline-secondary btn-sm px-3"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/admin-bitis.php'; ?>
