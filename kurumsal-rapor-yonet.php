<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM kurumsal_raporlar WHERE baslik LIKE :q ORDER BY sira ASC, id ASC");
    $stmt->execute(['q' => '%' . $arama . '%']);
    $raporlar = $stmt->fetchAll();
} else {
    $raporlar = $pdo->query("SELECT * FROM kurumsal_raporlar ORDER BY sira ASC, id ASC")->fetchAll();
}

$sayfaBasligi = 'Kurumsal Raporlar';
$aktifMenu = 'kurumsal-raporlar';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Kurumsal Raporlar</h4>
    <div class="d-flex gap-2">
        <a href="../kurumsal/kurumsal-raporlar.php" target="_blank" class="btn-goruntule-kutu">Görüntüle</a>
        <a href="kurumsal-rapor-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Rapor Ekle</a>
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
                    <th>Başlık</th>
                    <th>Yayın Tarihi</th>
                    <th>PDF</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($raporlar) === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen rapor bulunamadı.' : 'Henüz rapor eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($raporlar as $r): ?>
                    <tr>
                        <td><?php echo (int)$r['id']; ?></td>
                        <td><?php echo (int)$r['sira']; ?></td>
                        <td><?php echo htmlspecialchars($r['baslik']); ?></td>
                        <td><?php echo htmlspecialchars($r['yayin_tarihi']); ?></td>
                        <td>
                            <?php if ($r['pdf_url']): ?>
                                <a href="<?php echo htmlspecialchars($r['pdf_url']); ?>" target="_blank"><i class="bi bi-file-earmark-pdf-fill text-danger"></i> Görüntüle</a>
                            <?php else: ?>
                                <span class="text-muted small">Bağlantı yok</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="kurumsal-rapor-duzenle.php?id=<?php echo $r['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="kurumsal-rapor-sil.php?id=<?php echo $r['id']; ?>" onclick="return confirm('Bu raporu silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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
