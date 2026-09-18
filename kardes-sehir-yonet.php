<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$yurtIci = $pdo->query("SELECT * FROM kardes_sehirler WHERE tur = 'ici' ORDER BY sira ASC, id ASC")->fetchAll();
$yurtDisi = $pdo->query("SELECT * FROM kardes_sehirler WHERE tur = 'disi' ORDER BY sira ASC, id ASC")->fetchAll();

$sayfaBasligi = 'Kardeş Şehirler';
$aktifMenu = 'kardes-sehirler';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Kardeş Şehirler</h4>
    <a href="../gebze/kardes-sehirler.php" target="_blank" class="btn-goruntule-kutu">Görüntüle</a>
</div>

<?php if (isset($_GET['basarili'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>İşlem başarıyla tamamlandı.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card admin-kart mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-geo-alt-fill me-1 text-muted"></i> Yurt İçi</h6>
            <a href="kardes-sehir-ekle.php?tur=ici" class="btn btn-sm btn-outline-secondary"><i class="bi bi-plus-lg me-1"></i> Yurt İçi Şehir Ekle</a>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Belediye</th>
                        <th>İl</th>
                        <th style="width:70px;">Sıra</th>
                        <th>Durum</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($yurtIci) === 0): ?>
                        <tr><td colspan="5" class="text-center text-muted py-2 small">Henüz yurt içi kardeş şehir eklenmemiş.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($yurtIci as $k): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($k['ad']); ?></td>
                            <td><?php echo htmlspecialchars($k['il']); ?></td>
                            <td><?php echo (int)$k['sira']; ?></td>
                            <td>
                                <?php if ((int)$k['aktif'] === 1): ?>
                                    <span class="badge bg-success">AKTİF</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">PASİF</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="dropdown admin-islem-dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="../gebze/kardes-sehirler.php" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                        <li><a class="dropdown-item" href="kardes-sehir-duzenle.php?id=<?php echo $k['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                        <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=kardes-sehir&id=<?php echo $k['id']; ?>">
                                            <?php if ((int)$k['aktif'] === 1): ?>
                                                <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                            <?php else: ?>
                                                <i class="bi bi-eye-fill"></i> Aktif Yap
                                            <?php endif; ?>
                                        </a></li>
                                        <li><a class="dropdown-item text-danger" href="kardes-sehir-sil.php?id=<?php echo $k['id']; ?>" onclick="return confirm('Bu kardeş şehri silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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

<div class="card admin-kart mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-globe-americas me-1 text-muted"></i> Yurt Dışı</h6>
            <a href="kardes-sehir-ekle.php?tur=disi" class="btn btn-sm btn-outline-secondary"><i class="bi bi-plus-lg me-1"></i> Yurt Dışı Şehir Ekle</a>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Belediye / Şehir</th>
                        <th>Şehir</th>
                        <th>Ülke</th>
                        <th style="width:70px;">Sıra</th>
                        <th>Durum</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($yurtDisi) === 0): ?>
                        <tr><td colspan="6" class="text-center text-muted py-2 small">Henüz yurt dışı kardeş şehir eklenmemiş.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($yurtDisi as $k): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($k['ad']); ?></td>
                            <td><?php echo htmlspecialchars($k['sehir']); ?></td>
                            <td><?php echo htmlspecialchars($k['ulke']); ?></td>
                            <td><?php echo (int)$k['sira']; ?></td>
                            <td>
                                <?php if ((int)$k['aktif'] === 1): ?>
                                    <span class="badge bg-success">AKTİF</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">PASİF</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="dropdown admin-islem-dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="../gebze/kardes-sehirler.php" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                        <li><a class="dropdown-item" href="kardes-sehir-duzenle.php?id=<?php echo $k['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                        <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=kardes-sehir&id=<?php echo $k['id']; ?>">
                                            <?php if ((int)$k['aktif'] === 1): ?>
                                                <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                            <?php else: ?>
                                                <i class="bi bi-eye-fill"></i> Aktif Yap
                                            <?php endif; ?>
                                        </a></li>
                                        <li><a class="dropdown-item text-danger" href="kardes-sehir-sil.php?id=<?php echo $k['id']; ?>" onclick="return confirm('Bu kardeş şehri silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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
