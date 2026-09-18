<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

// Durum güncelleme
if (isset($_GET['durum_guncelle'], $_GET['id'])) {
    $izinliDurumlar = ['beklemede', 'inceleniyor', 'tamamlandi'];
    $yeniDurum = $_GET['durum_guncelle'];
    $guncellenecekId = (int)$_GET['id'];
    if (in_array($yeniDurum, $izinliDurumlar, true) && $guncellenecekId > 0) {
        $durumEtiketleriLog = ['beklemede' => 'Beklemede', 'inceleniyor' => 'İnceleniyor', 'tamamlandi' => 'Tamamlandı'];
        $kayitStmt = $pdo->prepare("SELECT ad_soyad FROM zabita_ihbarlari WHERE id = :id");
        $kayitStmt->execute(['id' => $guncellenecekId]);
        $kayitAdi = $kayitStmt->fetchColumn();

        $guncelle = $pdo->prepare("UPDATE zabita_ihbarlari SET durum = :durum WHERE id = :id");
        $guncelle->execute(['durum' => $yeniDurum, 'id' => $guncellenecekId]);

        if ($kayitAdi !== false) {
            islemKaydet($pdo, 'Zabıta İhbarı Durumunu ' . ($durumEtiketleriLog[$yeniDurum] ?? $yeniDurum) . ' Yaptı', $kayitAdi ?: 'Anonim');
        }
    }
    header('Location: zabita-ihbarlar-yonet.php?basarili=1');
    exit;
}

// Silme
if (isset($_GET['sil'])) {
    $silinecekId = (int)$_GET['sil'];
    if ($silinecekId > 0) {
        $silinecekStmt = $pdo->prepare("SELECT ad_soyad FROM zabita_ihbarlari WHERE id = :id");
        $silinecekStmt->execute(['id' => $silinecekId]);
        $silinecekAdi = $silinecekStmt->fetchColumn();

        $sil = $pdo->prepare("DELETE FROM zabita_ihbarlari WHERE id = :id");
        $sil->execute(['id' => $silinecekId]);

        if ($silinecekAdi !== false) {
            islemKaydet($pdo, 'Zabıta İhbarı Sildi', $silinecekAdi ?: 'Anonim');
        }
    }
    header('Location: zabita-ihbarlar-yonet.php?basarili=1');
    exit;
}

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM zabita_ihbarlari WHERE ad_soyad LIKE :q1 OR mahalle LIKE :q2 OR konu LIKE :q3 ORDER BY gonderim_tarihi DESC");
    $stmt->execute(['q1' => '%' . $arama . '%', 'q2' => '%' . $arama . '%', 'q3' => '%' . $arama . '%']);
    $ihbarlar = $stmt->fetchAll();
} else {
    $ihbarlar = $pdo->query("SELECT * FROM zabita_ihbarlari ORDER BY gonderim_tarihi DESC")->fetchAll();
}

$durumEtiket = ['beklemede' => 'Beklemede', 'inceleniyor' => 'İnceleniyor', 'tamamlandi' => 'Tamamlandı'];
$durumRenk = ['beklemede' => 'bg-danger', 'inceleniyor' => 'bg-warning text-dark', 'tamamlandi' => 'bg-success'];

$sayfaBasligi = 'Zabıta İhbarları';
$aktifMenu = 'zabita-ihbarlar';
include 'includes/admin-baslangic.php';
?>

<h4 class="fw-bold mb-3">Zabıta İhbarları</h4>

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
                    <th>Bildiren</th>
                    <th>Telefon</th>
                    <th>Mahalle</th>
                    <th>Konu</th>
                    <th>Açıklama</th>
                    <th>Durum</th>
                    <th>Tarih</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($ihbarlar) === 0): ?>
                    <tr><td colspan="9" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen ihbar bulunamadı.' : 'Henüz ihbar yok.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($ihbarlar as $t): ?>
                    <tr>
                        <td><?php echo (int)$t['id']; ?></td>
                        <td>
                            <?php if ($t['anonim']): ?>
                                <span class="badge bg-secondary">Anonim</span>
                            <?php else: ?>
                                <?php echo htmlspecialchars($t['ad_soyad'] ?: '-'); ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($t['telefon'] ?: '-'); ?></td>
                        <td><?php echo htmlspecialchars($t['mahalle']); ?></td>
                        <td><?php echo htmlspecialchars($t['konu']); ?></td>
                        <td style="max-width:220px;"><?php echo nl2br(htmlspecialchars($t['aciklama'])); ?></td>
                        <td><span class="badge <?php echo $durumRenk[$t['durum']] ?? 'bg-secondary'; ?>"><?php echo $durumEtiket[$t['durum']] ?? $t['durum']; ?></span></td>
                        <td><?php echo htmlspecialchars($t['gonderim_tarihi']); ?></td>
                        <td class="text-end">
                            <div class="dropdown admin-islem-dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">İşlem</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="?durum_guncelle=beklemede&id=<?php echo $t['id']; ?>"><i class="bi bi-circle-fill text-danger"></i> Beklemede</a></li>
                                    <li><a class="dropdown-item" href="?durum_guncelle=inceleniyor&id=<?php echo $t['id']; ?>"><i class="bi bi-circle-fill text-warning"></i> İnceleniyor</a></li>
                                    <li><a class="dropdown-item" href="?durum_guncelle=tamamlandi&id=<?php echo $t['id']; ?>"><i class="bi bi-circle-fill text-success"></i> Tamamlandı</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="?sil=<?php echo $t['id']; ?>" onclick="return confirm('Bu ihbarı silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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

