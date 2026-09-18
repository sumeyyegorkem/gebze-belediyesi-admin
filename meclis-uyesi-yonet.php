<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';
require_once 'includes/sayfalama-yardimci.php';

$arama = trim($_GET['q'] ?? '');
$mevcutSayfa = adminMevcutSayfa();
$sayfaBoyutu = ADMIN_SAYFA_BOYUTU;
$offset = ($mevcutSayfa - 1) * $sayfaBoyutu;

if ($arama !== '') {
    $sayimStmt = $pdo->prepare("SELECT COUNT(*) AS toplam FROM meclis_uyeleri WHERE ad_soyad LIKE :q");
    $sayimStmt->execute(['q' => '%' . $arama . '%']);
    $toplamKayit = (int)$sayimStmt->fetch()['toplam'];

    $stmt = $pdo->prepare("SELECT * FROM meclis_uyeleri WHERE ad_soyad LIKE :q ORDER BY sira ASC, id ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':q', '%' . $arama . '%');
    $stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $uyeler = $stmt->fetchAll();
} else {
    $toplamKayit = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM meclis_uyeleri")->fetch()['toplam'];

    $stmt = $pdo->prepare("SELECT * FROM meclis_uyeleri ORDER BY sira ASC, id ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $uyeler = $stmt->fetchAll();
}

$toplamSayfa = adminToplamSayfa($toplamKayit, $sayfaBoyutu);

$sayfaBasligi = 'Meclis Üyeleri';
$aktifMenu = 'sabit-meclis';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Meclis Üyeleri</h4>
    <div class="d-flex gap-2">
        <a href="../kurumsal/belediye-meclisi.php" target="_blank" class="btn-goruntule-kutu">Görüntüle</a>
        <a href="meclis-uyesi-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Üye Ekle</a>
    </div>
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
                    <th>Sıra</th>
                    <th>Görsel</th>
                    <th>Ad Soyad</th>
                    <th>Durum</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($uyeler) === 0): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen üye bulunamadı.' : 'Henüz üye eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($uyeler as $u): ?>
                    <tr>
                        <td><?php echo (int)$u['sira']; ?></td>
                        <?php $uFotoYolu = (strpos($u['foto_url'], 'http://') === 0 || strpos($u['foto_url'], 'https://') === 0) ? $u['foto_url'] : '../' . $u['foto_url']; ?>
                        <td><img src="<?php echo htmlspecialchars($uFotoYolu); ?>" class="admin-thumb-tam" alt="" onerror="this.src='https://placehold.co/80x60?text=Yok';"></td>
                        <td><?php echo htmlspecialchars($u['ad_soyad']); ?></td>
                        <td>
                            <?php if ((int)$u['aktif'] === 1): ?>
                                <span class="badge bg-success">AKTİF</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">PASİF</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="meclis-uyesi-duzenle.php?id=<?php echo $u['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=meclis-uyesi&id=<?php echo $u['id']; ?>">
                                        <?php if ((int)$u['aktif'] === 1): ?>
                                            <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                        <?php else: ?>
                                            <i class="bi bi-eye-fill"></i> Aktif Yap
                                        <?php endif; ?>
                                    </a></li>
                                    <li><a class="dropdown-item" href="meclis-uyesi-sil.php?id=<?php echo $u['id']; ?>" onclick="return confirm('Bu üyeyi silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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
