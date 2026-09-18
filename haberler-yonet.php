<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';
require_once 'includes/sayfalama-yardimci.php';

$arama = trim($_GET['q'] ?? '');
$mevcutSayfa = adminMevcutSayfa();
$sayfaBoyutu = ADMIN_SAYFA_BOYUTU;
$offset = ($mevcutSayfa - 1) * $sayfaBoyutu;

if ($arama !== '') {
    $sayimStmt = $pdo->prepare("SELECT COUNT(*) AS toplam FROM haberler WHERE baslik LIKE :q");
    $sayimStmt->execute(['q' => '%' . $arama . '%']);
    $toplamKayit = (int)$sayimStmt->fetch()['toplam'];

    $stmt = $pdo->prepare("SELECT * FROM haberler WHERE baslik LIKE :q ORDER BY yayin_tarihi DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':q', '%' . $arama . '%');
    $stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $haberler = $stmt->fetchAll();
} else {
    $toplamKayit = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM haberler")->fetch()['toplam'];

    $stmt = $pdo->prepare("SELECT * FROM haberler ORDER BY yayin_tarihi DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $sayfaBoyutu, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $haberler = $stmt->fetchAll();
}

$toplamSayfa = adminToplamSayfa($toplamKayit, $sayfaBoyutu);

$sayfaBasligi = 'Haberler';
$aktifMenu = 'haberler';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Haber Yönetimi</h4>
    <div class="d-flex gap-2">
        <a href="haber-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Haber Ekle</a>
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
        <input type="text" name="q" class="form-control" placeholder="Haberlerde ara..." value="<?php echo htmlspecialchars($arama); ?>">
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
                    <th>Tarih</th>
                    <th>Görüntülenme</th>
                    <th>Durum</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($haberler) === 0): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen haber bulunamadı.' : 'Henüz haber eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($haberler as $hSira => $h): ?>
                    <tr>
                        <td><?php echo $offset + $hSira + 1; ?></td>
                        <td><img src="<?php echo htmlspecialchars($h['resim_url']); ?>" class="admin-thumb" alt=""></td>
                        <td><?php echo htmlspecialchars($h['baslik']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($h['kategori']); ?></span></td>
                        <td><?php echo date('d.m.Y', strtotime($h['yayin_tarihi'])); ?></td>
                        <td><?php echo (int)$h['goruntulenme']; ?></td>
                        <td>
                            <?php if ((int)$h['aktif'] === 1): ?>
                                <span class="badge bg-success">AKTİF</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">PASİF</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="../haberler/haber-detay.php?id=<?php echo $h['id']; ?>" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                    <li><a class="dropdown-item" href="fotograf-ekle.php?sayfa=haber-<?php echo $h['id']; ?>" target="_blank"><i class="bi bi-image-fill"></i> Resim Ekle</a></li>
                                    <li><a class="dropdown-item" href="haber-duzenle.php?id=<?php echo $h['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=haber&id=<?php echo $h['id']; ?>">
                                        <?php if ((int)$h['aktif'] === 1): ?>
                                            <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                        <?php else: ?>
                                            <i class="bi bi-eye-fill"></i> Aktif Yap
                                        <?php endif; ?>
                                    </a></li>
                                    <li><a class="dropdown-item" href="haber-sil.php?id=<?php echo $h['id']; ?>" onclick="return confirm('Bu haberi silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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
