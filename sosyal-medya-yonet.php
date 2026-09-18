<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$hesaplar = $pdo->query("SELECT * FROM sosyal_medya ORDER BY sira ASC, id ASC")->fetchAll();

$sayfaBasligi = 'Sosyal Medya Hesapları';
$aktifMenu = 'sosyal-medya';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-share-fill me-2"></i>Sosyal Medya Hesapları</h4>
    <a href="sosyal-medya-ekle.php" class="btn btn-belediye"><i class="bi bi-plus-circle-fill me-1"></i> Yeni Hesap Ekle</a>
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
                    <th>İkon</th>
                    <th>Platform</th>
                    <th>Bağlantı</th>
                    <th>Sıra</th>
                    <th>Durum</th>
                    <th class="text-end">İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($hesaplar) === 0): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Henüz hesap eklenmemiş.</td></tr>
                <?php endif; ?>
                <?php foreach ($hesaplar as $h): ?>
                <tr>
                    <td><i class="bi <?php echo htmlspecialchars($h['ikon']); ?> fs-4"></i></td>
                    <td><?php echo htmlspecialchars($h['platform']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($h['url']); ?>" target="_blank" class="small"><?php echo htmlspecialchars($h['url']); ?></a></td>
                    <td><?php echo (int)$h['sira']; ?></td>
                    <td>
                        <?php if ((int)$h['aktif'] === 1): ?>
                            <span class="badge bg-success">AKTİF</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">PASİF</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo htmlspecialchars($h['url']); ?>" target="_blank"><i class="bi bi-eye-fill me-2"></i>Görüntüle</a></li>
                                <li><a class="dropdown-item" href="sosyal-medya-duzenle.php?id=<?php echo $h['id']; ?>"><i class="bi bi-pencil-fill me-2"></i>Düzenle</a></li>
                                <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=sosyal-medya&id=<?php echo $h['id']; ?>">
                                    <?php if ((int)$h['aktif'] === 1): ?><i class="bi bi-eye-slash-fill me-2"></i>Pasife Al<?php else: ?><i class="bi bi-eye-fill me-2"></i>Aktif Yap<?php endif; ?>
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="sosyal-medya-sil.php?id=<?php echo $h['id']; ?>" onclick="return confirm('Bu hesabı silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill me-2"></i>Sil</a></li>
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
