<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM duyurular WHERE baslik LIKE :q ORDER BY yayin_tarihi DESC");
    $stmt->execute(['q' => '%' . $arama . '%']);
    $duyurular = $stmt->fetchAll();
} else {
    $duyurular = $pdo->query("SELECT * FROM duyurular ORDER BY yayin_tarihi DESC")->fetchAll();
}

$sayfaBasligi = 'Duyurular';
$aktifMenu = 'duyurular';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Duyuru Yönetimi</h4>
    <div class="d-flex gap-2">
        <a href="duyuru-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Duyuru Ekle</a>
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
        <input type="text" name="q" class="form-control" placeholder="Duyurularda ara..." value="<?php echo htmlspecialchars($arama); ?>">
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
                <?php if (count($duyurular) === 0): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen duyuru bulunamadı.' : 'Henüz duyuru eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($duyurular as $d): ?>
                    <tr>
                        <td><?php echo (int)$d['id']; ?></td>
                        <td><img src="<?php echo htmlspecialchars($d['resim_url']); ?>" class="admin-thumb" alt=""></td>
                        <td><?php echo htmlspecialchars($d['baslik']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($d['kategori']); ?></span></td>
                        <td><?php echo date('d.m.Y', strtotime($d['yayin_tarihi'])); ?></td>
                        <td><?php echo (int)$d['goruntulenme']; ?></td>
                        <td>
                            <?php if ((int)$d['aktif'] === 1): ?>
                                <span class="badge bg-success">AKTİF</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">PASİF</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="../haberler/duyuru-detay.php?id=<?php echo $d['id']; ?>" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                    <li><a class="dropdown-item" href="fotograf-ekle.php?sayfa=duyuru-<?php echo $d['id']; ?>" target="_blank"><i class="bi bi-image-fill"></i> Resim Ekle</a></li>
                                    <li><a class="dropdown-item" href="duyuru-duzenle.php?id=<?php echo $d['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=duyuru&id=<?php echo $d['id']; ?>">
                                        <?php if ((int)$d['aktif'] === 1): ?>
                                            <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                        <?php else: ?>
                                            <i class="bi bi-eye-fill"></i> Aktif Yap
                                        <?php endif; ?>
                                    </a></li>
                                    <li><a class="dropdown-item" href="duyuru-sil.php?id=<?php echo $d['id']; ?>" onclick="return confirm('Bu duyuruyu silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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
