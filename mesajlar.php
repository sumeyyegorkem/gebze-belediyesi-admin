<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$mesajlar = $pdo->query("SELECT * FROM iletisim_mesajlari ORDER BY gonderim_tarihi DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesajlar | Gebze Belediyesi</title>
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

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">İletişim Mesajları</h4>
    </div>

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
                        <th>Durum</th>
                        <th>Ad Soyad</th>
                        <th>Konu</th>
                        <th>Tarih</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($mesajlar) === 0): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Henüz mesaj yok.</td></tr>
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
                                <a href="mesaj-detay.php?id=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye-fill"></i> Görüntüle
                                </a>
                                <a href="mesaj-sil.php?id=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Bu mesajı silmek istediğinize emin misiniz?');">
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
