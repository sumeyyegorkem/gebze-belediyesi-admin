<?php
/**
 * admin/islem-gecmisi.php
 * -------------------------------------------------------
 * Yöneticilerin site üzerinde yaptığı ekleme/düzenleme/silme
 * işlemlerinin listesi (kim, ne zaman, ne yaptı).
 * Kayıtlar config/db.php içindeki islemKaydet() fonksiyonuyla
 * her ilgili admin sayfasından tek satırla eklenir.
 * -------------------------------------------------------
 */
require_once 'oturum_kontrol.php';
require_once '../config/db.php';
require_once 'includes/yonetici-yardimci.php';
require_once 'includes/sayfalama-yardimci.php';

$arama = trim($_GET['q'] ?? '');
$mevcutSayfa = adminMevcutSayfa();
$sayfaBoyutu = ADMIN_SAYFA_BOYUTU;
$offset = ($mevcutSayfa - 1) * $sayfaBoyutu;

if ($arama !== '') {
    $sayimStmt = $pdo->prepare(
        "SELECT COUNT(*) AS toplam FROM islem_kayitlari WHERE yonetici_adi LIKE :q1 OR eylem LIKE :q2 OR hedef LIKE :q3"
    );
    $sayimStmt->execute(['q1' => '%' . $arama . '%', 'q2' => '%' . $arama . '%', 'q3' => '%' . $arama . '%']);
    $toplamKayit = (int)$sayimStmt->fetch()['toplam'];

    $stmt = $pdo->prepare(
        "SELECT * FROM islem_kayitlari WHERE yonetici_adi LIKE :q1 OR eylem LIKE :q2 OR hedef LIKE :q3
         ORDER BY tarih DESC LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue(':q1', '%' . $arama . '%');
    $stmt->bindValue(':q2', '%' . $arama . '%');
    $stmt->bindValue(':q3', '%' . $arama . '%');
    $stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $kayitlar = $stmt->fetchAll();
} else {
    $toplamKayit = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM islem_kayitlari")->fetch()['toplam'];

    $stmt = $pdo->prepare("SELECT * FROM islem_kayitlari ORDER BY tarih DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $kayitlar = $stmt->fetchAll();
}

$toplamSayfa = adminToplamSayfa($toplamKayit, $sayfaBoyutu);

// Her satırdaki yöneticinin güncel avatarını göstermek için, mevcut yöneticilerin
// foto/renk bilgisini id'ye göre önceden çekiyoruz (silinen yöneticiler için yok sayılır)
$yoneticiAvatarlari = [];
$avatarStmt = $pdo->query("SELECT id, fotograf, avatar_rengi FROM yoneticiler");
foreach ($avatarStmt->fetchAll() as $av) {
    $yoneticiAvatarlari[(int)$av['id']] = $av;
}

$sayfaBasligi = 'İşlem Geçmişi';
$aktifMenu = 'islem-gecmisi';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">İşlem Geçmişi</h4>
</div>

<form method="GET" class="mb-3">
    <div class="input-group admin-arama-kutusu">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" name="q" class="form-control" placeholder="Yönetici, eylem ya da kayıt adına göre ara..." value="<?php echo htmlspecialchars($arama); ?>">
    </div>
</form>

<div class="card admin-kart">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th></th>
                    <th>Yönetici</th>
                    <th>Eylem</th>
                    <th>Kayıt</th>
                    <th>Tarih</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($kayitlar) === 0): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen işlem bulunamadı.' : 'Henüz kayıtlı bir işlem yok.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($kayitlar as $k): ?>
                    <?php
                        $yid = $k['yonetici_id'] !== null ? (int)$k['yonetici_id'] : null;
                        $av = ($yid !== null && isset($yoneticiAvatarlari[$yid])) ? $yoneticiAvatarlari[$yid] : ['fotograf' => null, 'avatar_rengi' => 'mavi'];
                    ?>
                    <tr>
                        <td><?php echo adminAvatarHtml($k['yonetici_adi'], $av['fotograf'], $av['avatar_rengi'], 'admin-tablo-avatar'); ?></td>
                        <td><?php echo htmlspecialchars($k['yonetici_adi']); ?></td>
                        <td><?php echo htmlspecialchars($k['eylem']); ?></td>
                        <td><?php echo $k['hedef'] !== null ? htmlspecialchars($k['hedef']) : '<span class="text-muted">—</span>'; ?></td>
                        <td class="small text-muted"><?php echo date('d.m.Y H:i', strtotime($k['tarih'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($toplamSayfa > 1): ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-top">
        <span class="admin-sayfalama-bilgi">
            Toplam <?php echo $toplamKayit; ?> kayıttan <?php echo $offset + 1; ?>-<?php echo min($offset + $sayfaBoyutu, $toplamKayit); ?> arası gösteriliyor
        </span>
        <?php adminSayfalamaCiz($mevcutSayfa, $toplamSayfa, $arama !== '' ? ['q' => $arama] : []); ?>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/admin-bitis.php'; ?>
