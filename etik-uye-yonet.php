<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM etik_komisyonu_uyeleri WHERE ad_soyad LIKE :q ORDER BY sira ASC, id ASC");
    $stmt->execute(['q' => '%' . $arama . '%']);
    $uyeler = $stmt->fetchAll();
} else {
    $uyeler = $pdo->query("SELECT * FROM etik_komisyonu_uyeleri ORDER BY sira ASC, id ASC")->fetchAll();
}

$sayfaBasligi = 'Etik Komisyonu';
$aktifMenu = 'kurumsal-etik';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Etik Komisyonu Üyeleri</h4>
    <div class="d-flex gap-2">
        <a href="../kurumsal/etik-komisyonu.php" target="_blank" class="btn-goruntule-kutu">Görüntüle</a>
        <a href="etik-uye-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Üye Ekle</a>
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
                    <th>Ad Soyad</th>
                    <th>Ünvanı</th>
                    <th>Görevi</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($uyeler) === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen üye bulunamadı.' : 'Henüz üye eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($uyeler as $u): ?>
                    <tr>
                        <td><?php echo (int)$u['id']; ?></td>
                        <td><?php echo (int)$u['sira']; ?></td>
                        <td><?php echo htmlspecialchars($u['ad_soyad']); ?></td>
                        <td><?php echo htmlspecialchars($u['unvan']); ?></td>
                        <td><?php echo htmlspecialchars($u['gorev']); ?></td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="etik-uye-duzenle.php?id=<?php echo $u['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="etik-uye-sil.php?id=<?php echo $u['id']; ?>" onclick="return confirm('Bu üyeyi silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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
