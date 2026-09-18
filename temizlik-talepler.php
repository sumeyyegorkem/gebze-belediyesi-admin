<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

// Durum güncelleme
if (isset($_GET['durum_guncelle'], $_GET['id'])) {
    $izinliDurumlar = ['beklemede', 'inceleniyor', 'tamamlandi'];
    $yeniDurum = $_GET['durum_guncelle'];
    $guncellenecekId = (int)$_GET['id'];
    if (in_array($yeniDurum, $izinliDurumlar, true) && $guncellenecekId > 0) {
        $guncelle = $pdo->prepare("UPDATE temizlik_talepleri SET durum = :durum WHERE id = :id");
        $guncelle->execute(['durum' => $yeniDurum, 'id' => $guncellenecekId]);
    }
    header('Location: temizlik-talepler.php?basarili=1');
    exit;
}

// Silme
if (isset($_GET['sil'])) {
    $silinecekId = (int)$_GET['sil'];
    if ($silinecekId > 0) {
        $sil = $pdo->prepare("DELETE FROM temizlik_talepleri WHERE id = :id");
        $sil->execute(['id' => $silinecekId]);
    }
    header('Location: temizlik-talepler.php?basarili=1');
    exit;
}

$talepler = $pdo->query("SELECT * FROM temizlik_talepleri ORDER BY gonderim_tarihi DESC")->fetchAll();

$durumEtiket = ['beklemede' => 'Beklemede', 'inceleniyor' => 'İnceleniyor', 'tamamlandi' => 'Tamamlandı'];
$durumRenk = ['beklemede' => 'bg-danger', 'inceleniyor' => 'bg-warning text-dark', 'tamamlandi' => 'bg-success'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temizlik Talepleri | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=60" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body class="admin-govde">

<nav class="navbar admin-navbar navbar-dark mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1"><i class="bi bi-speedometer2 me-2"></i>Yönetim Paneli</span>
        <a href="panel.php" class="btn btn-sm btn-outline-light"><i class="bi bi-arrow-left"></i> Panele Dön</a>
    </div>
</nav>

<div class="container pb-5">

    <h4 class="fw-bold mb-3">Temizlik Talepleri</h4>

    <?php if (isset($_GET['basarili'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>İşlem başarıyla tamamlandı.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card admin-kart">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Ad Soyad</th>
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
                    <?php if (count($talepler) === 0): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Henüz talep yok.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($talepler as $t): ?>
                        <tr>
                            <td><?php echo (int)$t['id']; ?></td>
                            <td><?php echo htmlspecialchars($t['ad_soyad']); ?></td>
                            <td><?php echo htmlspecialchars($t['telefon']); ?></td>
                            <td><?php echo htmlspecialchars($t['mahalle']); ?></td>
                            <td><?php echo htmlspecialchars($t['konu']); ?></td>
                            <td style="max-width:220px;"><?php echo nl2br(htmlspecialchars($t['aciklama'])); ?></td>
                            <td><span class="badge <?php echo $durumRenk[$t['durum']] ?? 'bg-secondary'; ?>"><?php echo $durumEtiket[$t['durum']] ?? $t['durum']; ?></span></td>
                            <td><?php echo htmlspecialchars($t['gonderim_tarihi']); ?></td>
                            <td class="text-end">
                                <div class="btn-group mb-1">
                                    <a href="?durum_guncelle=beklemede&id=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-danger">Beklemede</a>
                                    <a href="?durum_guncelle=inceleniyor&id=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-warning">İnceleniyor</a>
                                    <a href="?durum_guncelle=tamamlandi&id=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-success">Tamamlandı</a>
                                </div>
                                <br>
                                <a href="?sil=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-secondary"
                                   onclick="return confirm('Bu talebi silmek istediğinize emin misiniz?');">
                                    <i class="bi bi-trash-fill"></i> Sil
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
