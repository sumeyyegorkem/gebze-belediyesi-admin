<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$kategoriler = $pdo->query("SELECT * FROM e_belediye_kategoriler ORDER BY sira ASC, id ASC")->fetchAll();
foreach ($kategoriler as &$kat) {
    $stmtBolum = $pdo->prepare("SELECT * FROM e_belediye_bolumler WHERE kategori_id = :id ORDER BY sira ASC, id ASC");
    $stmtBolum->execute(['id' => $kat['id']]);
    $kat['bolumler'] = $stmtBolum->fetchAll();
    foreach ($kat['bolumler'] as &$bolum) {
        $stmtHizmet = $pdo->prepare("SELECT * FROM e_belediye_hizmetleri WHERE bolum_id = :id ORDER BY sira ASC, id ASC");
        $stmtHizmet->execute(['id' => $bolum['id']]);
        $bolum['hizmetler'] = $stmtHizmet->fetchAll();
    }
    unset($bolum);
}
unset($kat);

$sayfaBasligi = 'E-Belediye';
$aktifMenu = 'e-belediye';
include 'includes/admin-baslangic.php';

// Kategori sayfaları artık "e-belediye/" klasörü içinde yaşıyor, ama veritabanındaki
// hedef_sayfa değeri (config/e-belediye-seed-data.php'den geldiği için) klasör önekini
// içermiyor. "Görüntüle" linkinin doğru sayfayı açması için önek burada ekleniyor
// (değer zaten "e-belediye/" ile başlıyorsa tekrar eklenmiyor).
function gbEbGoruntuleLinki($hedefSayfa) {
    if (strpos($hedefSayfa, 'e-belediye/') === 0) {
        return '../' . $hedefSayfa;
    }
    return '../e-belediye/' . $hedefSayfa;
}
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">E-Belediye Yönetimi</h4>
    <a href="../e-belediye/e-belediye.php" target="_blank" class="btn-goruntule-kutu">Görüntüle</a>
</div>

<?php if (isset($_GET['basarili'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>İşlem başarıyla tamamlandı.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php foreach ($kategoriler as $kat):
    $toplamHizmet = 0;
    foreach ($kat['bolumler'] as $b) { $toplamHizmet += count($b['hizmetler']); }
?>
<div class="card admin-kart mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-2">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:9px;background:var(--ay-mavi-bg,#eaf2ff);color:var(--ay-mavi-koyu,#2563eb);">
                    <i class="bi <?php echo htmlspecialchars($kat['ikon']); ?>"></i>
                </span>
                <div>
                    <span class="fw-bold"><?php echo htmlspecialchars($kat['baslik']); ?></span>
                    <span class="text-muted small d-block">
                        <code><?php echo htmlspecialchars($kat['hedef_sayfa']); ?></code> &middot; <?php echo $toplamHizmet; ?> hizmet
                        <?php if ((int)$kat['aktif'] === 1): ?>
                            <span class="badge bg-success ms-1">AKTİF</span>
                        <?php else: ?>
                            <span class="badge bg-secondary ms-1">PASİF</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="e-belediye-bolum-ekle.php?kategori_id=<?php echo $kat['id']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-plus-lg me-1"></i> Bölüm Ekle</a>
                <a href="e-belediye-kategori-duzenle.php?id=<?php echo $kat['id']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-fill me-1"></i> Kategoriyi Düzenle</a>
                <div class="dropdown admin-islem-dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo htmlspecialchars(gbEbGoruntuleLinki($kat['hedef_sayfa'])); ?>" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                        <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=e-belediye-kategori&id=<?php echo $kat['id']; ?>">
                            <?php if ((int)$kat['aktif'] === 1): ?>
                                <i class="bi bi-eye-slash-fill"></i> Pasife Al
                            <?php else: ?>
                                <i class="bi bi-eye-fill"></i> Aktif Yap
                            <?php endif; ?>
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <?php if (count($kat['bolumler']) === 0): ?>
            <p class="text-muted small mb-0">Bu kategoride henüz bölüm eklenmemiş.</p>
        <?php endif; ?>

        <?php foreach ($kat['bolumler'] as $bolum): ?>
        <div class="border rounded-3 p-3 mb-3" style="background:#fafbfd;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <h6 class="fw-bold mb-0"><i class="bi bi-folder-fill me-1 text-muted"></i> <?php echo htmlspecialchars($bolum['baslik']); ?>
                    <?php if ((int)$bolum['aktif'] === 1): ?>
                        <span class="badge bg-success ms-1">AKTİF</span>
                    <?php else: ?>
                        <span class="badge bg-secondary ms-1">PASİF</span>
                    <?php endif; ?>
                </h6>
                <div class="d-flex gap-2">
                    <a href="e-belediye-hizmet-ekle.php?bolum_id=<?php echo $bolum['id']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-plus-lg me-1"></i> Hizmet Ekle</a>
                    <div class="dropdown admin-islem-dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Bölüm</button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="e-belediye-bolum-duzenle.php?id=<?php echo $bolum['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                            <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=e-belediye-bolum&id=<?php echo $bolum['id']; ?>">
                                <?php if ((int)$bolum['aktif'] === 1): ?>
                                    <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                <?php else: ?>
                                    <i class="bi bi-eye-fill"></i> Aktif Yap
                                <?php endif; ?>
                            </a></li>
                            <li><a class="dropdown-item text-danger" href="e-belediye-bolum-sil.php?id=<?php echo $bolum['id']; ?>" onclick="return confirm('Bu bölümü ve içindeki TÜM hizmetleri silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Bölümü Sil (hizmetleriyle birlikte)</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;"></th>
                            <th>Başlık</th>
                            <th>Bağlantı</th>
                            <th style="width:70px;">Sıra</th>
                            <th>Durum</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($bolum['hizmetler']) === 0): ?>
                            <tr><td colspan="6" class="text-center text-muted py-2 small">Bu bölümde henüz hizmet eklenmemiş.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($bolum['hizmetler'] as $h): ?>
                            <tr>
                                <td><i class="bi <?php echo htmlspecialchars($h['ikon']); ?> text-muted"></i></td>
                                <td><?php echo htmlspecialchars($h['baslik']); ?></td>
                                <td>
                                    <?php if (!empty($h['ozel_hedef'])): ?>
                                        <span class="badge bg-info"><i class="bi bi-lock-fill me-1"></i>Özel akış (modal)</span>
                                    <?php else: ?>
                                        <a href="<?php echo htmlspecialchars($h['href']); ?>" target="_blank" class="small text-truncate d-inline-block" style="max-width:280px;"><?php echo htmlspecialchars($h['href']); ?></a>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo (int)$h['sira']; ?></td>
                                <td>
                                    <?php if ((int)$h['aktif'] === 1): ?>
                                        <span class="badge bg-success">AKTİF</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">PASİF</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown admin-islem-dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="e-belediye-hizmet-duzenle.php?id=<?php echo $h['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                            <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=e-belediye-hizmet&id=<?php echo $h['id']; ?>">
                                                <?php if ((int)$h['aktif'] === 1): ?>
                                                    <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                                <?php else: ?>
                                                    <i class="bi bi-eye-fill"></i> Aktif Yap
                                                <?php endif; ?>
                                            </a></li>
                                            <li><a class="dropdown-item text-danger" href="e-belediye-hizmet-sil.php?id=<?php echo $h['id']; ?>" onclick="return confirm('Bu hizmeti silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>

<?php include 'includes/admin-bitis.php'; ?>
