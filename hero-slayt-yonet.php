<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$slaytlar = $pdo->query("SELECT * FROM hero_slaytlari ORDER BY sira ASC, id ASC")->fetchAll();

$sayfaBasligi = 'Hero Slaytları';
$aktifMenu = 'hero-slaytlari';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-images me-2"></i>Hero Slaytları</h4>
    <a href="hero-slayt-ekle.php" class="btn btn-belediye"><i class="bi bi-plus-circle-fill me-1"></i> Yeni Slayt Ekle</a>
</div>

<?php if (isset($_GET['basarili'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        İşlem başarıyla tamamlandı.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card admin-kart p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Görsel</th>
                    <th>Başlık</th>
                    <th>Sıra</th>
                    <th>Durum</th>
                    <th class="text-end">İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($slaytlar) === 0): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Henüz slayt eklenmemiş.</td></tr>
                <?php endif; ?>
                <?php foreach ($slaytlar as $s): ?>
                <tr>
                    <td>
                        <img src="../<?php echo htmlspecialchars($s['resim_url']); ?>" style="width:90px;height:56px;object-fit:cover;border-radius:6px;" onerror="this.style.display='none';">
                    </td>
                    <td>
                        <?php echo htmlspecialchars($s['baslik_on']); ?><strong><?php echo htmlspecialchars($s['baslik_vurgu']); ?></strong><?php echo htmlspecialchars($s['baslik_son']); ?>
                    </td>
                    <td><?php echo (int)$s['sira']; ?></td>
                    <td>
                        <?php if ((int)$s['aktif'] === 1): ?>
                            <span class="badge bg-success">AKTİF</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">PASİF</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="../index.php" target="_blank"><i class="bi bi-eye-fill me-2"></i>Görüntüle</a></li>
                                <li><a class="dropdown-item" href="hero-slayt-duzenle.php?id=<?php echo $s['id']; ?>"><i class="bi bi-pencil-fill me-2"></i>Düzenle</a></li>
                                <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=hero-slayt&id=<?php echo $s['id']; ?>">
                                    <?php if ((int)$s['aktif'] === 1): ?><i class="bi bi-eye-slash-fill me-2"></i>Pasife Al<?php else: ?><i class="bi bi-eye-fill me-2"></i>Aktif Yap<?php endif; ?>
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="hero-slayt-sil.php?id=<?php echo $s['id']; ?>" onclick="return confirm('Bu slaytı silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill me-2"></i>Sil</a></li>
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
