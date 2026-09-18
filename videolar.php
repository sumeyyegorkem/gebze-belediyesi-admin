<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM videolar WHERE baslik LIKE :q ORDER BY tarih DESC");
    $stmt->execute(['q' => '%' . $arama . '%']);
    $videolar = $stmt->fetchAll();
} else {
    $videolar = $pdo->query("SELECT * FROM videolar ORDER BY tarih DESC")->fetchAll();
}

$sayfaBasligi = 'Videolar';
$aktifMenu = 'videolar';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Video Yönetimi</h4>
    <a href="video-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Video Ekle</a>
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
                    <th>Önizleme</th>
                    <th>Başlık</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($videolar) === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen video bulunamadı.' : 'Henüz video eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($videolar as $v): ?>
                    <tr>
                        <td><?php echo (int)$v['id']; ?></td>
                        <td>
                            <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($v['youtube_id']); ?>/default.jpg"
                                 style="width:80px;border-radius:6px;">
                        </td>
                        <td><?php echo htmlspecialchars($v['baslik']); ?></td>
                        <td class="small text-muted"><?php echo date('d.m.Y', strtotime($v['tarih'])); ?></td>
                        <td>
                            <?php if ((int)$v['aktif'] === 1): ?>
                                <span class="badge bg-success">AKTİF</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">PASİF</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="../haberler/videolar.php" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                    <li><a class="dropdown-item" href="video-duzenle.php?id=<?php echo $v['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=video&id=<?php echo $v['id']; ?>">
                                        <?php if ((int)$v['aktif'] === 1): ?>
                                            <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                        <?php else: ?>
                                            <i class="bi bi-eye-fill"></i> Aktif Yap
                                        <?php endif; ?>
                                    </a></li>
                                    <li><a class="dropdown-item" href="video-sil.php?id=<?php echo $v['id']; ?>" onclick="return confirm('Bu videoyu silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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

