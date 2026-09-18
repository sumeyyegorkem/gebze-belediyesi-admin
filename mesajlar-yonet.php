<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM iletisim_mesajlari WHERE ad_soyad LIKE :q1 OR konu LIKE :q2 ORDER BY gonderim_tarihi DESC");
    $stmt->execute(['q1' => '%' . $arama . '%', 'q2' => '%' . $arama . '%']);
    $mesajlar = $stmt->fetchAll();
} else {
    $mesajlar = $pdo->query("SELECT * FROM iletisim_mesajlari ORDER BY gonderim_tarihi DESC")->fetchAll();
}

$sayfaBasligi = 'Mesajlar';
$aktifMenu = 'mesajlar';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">İletişim Mesajları</h4>
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
                    <th>Durum</th>
                    <th>Ad Soyad</th>
                    <th>Konu</th>
                    <th>Tarih</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($mesajlar) === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen mesaj bulunamadı.' : 'Henüz mesaj yok.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($mesajlar as $m): ?>
                    <tr class="<?php echo $m['okundu'] ? '' : 'fw-bold'; ?>">
                        <td><?php echo (int)$m['id']; ?></td>
                        <td>
                            <?php if ($m['okundu']): ?>
                                <span class="badge bg-secondary">Okundu</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Yeni</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($m['ad_soyad']); ?></td>
                        <td><?php echo htmlspecialchars($m['konu']); ?></td>
                        <td><?php echo htmlspecialchars($m['gonderim_tarihi']); ?></td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="mesaj-detay.php?id=<?php echo $m['id']; ?>"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                    <li><a class="dropdown-item" href="mesaj-sil.php?id=<?php echo $m['id']; ?>" onclick="return confirm('Bu mesajı silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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

