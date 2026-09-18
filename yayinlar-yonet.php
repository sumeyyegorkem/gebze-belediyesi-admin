<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM yayinlar WHERE baslik LIKE :q ORDER BY id DESC");
    $stmt->execute(['q' => '%' . $arama . '%']);
    $yayinlar = $stmt->fetchAll();
} else {
    $yayinlar = $pdo->query("SELECT * FROM yayinlar ORDER BY id DESC")->fetchAll();
}

$kategoriEtiket = ['kultur' => 'Kültür Yayınları', 'projeler' => 'Gebze Belediyesi Projeleri', 'manset' => 'Gebze Manşet'];

$sayfaBasligi = 'Yayınlar';
$aktifMenu = 'yayinlar';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Yayın Yönetimi</h4>
    <a href="yayinlar-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Yayın Ekle</a>
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
                    <th>Başlık</th>
                    <th>Kategori</th>
                    <th>Tarih</th>
                    <th>PDF</th>
                    <th>Durum</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($yayinlar) === 0): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen yayın bulunamadı.' : 'Henüz yayın eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($yayinlar as $y): ?>
                    <tr>
                        <td><?php echo (int)$y['id']; ?></td>
                        <td><?php echo htmlspecialchars($y['baslik']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($kategoriEtiket[$y['kategori']] ?? $y['kategori']); ?></span></td>
                        <td><?php echo htmlspecialchars($y['tarih']); ?></td>
                        <td>
                            <?php if ($y['pdf_url']): ?>
                                <a href="<?php echo htmlspecialchars($y['pdf_url']); ?>" target="_blank"><i class="bi bi-file-earmark-pdf-fill"></i> Bağlantı</a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
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
                                    <li><a class="dropdown-item" href="../kurumsal/yayinlar.php" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                    <li><a class="dropdown-item" href="yayinlar-duzenle.php?id=<?php echo $y['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=yayin&id=<?php echo $y['id']; ?>">
                                        <?php if ((int)$y['aktif'] === 1): ?>
                                            <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                        <?php else: ?>
                                            <i class="bi bi-eye-fill"></i> Aktif Yap
                                        <?php endif; ?>
                                    </a></li>
                                    <li><a class="dropdown-item" href="yayinlar-sil.php?id=<?php echo $y['id']; ?>" onclick="return confirm('Bu yayını silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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

