<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';
require_once 'includes/sayfalama-yardimci.php';

$arama = trim($_GET['ara'] ?? '');
$mevcutSayfa = adminMevcutSayfa();
$sayfaBoyutu = ADMIN_SAYFA_BOYUTU;
$offset = ($mevcutSayfa - 1) * $sayfaBoyutu;

if ($arama !== '') {
    $sayimStmt = $pdo->prepare("SELECT COUNT(*) AS toplam FROM muhtarlar WHERE ad LIKE :ara1 OR mahalle LIKE :ara2");
    $sayimStmt->execute(['ara1' => '%' . $arama . '%', 'ara2' => '%' . $arama . '%']);
    $toplamKayit = (int)$sayimStmt->fetch()['toplam'];

    $stmt = $pdo->prepare("SELECT * FROM muhtarlar WHERE ad LIKE :ara1 OR mahalle LIKE :ara2 ORDER BY sira ASC, id ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':ara1', '%' . $arama . '%');
    $stmt->bindValue(':ara2', '%' . $arama . '%');
    $stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $muhtarlar = $stmt->fetchAll();
} else {
    $toplamKayit = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM muhtarlar")->fetch()['toplam'];

    $stmt = $pdo->prepare("SELECT * FROM muhtarlar ORDER BY sira ASC, id ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $muhtarlar = $stmt->fetchAll();
}

$toplamSayfa = adminToplamSayfa($toplamKayit, $sayfaBoyutu);

$sayfaBasligi = 'Mahalle Muhtarları';
$aktifMenu = 'muhtarlar';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-signpost-split-fill me-2"></i>Mahalle Muhtarları</h4>
    <a href="muhtar-ekle.php" class="btn btn-belediye"><i class="bi bi-plus-circle-fill me-1"></i> Yeni Muhtar Ekle</a>
</div>

<?php if (isset($_GET['basarili'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        İşlem başarıyla tamamlandı.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card admin-kart p-4">
    <form method="GET" action="muhtar-yonet.php" class="mb-3" style="max-width:400px;">
        <div class="input-group">
            <input type="text" name="ara" class="form-control" placeholder="Mahalle veya muhtar adı ara..." value="<?php echo htmlspecialchars($arama); ?>">
            <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Muhtar</th>
                    <th>Mahalle</th>
                    <th>Telefon</th>
                    <th>Durum</th>
                    <th class="text-end">İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($muhtarlar) === 0): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Kayıt bulunamadı.</td></tr>
                <?php endif; ?>
                <?php foreach ($muhtarlar as $m): ?>
                <tr>
                    <td><?php echo htmlspecialchars($m['ad']); ?></td>
                    <td><?php echo htmlspecialchars($m['mahalle']); ?></td>
                    <td><?php echo htmlspecialchars($m['tel']); ?></td>
                    <td>
                        <?php if ((int)$m['aktif'] === 1): ?>
                            <span class="badge bg-success">AKTİF</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">PASİF</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="../gebze/muhtarlar.php" target="_blank"><i class="bi bi-eye-fill me-2"></i>Görüntüle</a></li>
                                <li><a class="dropdown-item" href="muhtar-duzenle.php?id=<?php echo $m['id']; ?>"><i class="bi bi-pencil-fill me-2"></i>Düzenle</a></li>
                                <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=muhtar&id=<?php echo $m['id']; ?>">
                                    <?php if ((int)$m['aktif'] === 1): ?><i class="bi bi-eye-slash-fill me-2"></i>Pasife Al<?php else: ?><i class="bi bi-eye-fill me-2"></i>Aktif Yap<?php endif; ?>
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="muhtar-sil.php?id=<?php echo $m['id']; ?>" onclick="return confirm('Bu muhtarı silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill me-2"></i>Sil</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($toplamSayfa > 1): ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 border-top">
        <span class="admin-sayfalama-bilgi">
            Toplam <?php echo $toplamKayit; ?> kayıttan <?php echo $offset + 1; ?>-<?php echo min($offset + $sayfaBoyutu, $toplamKayit); ?> arası gösteriliyor
        </span>
        <?php adminSayfalamaCiz($mevcutSayfa, $toplamSayfa, $arama !== '' ? ['ara' => $arama] : []); ?>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/admin-bitis.php'; ?>
