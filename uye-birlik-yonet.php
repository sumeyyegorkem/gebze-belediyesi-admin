<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$birlikler = $pdo->query("SELECT * FROM uye_birlikler ORDER BY sira ASC, id ASC")->fetchAll();

$sayfaBasligi = 'Üye Olduğumuz Birlikler';
$aktifMenu = 'uye-birlikler';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Üye Olduğumuz Birlikler</h4>
    <div class="d-flex gap-2">
        <a href="../gebze/uye-birlikler.php" target="_blank" class="btn-goruntule-kutu">Görüntüle</a>
        <a href="uye-birlik-ekle.php" class="btn btn-belediye"><i class="bi bi-plus-lg me-1"></i> Birlik Ekle</a>
    </div>
</div>

<?php if (isset($_GET['basarili'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>İşlem başarıyla tamamlandı.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card admin-kart">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Adı</th>
                        <th>Bağlantı</th>
                        <th style="width:70px;">Sıra</th>
                        <th>Durum</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($birlikler) === 0): ?>
                        <tr><td colspan="5" class="text-center text-muted py-2 small">Henüz birlik eklenmemiş.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($birlikler as $b): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($b['ad']); ?></td>
                            <td>
                                <?php if (!empty($b['url'])): ?>
                                    <a href="<?php echo htmlspecialchars($b['url']); ?>" target="_blank" class="small text-truncate d-inline-block" style="max-width:320px;"><?php echo htmlspecialchars($b['url']); ?></a>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo (int)$b['sira']; ?></td>
                            <td>
                                <?php if ((int)$b['aktif'] === 1): ?>
                                    <span class="badge bg-success">AKTİF</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">PASİF</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="dropdown admin-islem-dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="../gebze/uye-birlikler.php" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                        <li><a class="dropdown-item" href="uye-birlik-duzenle.php?id=<?php echo $b['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                        <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=uye-birlik&id=<?php echo $b['id']; ?>">
                                            <?php if ((int)$b['aktif'] === 1): ?>
                                                <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                            <?php else: ?>
                                                <i class="bi bi-eye-fill"></i> Aktif Yap
                                            <?php endif; ?>
                                        </a></li>
                                        <li><a class="dropdown-item text-danger" href="uye-birlik-sil.php?id=<?php echo $b['id']; ?>" onclick="return confirm('Bu birliği silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/admin-bitis.php'; ?>
