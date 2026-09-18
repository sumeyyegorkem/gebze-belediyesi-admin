<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM tarihi_yerler WHERE baslik LIKE :q ORDER BY sira ASC, id ASC");
    $stmt->execute(['q' => '%' . $arama . '%']);
    $yerler = $stmt->fetchAll();
} else {
    $yerler = $pdo->query("SELECT * FROM tarihi_yerler ORDER BY sira ASC, id ASC")->fetchAll();
}

$sayfaBasligi = 'Tarihi Yerler';
$aktifMenu = 'sabit-tarihi-yerler';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Tarihi Yerler</h4>
    <div class="d-flex gap-2">
        <a href="../gebze/kent-rehberi.php" target="_blank" class="btn-goruntule-kutu">Görüntüle</a>
        <a href="tarihi-yerler-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Yer Ekle</a>
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
                    <th>Sıra</th>
                    <th>Görsel</th>
                    <th>Başlık</th>
                    <th>Kısa Açıklama</th>
                    <th>Durum</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($yerler) === 0): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen yer bulunamadı.' : 'Henüz yer eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($yerler as $y): ?>
                    <tr>
                        <td><?php echo (int)$y['id']; ?></td>
                        <td><?php echo (int)$y['sira']; ?></td>
                        <td><img src="../<?php echo htmlspecialchars($y['resim_url']); ?>" class="admin-thumb" alt="" onerror="this.src='https://placehold.co/80x60?text=Yok';"></td>
                        <td><i class="bi <?php echo htmlspecialchars($y['ikon']); ?> me-1"></i><?php echo htmlspecialchars($y['baslik']); ?></td>
                        <td class="small text-muted"><?php echo htmlspecialchars($y['kisa_aciklama']); ?></td>
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
                                    <li><a class="dropdown-item" href="../gebze/kent-rehberi.php" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                    <li><a class="dropdown-item" href="fotograf-ekle.php?sayfa=tarihi-yer-<?php echo $y['id']; ?>" target="_blank"><i class="bi bi-image-fill"></i> Resim Ekle</a></li>
                                    <li><a class="dropdown-item" href="tarihi-yerler-duzenle.php?id=<?php echo $y['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=tarihi-yer&id=<?php echo $y['id']; ?>">
                                        <?php if ((int)$y['aktif'] === 1): ?>
                                            <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                        <?php else: ?>
                                            <i class="bi bi-eye-fill"></i> Aktif Yap
                                        <?php endif; ?>
                                    </a></li>
                                    <li><a class="dropdown-item" href="tarihi-yerler-sil.php?id=<?php echo $y['id']; ?>" onclick="return confirm('Bu yeri silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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
