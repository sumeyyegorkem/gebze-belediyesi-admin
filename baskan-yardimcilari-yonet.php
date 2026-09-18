<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM baskan_yardimcilari WHERE ad LIKE :q ORDER BY sira ASC");
    $stmt->execute(['q' => '%' . $arama . '%']);
    $yardimcilar = $stmt->fetchAll();
} else {
    $yardimcilar = $pdo->query("SELECT * FROM baskan_yardimcilari ORDER BY sira ASC")->fetchAll();
}

foreach ($yardimcilar as &$y) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS toplam FROM mudurlukler WHERE baskan_yardimcisi_id = :id");
    $stmt->execute(['id' => $y['id']]);
    $y['mudurlukSayisi'] = $stmt->fetch()['toplam'];
}
unset($y);

$sayfaBasligi = 'Başkan Yardımcıları';
$aktifMenu = 'baskan-yardimcilari';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Başkan Yardımcıları Yönetimi</h4>
    <a href="baskan-yardimcilari-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Başkan Yardımcısı Ekle</a>
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
                    <th>Fotoğraf</th>
                    <th>Ad Soyad</th>
                    <th>Slug</th>
                    <th>Bağlı Müdürlük</th>
                    <th>Sıra</th>
                    <th>Durum</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($yardimcilar) === 0): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen başkan yardımcısı bulunamadı.' : 'Henüz başkan yardımcısı eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($yardimcilar as $y): ?>
                    <tr>
                        <td><?php echo (int)$y['id']; ?></td>
                        <td><img src="<?php echo htmlspecialchars($y['foto']); ?>" style="width:44px;height:44px;object-fit:cover;border-radius:50%;" onerror="this.style.display='none';"></td>
                        <td><?php echo htmlspecialchars($y['ad']); ?></td>
                        <td><code><?php echo htmlspecialchars($y['slug']); ?></code></td>
                        <td><?php echo (int)$y['mudurlukSayisi']; ?></td>
                        <td><?php echo (int)$y['sira']; ?></td>
                        <td>
                            <?php if ((int)$y['aktif'] === 1): ?>
                                <span class="badge bg-success">AKTİF</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">PASİF</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="../kurumsal/baskan-yardimcilari.php" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                    <li><a class="dropdown-item" href="baskan-yardimcilari-duzenle.php?id=<?php echo $y['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=baskan-yardimcisi&id=<?php echo $y['id']; ?>">
                                        <?php if ((int)$y['aktif'] === 1): ?>
                                            <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                        <?php else: ?>
                                            <i class="bi bi-eye-fill"></i> Aktif Yap
                                        <?php endif; ?>
                                    </a></li>
                                    <li><a class="dropdown-item" href="baskan-yardimcilari-sil.php?id=<?php echo $y['id']; ?>" onclick="return confirm('Bu başkan yardımcısını silmek istediğinize emin misiniz? Bağlı müdürlükler doğrudan başkana bağlı hale gelecek.');"><i class="bi bi-trash-fill"></i> Sil</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/admin-bitis.php'; ?>

