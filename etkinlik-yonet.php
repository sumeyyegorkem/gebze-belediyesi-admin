<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM etkinlikler WHERE baslik LIKE :q1 OR mekan LIKE :q2 ORDER BY etkinlik_tarihi DESC");
    $stmt->execute(['q1' => '%' . $arama . '%', 'q2' => '%' . $arama . '%']);
    $etkinlikler = $stmt->fetchAll();
} else {
    $etkinlikler = $pdo->query("SELECT * FROM etkinlikler ORDER BY etkinlik_tarihi DESC")->fetchAll();
}

$sayfaBasligi = 'Etkinlikler';
$aktifMenu = 'etkinlikler';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Etkinlik Yönetimi</h4>
    <div class="d-flex gap-2">
        <a href="etkinlik-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Etkinlik Ekle</a>
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
                    <th>Tür</th>
                    <th>Mekan</th>
                    <th>Tarih</th>
                    <th>Saat</th>
                    <th>Durum</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($etkinlikler) === 0): ?>
                    <tr><td colspan="9" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen etkinlik bulunamadı.' : 'Henüz etkinlik eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($etkinlikler as $e): ?>
                    <tr>
                        <td><?php echo (int)$e['id']; ?></td>
                        <td><img src="<?php echo htmlspecialchars($e['resim_url']); ?>" class="admin-thumb" alt=""></td>
                        <td><?php echo htmlspecialchars($e['baslik']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($e['tur']); ?></span></td>
                        <td><?php echo htmlspecialchars($e['mekan']); ?></td>
                        <td><?php echo htmlspecialchars($e['etkinlik_tarihi']); ?></td>
                        <td><?php echo htmlspecialchars($e['etkinlik_saati']); ?></td>
                        <td>
                            <?php if ((int)$e['aktif'] === 1): ?>
                                <span class="badge bg-success">AKTİF</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">PASİF</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="../etkinlikler/etkinlik-detay.php?id=<?php echo $e['id']; ?>" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                    <li><a class="dropdown-item" href="fotograf-ekle.php?sayfa=etkinlik-<?php echo $e['id']; ?>" target="_blank"><i class="bi bi-image-fill"></i> Resim Ekle</a></li>
                                    <li><a class="dropdown-item" href="etkinlik-duzenle.php?id=<?php echo $e['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=etkinlik&id=<?php echo $e['id']; ?>">
                                        <?php if ((int)$e['aktif'] === 1): ?>
                                            <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                        <?php else: ?>
                                            <i class="bi bi-eye-fill"></i> Aktif Yap
                                        <?php endif; ?>
                                    </a></li>
                                    <li><a class="dropdown-item" href="etkinlik-sil.php?id=<?php echo $e['id']; ?>" onclick="return confirm('Bu etkinliği silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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

