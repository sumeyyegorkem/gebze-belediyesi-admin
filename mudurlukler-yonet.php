<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';
require_once 'includes/sayfalama-yardimci.php';

$arama = trim($_GET['q'] ?? '');
$mevcutSayfa = adminMevcutSayfa();
$sayfaBoyutu = ADMIN_SAYFA_BOYUTU;
$offset = ($mevcutSayfa - 1) * $sayfaBoyutu;

if ($arama !== '') {
    $sayimStmt = $pdo->prepare(
        "SELECT COUNT(*) AS toplam FROM mudurlukler m WHERE m.ad LIKE :q1 OR m.mudur LIKE :q2"
    );
    $sayimStmt->execute(['q1' => '%' . $arama . '%', 'q2' => '%' . $arama . '%']);
    $toplamKayit = (int)$sayimStmt->fetch()['toplam'];

    $stmt = $pdo->prepare(
        "SELECT m.*, y.ad AS yardimci_ad FROM mudurlukler m
         LEFT JOIN baskan_yardimcilari y ON y.id = m.baskan_yardimcisi_id
         WHERE m.ad LIKE :q1 OR m.mudur LIKE :q2
         ORDER BY m.sira ASC LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue(':q1', '%' . $arama . '%');
    $stmt->bindValue(':q2', '%' . $arama . '%');
    $stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $mudurlukler = $stmt->fetchAll();
} else {
    $toplamKayit = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM mudurlukler")->fetch()['toplam'];

    $stmt = $pdo->prepare(
        "SELECT m.*, y.ad AS yardimci_ad FROM mudurlukler m
         LEFT JOIN baskan_yardimcilari y ON y.id = m.baskan_yardimcisi_id
         ORDER BY m.sira ASC LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $mudurlukler = $stmt->fetchAll();
}

$toplamSayfa = adminToplamSayfa($toplamKayit, $sayfaBoyutu);

$sayfaBasligi = 'Müdürlükler';
$aktifMenu = 'mudurlukler';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Müdürlükler Yönetimi</h4>
    <a href="mudurlukler-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Müdürlük Ekle</a>
</div>

<?php if (isset($_GET['basarili'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>İşlem başarıyla tamamlandı.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="GET" class="mb-3">
    <div class="input-group admin-arama-kutusu">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" name="q" class="form-control" placeholder="Arama yapınız..." value="<?php echo htmlspecialchars($arama); ?>">
    </div>
</form>

<div class="card admin-kart">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Fotoğraf</th>
                    <th>Müdürlük</th>
                    <th>Müdür</th>
                    <th>Bağlı Olduğu</th>
                    <th>Sıra</th>
                    <th>Durum</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($mudurlukler) === 0): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen müdürlük bulunamadı.' : 'Henüz müdürlük eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($mudurlukler as $m): ?>
                    <tr>
                        <td>
                            <?php if (!empty($m['foto'])): ?>
                                <img src="<?php echo htmlspecialchars($m['foto']); ?>" class="admin-thumb" style="border-radius:50%;width:44px;height:44px;" alt="">
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($m['ad']); ?></td>
                        <td><?php echo htmlspecialchars($m['mudur']); ?></td>
                        <td>
                            <?php if ($m['yardimci_ad']): ?>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($m['yardimci_ad']); ?></span>
                            <?php else: ?>
                                <span class="badge bg-lacivert" style="background:var(--lacivert);">Doğrudan Başkan</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo (int)$m['sira']; ?></td>
                        <td>
                            <?php if ((int)$m['aktif'] === 1): ?>
                                <span class="badge bg-success">AKTİF</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">PASİF</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="../kurumsal/mudurlukler.php" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                    <li><a class="dropdown-item" href="mudurlukler-duzenle.php?id=<?php echo $m['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=mudurluk&id=<?php echo $m['id']; ?>">
                                        <?php if ((int)$m['aktif'] === 1): ?>
                                            <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                        <?php else: ?>
                                            <i class="bi bi-eye-fill"></i> Aktif Yap
                                        <?php endif; ?>
                                    </a></li>
                                    <li><a class="dropdown-item" href="mudurlukler-sil.php?id=<?php echo $m['id']; ?>" onclick="return confirm('Bu müdürlüğü silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
                                </ul>
                            </div>
                        </td>
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

