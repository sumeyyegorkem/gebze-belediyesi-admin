<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$q = trim($_GET['q'] ?? '');
$aramaMetni = '%' . $q . '%';

$haberSonuc = $duyuruSonuc = $etkinlikSonuc = $projeSonuc = $mesajSonuc = $yoneticiSonuc = [];

if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM haberler WHERE baslik LIKE :q1 OR kategori LIKE :q2 ORDER BY yayin_tarihi DESC");
    $stmt->execute(['q1' => $aramaMetni, 'q2' => $aramaMetni]);
    $haberSonuc = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT * FROM duyurular WHERE baslik LIKE :q1 OR ozet LIKE :q2 OR icerik LIKE :q3 ORDER BY yayin_tarihi DESC");
    $stmt->execute(['q1' => $aramaMetni, 'q2' => $aramaMetni, 'q3' => $aramaMetni]);
    $duyuruSonuc = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT * FROM etkinlikler WHERE baslik LIKE :q1 OR mekan LIKE :q2 OR tur LIKE :q3 ORDER BY etkinlik_tarihi DESC");
    $stmt->execute(['q1' => $aramaMetni, 'q2' => $aramaMetni, 'q3' => $aramaMetni]);
    $etkinlikSonuc = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT * FROM projeler WHERE baslik LIKE :q1 OR aciklama LIKE :q2 ORDER BY olusturma_tarihi DESC");
    $stmt->execute(['q1' => $aramaMetni, 'q2' => $aramaMetni]);
    $projeSonuc = $stmt->fetchAll();

    try {
        $stmt = $pdo->prepare("SELECT * FROM iletisim_mesajlari WHERE ad_soyad LIKE :q1 OR konu LIKE :q2 ORDER BY gonderim_tarihi DESC");
        $stmt->execute(['q1' => $aramaMetni, 'q2' => $aramaMetni]);
        $mesajSonuc = $stmt->fetchAll();
    } catch (Exception $e) {
        $mesajSonuc = [];
    }

    $stmt = $pdo->prepare("SELECT * FROM yoneticiler WHERE ad_soyad LIKE :q1 OR kullanici_adi LIKE :q2 ORDER BY ad_soyad ASC");
    $stmt->execute(['q1' => $aramaMetni, 'q2' => $aramaMetni]);
    $yoneticiSonuc = $stmt->fetchAll();
}

$toplamSonuc = count($haberSonuc) + count($duyuruSonuc) + count($etkinlikSonuc)
    + count($projeSonuc) + count($mesajSonuc) + count($yoneticiSonuc);

$sayfaBasligi = 'Arama Sonuçları';
$aktifMenu = '';
include 'includes/admin-baslangic.php';
?>

<h4 class="fw-bold mb-1"><i class="bi bi-search me-2"></i>Arama Sonuçları</h4>

<?php if ($q === ''): ?>
    <p class="text-muted">Aramak istediğiniz kelimeyi üst bardaki arama kutusuna yazıp Enter'a basın.</p>

<?php elseif ($toplamSonuc === 0): ?>
    <p class="text-muted mb-4">"<strong><?php echo htmlspecialchars($q); ?></strong>" için sonuç bulunamadı.</p>

<?php else: ?>
    <p class="text-muted mb-4">"<strong><?php echo htmlspecialchars($q); ?></strong>" için <?php echo $toplamSonuc; ?> sonuç bulundu.</p>

    <?php if (count($haberSonuc) > 0): ?>
    <div class="card admin-kart mb-4">
        <div class="card-body">
            <div class="admin-panel-baslik">Haberler <span class="text-muted fw-normal">(<?php echo count($haberSonuc); ?>)</span></div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        <?php foreach ($haberSonuc as $h): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($h['resim_url']); ?>" class="admin-thumb" alt=""></td>
                                <td><?php echo htmlspecialchars($h['baslik']); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($h['kategori']); ?></span></td>
                                <td class="text-end"><a href="haber-duzenle.php?id=<?php echo $h['id']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-fill"></i> Düzenle</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (count($duyuruSonuc) > 0): ?>
    <div class="card admin-kart mb-4">
        <div class="card-body">
            <div class="admin-panel-baslik">Duyurular <span class="text-muted fw-normal">(<?php echo count($duyuruSonuc); ?>)</span></div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        <?php foreach ($duyuruSonuc as $d): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($d['resim_url']); ?>" class="admin-thumb" alt=""></td>
                                <td><?php echo htmlspecialchars($d['baslik']); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($d['kategori']); ?></span></td>
                                <td class="text-end"><a href="duyuru-duzenle.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-fill"></i> Düzenle</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (count($etkinlikSonuc) > 0): ?>
    <div class="card admin-kart mb-4">
        <div class="card-body">
            <div class="admin-panel-baslik">Etkinlikler <span class="text-muted fw-normal">(<?php echo count($etkinlikSonuc); ?>)</span></div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        <?php foreach ($etkinlikSonuc as $e): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($e['resim_url']); ?>" class="admin-thumb" alt=""></td>
                                <td><?php echo htmlspecialchars($e['baslik']); ?></td>
                                <td><?php echo htmlspecialchars($e['mekan']); ?></td>
                                <td class="text-end"><a href="etkinlik-duzenle.php?id=<?php echo $e['id']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-fill"></i> Düzenle</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (count($projeSonuc) > 0): ?>
    <div class="card admin-kart mb-4">
        <div class="card-body">
            <div class="admin-panel-baslik">Projeler <span class="text-muted fw-normal">(<?php echo count($projeSonuc); ?>)</span></div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        <?php foreach ($projeSonuc as $p): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($p['resim_url']); ?>" class="admin-thumb" alt=""></td>
                                <td><?php echo htmlspecialchars($p['baslik']); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($p['kategori'] ?? ''); ?></span></td>
                                <td class="text-end"><a href="projeler-duzenle.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-fill"></i> Düzenle</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (count($mesajSonuc) > 0): ?>
    <div class="card admin-kart mb-4">
        <div class="card-body">
            <div class="admin-panel-baslik">Mesajlar <span class="text-muted fw-normal">(<?php echo count($mesajSonuc); ?>)</span></div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        <?php foreach ($mesajSonuc as $m): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($m['ad_soyad']); ?></td>
                                <td><?php echo htmlspecialchars($m['konu']); ?></td>
                                <td class="text-end"><a href="mesaj-detay.php?id=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye-fill"></i> Görüntüle</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (count($yoneticiSonuc) > 0): ?>
    <div class="card admin-kart mb-4">
        <div class="card-body">
            <div class="admin-panel-baslik">Yöneticiler <span class="text-muted fw-normal">(<?php echo count($yoneticiSonuc); ?>)</span></div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        <?php foreach ($yoneticiSonuc as $y): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($y['ad_soyad']); ?></td>
                                <td><?php echo htmlspecialchars($y['kullanici_adi']); ?></td>
                                <td class="text-end"><a href="yonetici-duzenle.php?id=<?php echo $y['id']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-fill"></i> Düzenle</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

<?php endif; ?>

<?php include 'includes/admin-bitis.php'; ?>
