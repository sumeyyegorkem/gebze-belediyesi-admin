<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM meclis_kararlari WHERE baslik LIKE :q ORDER BY yil DESC, ay DESC, id DESC");
    $stmt->execute(['q' => '%' . $arama . '%']);
    $kararlar = $stmt->fetchAll();
} else {
    $kararlar = $pdo->query("SELECT * FROM meclis_kararlari ORDER BY yil DESC, ay DESC, id DESC")->fetchAll();
}

$ayAdlari = ['01' => 'Ocak', '02' => 'Şubat', '03' => 'Mart', '04' => 'Nisan', '05' => 'Mayıs', '06' => 'Haziran',
             '07' => 'Temmuz', '08' => 'Ağustos', '09' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık'];

$sayfaBasligi = 'Meclis Kararları';
$aktifMenu = 'kurumsal-meclis-kararlari';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Meclis Kararları</h4>
    <div class="d-flex gap-2">
        <a href="../kurumsal/meclis-kararlari.php" target="_blank" class="btn-goruntule-kutu">Görüntüle</a>
        <a href="meclis-karari-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Karar Ekle</a>
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
                    <th>Başlık</th>
                    <th>Yıl / Ay</th>
                    <th>PDF</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($kararlar) === 0): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen karar bulunamadı.' : 'Henüz karar eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($kararlar as $k): ?>
                    <tr>
                        <td><?php echo (int)$k['id']; ?></td>
                        <td>
                            <?php echo htmlspecialchars($k['baslik']); ?>
                            <br><small class="text-muted"><?php echo htmlspecialchars($k['aciklama']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars(($ayAdlari[$k['ay']] ?? $k['ay']) . ' ' . $k['yil']); ?></td>
                        <td>
                            <?php if ($k['pdf_url']): ?>
                                <a href="<?php echo htmlspecialchars($k['pdf_url']); ?>" target="_blank"><i class="bi bi-file-earmark-pdf-fill text-danger"></i> Görüntüle</a>
                            <?php else: ?>
                                <span class="text-muted small">Bağlantı yok</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="meclis-karari-duzenle.php?id=<?php echo $k['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="meclis-karari-sil.php?id=<?php echo $k['id']; ?>" onclick="return confirm('Bu kararı silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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
