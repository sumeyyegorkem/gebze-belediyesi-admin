<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$arama = trim($_GET['q'] ?? '');
if ($arama !== '') {
    $stmt = $pdo->prepare("SELECT * FROM hizmet_kartlari WHERE baslik LIKE :q ORDER BY sira ASC, id ASC");
    $stmt->execute(['q' => '%' . $arama . '%']);
    $hizmetler = $stmt->fetchAll();
} else {
    $hizmetler = $pdo->query("SELECT * FROM hizmet_kartlari ORDER BY sira ASC, id ASC")->fetchAll();
}

// Kendi "Fotoğraf Galerisi" bölümü olan hizmet alt sayfaları (bkz. includes/fotograf-galerisi-bolum.php kullanımları)
$hizmetGaleriSayfalari = ['nikah-islemleri', 'fen-isleri', 'emlak-istimlak', 'kultur-sosyal-isler', 'temizlik-isleri', 'veteriner-hizmetleri', 'zabita'];

$sayfaBasligi = 'Hizmetler';
$aktifMenu = 'sabit-hizmetler';
include 'includes/admin-baslangic.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">Hizmet Kartları</h4>
    <div class="d-flex gap-2">
        <a href="../hizmetler/hizmetler.php" target="_blank" class="btn-goruntule-kutu">Görüntüle</a>
        <a href="hizmet-karti-ekle.php" class="btn btn-lacivert"><i class="bi bi-plus-lg me-1"></i> Yeni Hizmet Ekle</a>
    </div>
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
                    <th>Sıra</th>
                    <th>Görsel</th>
                    <th>Başlık</th>
                    <th>Özet</th>
                    <th>Durum</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($hizmetler) === 0): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">
                        <?php echo $arama !== '' ? 'Aramanızla eşleşen hizmet bulunamadı.' : 'Henüz hizmet eklenmemiş.'; ?>
                    </td></tr>
                <?php endif; ?>
                <?php foreach ($hizmetler as $h):
                    // "link" veritabanında bazen "hizmetler/x.php" (taşınmadan önceki köke göre
                    // yazılmış), bazen sadece "x.php" olarak saklanmış olabilir. Bu yüzden önce
                    // varsa "hizmetler/" önekini geçici olarak temizleyip asıl dosya adına bakıyoruz;
                    // böylece hem galeri eşleşmesi hem de aşağıdaki Görüntüle bağlantısı, link
                    // önekli ya da öneksiz kaydedilmiş olsa da doğru çalışır.
                    $hLinkOneksiz = $h['link'] ?: '';
                    if (strpos($hLinkOneksiz, 'hizmetler/') === 0) {
                        $hLinkOneksiz = substr($hLinkOneksiz, strlen('hizmetler/'));
                    }
                    $hLinkTemel = $hLinkOneksiz !== '' ? preg_replace('/\.php.*$/', '', $hLinkOneksiz) : '';
                    $hGaleriDestekli = in_array($hLinkTemel, $hizmetGaleriSayfalari, true);

                    if (!$h['link']) {
                        $hGoruntuleHref = '../hizmetler/hizmetler.php';
                    } elseif (strpos($h['link'], 'http') === 0) {
                        $hGoruntuleHref = $h['link'];
                    } elseif (strpos($h['link'], 'e-belediye.php') === 0) {
                        $hGoruntuleHref = '../e-belediye/' . $h['link'];
                    } elseif ($hGaleriDestekli) {
                        // Nikah İşlemleri, Fen İşleri, Zabıta gibi taşınan hizmet alt sayfaları
                        // artık hizmetler/ klasöründe yaşıyor; admin buradan bir üst dizinde
                        // olduğu için oraya inmemiz gerekir (aksi halde kök dizinde aranıp 404 verir).
                        $hGoruntuleHref = '../hizmetler/' . $hLinkOneksiz;
                    } else {
                        $hGoruntuleHref = '../' . $h['link'];
                    }
                ?>
                    <tr>
                        <td><?php echo (int)$h['id']; ?></td>
                        <td><?php echo (int)$h['sira']; ?></td>
                        <td><img src="../<?php echo htmlspecialchars($h['resim_url']); ?>" class="admin-thumb" alt="" onerror="this.src='https://placehold.co/80x60?text=Yok';"></td>
                        <td><i class="bi <?php echo htmlspecialchars($h['ikon']); ?> me-1"></i><?php echo htmlspecialchars($h['baslik']); ?></td>
                        <td class="small text-muted"><?php echo htmlspecialchars($h['ozet']); ?></td>
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
                                    <li><a class="dropdown-item" href="<?php echo htmlspecialchars($hGoruntuleHref); ?>" target="_blank"><i class="bi bi-eye-fill"></i> Görüntüle</a></li>
                                    <?php if ($hGaleriDestekli): ?>
                                    <li><a class="dropdown-item" href="fotograf-ekle.php?sayfa=<?php echo urlencode($hLinkTemel); ?>" target="_blank"><i class="bi bi-image-fill"></i> Resim Ekle</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item" href="hizmet-karti-duzenle.php?id=<?php echo $h['id']; ?>"><i class="bi bi-pencil-fill"></i> Düzenle</a></li>
                                    <li><a class="dropdown-item" href="hizli-durum-degistir.php?tur=hizmet-karti&id=<?php echo $h['id']; ?>">
                                        <?php if ((int)$h['aktif'] === 1): ?>
                                            <i class="bi bi-eye-slash-fill"></i> Pasife Al
                                        <?php else: ?>
                                            <i class="bi bi-eye-fill"></i> Aktif Yap
                                        <?php endif; ?>
                                    </a></li>
                                    <li><a class="dropdown-item" href="hizmet-karti-sil.php?id=<?php echo $h['id']; ?>" onclick="return confirm('Bu hizmeti silmek istediğinize emin misiniz?');"><i class="bi bi-trash-fill"></i> Sil</a></li>
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
