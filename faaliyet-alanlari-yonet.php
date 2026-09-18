<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$kategoriler = $pdo->query("SELECT * FROM faaliyet_kategorileri ORDER BY sira ASC, id ASC")->fetchAll();
foreach ($kategoriler as &$kat) {
    $stmt = $pdo->prepare("SELECT * FROM faaliyet_ogeleri WHERE kategori_id = :id ORDER BY sira ASC, id ASC");
    $stmt->execute(['id' => $kat['id']]);
    $kat['ogeler'] = $stmt->fetchAll();
}
unset($kat);

$sayfaBasligi = 'Faaliyet Alanları';
$aktifMenu = 'faaliyet-alanlari';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Faaliyet Alanları Yönetimi</h4>
    <div class="d-flex gap-2">
        <a href="../genel/faaliyet-alanlari.php" target="_blank" class="btn-goruntule-kutu">Görüntüle</a>
        <a href="faaliyet-kategori-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Kategori Ekle</a>
    </div>
</div>

<?php if (isset($_GET['basarili'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>İşlem başarıyla tamamlandı.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (count($kategoriler) === 0): ?>
    <div class="card admin-kart"><div class="card-body text-center text-muted py-4">Henüz kategori eklenmemiş.</div></div>
<?php endif; ?>

<?php foreach ($kategoriler as $kat): ?>
<div class="card admin-kart mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-2">
                <span class="hizmet-kutu-admin" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:9px;background:var(--ay-mavi-bg,#eaf2ff);color:var(--ay-mavi-koyu,#2563eb);">
                    <i class="bi <?php echo htmlspecialchars($kat['ikon']); ?>"></i>
                </span>
                <div>
                    <span class="fw-bold"><?php echo htmlspecialchars($kat['baslik']); ?></span>
                    <span class="text-muted small d-block">Anahtar: <?php echo htmlspecialchars($kat['anahtar']); ?> &middot; Sıra: <?php echo (int)$kat['sira']; ?> &middot; <?php echo count($kat['ogeler']); ?> öge
                        <?php if ((int)$kat['aktif'] === 1): ?>
                            <span class="badge bg-success ms-1">AKTİF</span>
                        <?php else: ?>
                            <span class="badge bg-secondary ms-1">PASİF</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="faaliyet-oge-ekle.php?kategori_id=<?php echo $kat['id']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-plus-lg me-1"></i> Bu Kategoriye Öge Ekle</a>
                <div class="dropdown admin-islem-dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="../genel/faaliyet-alanlari.php" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                        <li><a class="dropdown-item" href="faaliyet-kategori-duzenle.php?id=<?php echo $kat['id']; ?>"><i class="bi bi-pencil-fill"></i> Kategoriyi Düzenle</a></li>
                        <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=faaliyet-kategori&id=<?php echo $kat['id']; ?>">
                            <?php if ((int)$kat['aktif'] === 1): ?>
                                <i class="bi bi-eye-slash-fill"></i> Pasife Al
                            <?php else: ?>
                                <i class="bi bi-eye-fill"></i> Aktif Yap
                            <?php endif; ?>
                        </a></li>
                        <li><a class="dropdown-item text-danger" href="faaliyet-kategori-sil.php?id=<?php echo $kat['id']; ?>" onclick="return confirm('Bu kategoriyi ve içindeki TÜM ögeleri silmek istediğinize emin misiniz? Bu işlem geri alınamaz.');"><i class="bi bi-trash-fill"></i> Kategoriyi Sil (ögeleriyle birlikte)</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fotoğraf</th>
                        <th>Başlık</th>
                        <th>Bağlantı Türü</th>
                        <th>Sıra</th>
                        <th>Durum</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($kat['ogeler']) === 0): ?>
                        <tr><td colspan="6" class="text-center text-muted py-3">Bu kategoride henüz öge eklenmemiş.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($kat['ogeler'] as $oge):
                        $gorselYolu = $oge['img'];
                        if ($gorselYolu !== '' && strpos($gorselYolu, 'http://') !== 0 && strpos($gorselYolu, 'https://') !== 0) {
                            $gorselYolu = '../' . $gorselYolu;
                        }
                    ?>
                        <tr>
                            <td>
                                <?php if (!empty($oge['img'])): ?>
                                    <img src="<?php echo htmlspecialchars($gorselYolu); ?>" class="admin-thumb" onerror="this.style.display='none';" alt="">
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($oge['baslik']); ?></td>
                            <td>
                                <?php if ((int)$oge['ic_baglanti'] === 1): ?>
                                    <span class="badge bg-info">Site-içi</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Dış bağlantı</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo (int)$oge['sira']; ?></td>
                            <td>
                                <?php if ((int)$oge['aktif'] === 1): ?>
                                    <span class="badge bg-success">AKTİF</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">PASİF</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="dropdown admin-islem-dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="../genel/faaliyet-alanlari.php" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                        <li><a class="dropdown-item" href="faaliyet-oge-duzenle.php?id=<?php echo $oge['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                        <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=faaliyet-oge&id=<?php echo $oge['id']; ?>">
                                            <?php if ((int)$oge['aktif'] === 1): ?>
                                                <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                            <?php else: ?>
                                                <i class="bi bi-eye-fill"></i> Aktif Yap
                                            <?php endif; ?>
                                        </a></li>
                                        <li><a class="dropdown-item text-danger" href="faaliyet-oge-sil.php?id=<?php echo $oge['id']; ?>" onclick="return confirm('Bu ögeyi silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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
<?php endforeach; ?>

<?php include 'includes/admin-bitis.php'; ?>
