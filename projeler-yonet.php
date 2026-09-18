<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';
require_once 'includes/sayfalama-yardimci.php';

$arama = trim($_GET['q'] ?? '');
$mevcutSayfa = adminMevcutSayfa();
$sayfaBoyutu = ADMIN_SAYFA_BOYUTU;
$offset = ($mevcutSayfa - 1) * $sayfaBoyutu;

if ($arama !== '') {
    $sayimStmt = $pdo->prepare("SELECT COUNT(*) AS toplam FROM projeler WHERE baslik LIKE :q1 OR aciklama LIKE :q2");
    $sayimStmt->execute(['q1' => '%' . $arama . '%', 'q2' => '%' . $arama . '%']);
    $toplamKayit = (int)$sayimStmt->fetch()['toplam'];

    $stmt = $pdo->prepare("SELECT * FROM projeler WHERE baslik LIKE :q1 OR aciklama LIKE :q2 ORDER BY olusturma_tarihi DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':q1', '%' . $arama . '%');
    $stmt->bindValue(':q2', '%' . $arama . '%');
    $stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $projeler = $stmt->fetchAll();
} else {
    $toplamKayit = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM projeler")->fetch()['toplam'];

    $stmt = $pdo->prepare("SELECT * FROM projeler ORDER BY olusturma_tarihi DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $projeler = $stmt->fetchAll();
}

$toplamSayfa = adminToplamSayfa($toplamKayit, $sayfaBoyutu);

$durumEtiket = ['tamamlanmis' => 'Tamamlanmış', 'devam_eden' => 'Devam Eden', 'planli' => 'Planlanan'];
$durumRenk = ['tamamlanmis' => 'bg-success', 'devam_eden' => 'bg-info text-dark', 'planli' => 'bg-warning text-dark'];

$sayfaBasligi = 'Projeler';
$aktifMenu = 'projeler';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Proje Yönetimi</h4>
    <div class="d-flex gap-2">
        <a href="projeler-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Proje Ekle</a>
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
                    <th>#</th>
                    <th>Görsel</th>
                    <th>Başlık</th>
                    <th>Kategori</th>
                    <th>Durum</th>
                    <th>Yayın</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($projeler) === 0): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen proje bulunamadı.' : 'Henüz proje eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($projeler as $pSira => $p): ?>
                    <tr>
                        <td><?php echo $offset + $pSira + 1; ?></td>
                        <td><img src="<?php echo htmlspecialchars($p['resim_url']); ?>" class="admin-thumb" alt=""></td>
                        <td><?php echo htmlspecialchars($p['baslik']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($p['kategori'] ?? ''); ?></span></td>
                        <td><span class="badge <?php echo $durumRenk[$p['durum']] ?? 'bg-secondary'; ?>"><?php echo $durumEtiket[$p['durum']] ?? $p['durum']; ?></span></td>
                        <td>
                            <?php if ((int)$p['aktif'] === 1): ?>
                                <span class="badge bg-success">AKTİF</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">PASİF</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="../projeler/proje-detay.php?id=<?php echo $p['id']; ?>" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                    <li><a class="dropdown-item" href="projeler-duzenle.php?id=<?php echo $p['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=proje&id=<?php echo $p['id']; ?>">
                                        <?php if ((int)$p['aktif'] === 1): ?>
                                            <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                        <?php else: ?>
                                            <i class="bi bi-eye-fill"></i> Aktif Yap
                                        <?php endif; ?>
                                    </a></li>
                                    <li><a class="dropdown-item" href="projeler-sil.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Bu projeyi silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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

