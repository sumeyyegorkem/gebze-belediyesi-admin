<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

// Başkana doğrudan bağlı birimler (baskan_yardimcisi_id NULL olanlar)
$dogrudanBirimler = $pdo->query(
    "SELECT * FROM mudurlukler WHERE baskan_yardimcisi_id IS NULL ORDER BY sira ASC"
)->fetchAll();

// Başkan yardımcıları ve her birine bağlı birimler
$yardimcilar = $pdo->query("SELECT * FROM baskan_yardimcilari ORDER BY sira ASC")->fetchAll();
foreach ($yardimcilar as &$y) {
    $stmt = $pdo->prepare("SELECT * FROM mudurlukler WHERE baskan_yardimcisi_id = :id ORDER BY sira ASC");
    $stmt->execute(['id' => $y['id']]);
    $y['birimler'] = $stmt->fetchAll();
}
unset($y);

$sayfaBasligi = 'Yönetim Şeması';
$aktifMenu = 'yonetim-semasi';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Yönetim Şeması</h4>
    <a href="../kurumsal/yonetim-semasi.php" target="_blank" class="btn-goruntule-kutu">Görüntüle</a>
</div>

<?php if (isset($_GET['basarili'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>İşlem başarıyla tamamlandı.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Başkana Bağlı Birimler -->
<div class="card admin-kart mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-diagram-3-fill me-1"></i> Başkana Doğrudan Bağlı Birimler</h6>
            <a href="mudurlukler-ekle.php" class="btn btn-sm btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Birim Ekle</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Birim</th>
                        <th>Müdür</th>
                        <th>Sıra</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($dogrudanBirimler) === 0): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">Doğrudan başkana bağlı birim eklenmemiş.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($dogrudanBirimler as $b): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($b['ad']); ?></td>
                            <td><?php echo htmlspecialchars($b['mudur']); ?></td>
                            <td><?php echo (int)$b['sira']; ?></td>
                            <td class="text-end">
                                <div class="dropdown admin-islem-dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="mudurlukler-duzenle.php?id=<?php echo $b['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                        <li><a class="dropdown-item" href="mudurlukler-sil.php?id=<?php echo $b['id']; ?>" onclick="return confirm('Bu birimi silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Başkan Yardımcıları -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h6 class="fw-bold mb-0"><i class="bi bi-person-check-fill me-1"></i> Başkan Yardımcıları ve Bağlı Birimleri</h6>
    <a href="baskan-yardimcilari-ekle.php" class="btn btn-sm btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yardımcı Ekle</a>
</div>

<?php if (count($yardimcilar) === 0): ?>
    <div class="card admin-kart mb-4"><div class="card-body text-center text-muted py-4">Henüz başkan yardımcısı eklenmemiş.</div></div>
<?php endif; ?>

<?php foreach ($yardimcilar as $y): ?>
<div class="card admin-kart mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-2">
                <img src="<?php echo htmlspecialchars($y['foto']); ?>" style="width:40px;height:40px;object-fit:cover;border-radius:50%;" onerror="this.style.display='none';" alt="">
                <div>
                    <span class="fw-bold"><?php echo htmlspecialchars($y['ad']); ?></span>
                    <span class="text-muted small d-block">Başkan Yardımcısı &middot; Sıra: <?php echo (int)$y['sira']; ?></span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="mudurlukler-ekle.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-plus-lg me-1"></i> Bu Yardımcıya Birim Ekle</a>
                <div class="dropdown admin-islem-dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="baskan-yardimcilari-duzenle.php?id=<?php echo $y['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                        <li><a class="dropdown-item" href="baskan-yardimcilari-sil.php?id=<?php echo $y['id']; ?>" onclick="return confirm('Bu başkan yardımcısını silmek istediğinize emin misiniz? Bağlı birimler doğrudan başkana bağlı hale gelecek.');"><i class="bi bi-trash-fill"></i> Sil</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Birim</th>
                        <th>Müdür</th>
                        <th>Sıra</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($y['birimler']) === 0): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">Bu yardımcıya bağlı birim eklenmemiş.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($y['birimler'] as $b): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($b['ad']); ?></td>
                            <td><?php echo htmlspecialchars($b['mudur']); ?></td>
                            <td><?php echo (int)$b['sira']; ?></td>
                            <td class="text-end">
                                <div class="dropdown admin-islem-dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="mudurlukler-duzenle.php?id=<?php echo $b['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                        <li><a class="dropdown-item" href="mudurlukler-sil.php?id=<?php echo $b['id']; ?>" onclick="return confirm('Bu birimi silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php include 'includes/admin-bitis.php'; ?>
