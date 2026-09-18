<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';
require_once 'includes/yonetici-yardimci.php';

// Sistemdeki gerçek toplam yönetici sayısı (arama filtresinden bağımsız,
// "son yönetici silinemez" kontrolü için kullanılır)
$toplamYonetici = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM yoneticiler")->fetch()['toplam'];

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM yoneticiler WHERE ad_soyad LIKE :q1 OR kullanici_adi LIKE :q2 ORDER BY olusturma_tarihi ASC");
    $stmt->execute(['q1' => '%' . $arama . '%', 'q2' => '%' . $arama . '%']);
    $yoneticiler = $stmt->fetchAll();
} else {
    $yoneticiler = $pdo->query("SELECT * FROM yoneticiler ORDER BY olusturma_tarihi ASC")->fetchAll();
}

$mevcutId = (int)($_SESSION['admin_id'] ?? 0);

$sayfaBasligi = 'Yöneticiler';
$aktifMenu = 'yoneticiler';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Yönetici Hesapları</h4>
    <a href="yonetici-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Yönetici Ekle</a>
</div>

<?php if (isset($_GET['basarili'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>İşlem başarıyla tamamlandı.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['hata'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?php
            if ($_GET['hata'] === 'kendi') {
                echo 'Kendi hesabınızı silemezsiniz.';
            } elseif ($_GET['hata'] === 'son') {
                echo 'Sistemde en az bir yönetici hesabı kalmalıdır, bu yüzden son hesap silinemez.';
            } else {
                echo 'İşlem gerçekleştirilemedi.';
            }
        ?>
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
                    <th></th>
                    <th>Ad Soyad</th>
                    <th>Kullanıcı Adı</th>
                    <th>Oluşturma Tarihi</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($yoneticiler) === 0): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen yönetici bulunamadı.' : 'Henüz yönetici eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($yoneticiler as $y): ?>
                    <tr>
                        <td><?php echo adminAvatarHtml($y['ad_soyad'], $y['fotograf'] ?? null, $y['avatar_rengi'] ?? 'mavi', 'admin-tablo-avatar'); ?></td>
                        <td>
                            <?php echo htmlspecialchars($y['ad_soyad']); ?>
                            <?php if ((int)$y['id'] === $mevcutId): ?>
                                <span class="badge bg-info ms-1">Siz</span>
                            <?php endif; ?>
                            <?php if (!empty($y['unvan'])): ?>
                                <span class="d-block small text-muted"><?php echo htmlspecialchars($y['unvan']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($y['kullanici_adi']); ?></td>
                        <td><?php echo date('d.m.Y H:i', strtotime($y['olusturma_tarihi'])); ?></td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="yonetici-duzenle.php?id=<?php echo $y['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <?php if ((int)$y['id'] !== $mevcutId && $toplamYonetici > 1): ?>
                                        <li><a class="dropdown-item" href="yonetici-sil.php?id=<?php echo $y['id']; ?>" onclick="return confirm('Bu yöneticiyi silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
                                    <?php endif; ?>
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
