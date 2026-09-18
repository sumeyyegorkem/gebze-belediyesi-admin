<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hataMesaji = '';
$basariMesaji = '';

// Bakım Modu, veritabanında değil "config/bakim.lock" adlı bir kilit dosyasında tutulur:
// dosya varsa site bakımda demektir, dosyanın içeriği de (varsa) özel bakım mesajıdır.
// Bu sayede .htaccess, veritabanına hiç sormadan sadece dosyanın var olup olmadığına bakarak
// ziyaretçileri bakim.php'ye yönlendirebilir.
$bakimKilitYolu = '../config/bakim.lock';
$bakimAktif = file_exists($bakimKilitYolu);
$bakimMesaji = $bakimAktif ? file_get_contents($bakimKilitYolu) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bakimAktif = isset($_POST['bakim_aktif']);
    $bakimMesaji = trim($_POST['bakim_mesaji'] ?? '');

    if ($bakimAktif) {
        file_put_contents($bakimKilitYolu, $bakimMesaji);
        $basariMesaji = 'Site bakım moduna alındı. Ziyaretçiler artık bakım ekranını görecek (admin paneli etkilenmez).';
        islemKaydet($pdo, 'Bakım Modunu Açtı');
    } else {
        if (file_exists($bakimKilitYolu)) {
            unlink($bakimKilitYolu);
        }
        $basariMesaji = 'Bakım modu kapatıldı. Site tekrar normal şekilde yayında.';
        islemKaydet($pdo, 'Bakım Modunu Kapattı');
    }
}

$sayfaBasligi = 'Bakım Modu';
$aktifMenu = 'bakim-modu';
include 'includes/admin-baslangic.php';
?>

<?php if ($bakimAktif): ?>
    <div class="d-flex align-items-center gap-3 p-4 mb-4 rounded-4" style="background:linear-gradient(135deg,#b02a2a 0%,#7a1c1c 100%); color:#fff;">
        <div style="width:56px;height:56px;flex:0 0 auto;border-radius:14px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:1.6rem;">
            <i class="bi bi-cone-striped"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:1.15rem;">Site şu anda BAKIMDA</div>
            <div style="opacity:.9;font-size:.92rem;">Ziyaretçiler, aşağıdaki mesajla birlikte bakım ekranını görüyor. Yönetim paneli etkilenmez.</div>
        </div>
    </div>
<?php else: ?>
    <div class="d-flex align-items-center gap-3 p-4 mb-4 rounded-4" style="background:linear-gradient(135deg,#1e8f5f 0%,#146b46 100%); color:#fff;">
        <div style="width:56px;height:56px;flex:0 0 auto;border-radius:14px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:1.6rem;">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <div class="fw-bold" style="font-size:1.15rem;">Site normal şekilde yayında</div>
            <div style="opacity:.9;font-size:.92rem;">Ziyaretçiler siteyi olağan şekilde görüntülüyor.</div>
        </div>
    </div>
<?php endif; ?>

<?php if ($hataMesaji): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($hataMesaji); ?></div>
<?php endif; ?>
<?php if ($basariMesaji): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($basariMesaji); ?></div>
<?php endif; ?>

<div class="card admin-kart p-4 mb-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-gear-fill me-2"></i>Bakım Modu Ayarları</h4>

    <form method="POST" action="bakim-modu.php">
        <div class="mb-4">
            <label class="form-label">Bakım Mesajı <span class="text-muted small">(boş bırakılırsa varsayılan mesaj gösterilir)</span></label>
            <textarea name="bakim_mesaji" rows="3" class="form-control"
                      placeholder="Örn: Sitemizde planlı bakım çalışması yapılmaktadır, 18:00'de tekrar yayındayız."><?php echo htmlspecialchars($bakimMesaji); ?></textarea>
        </div>

        <div class="form-check form-switch mb-4">
            <input type="checkbox" name="bakim_aktif" id="bakimAktif" class="form-check-input" role="switch"
                   value="1" <?php echo $bakimAktif ? 'checked' : ''; ?>>
            <label class="form-check-label fw-bold" for="bakimAktif">Siteyi bakım moduna al</label>
        </div>

        <button type="submit" class="btn btn-lacivert px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
