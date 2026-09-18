<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$turler = [
    'Konser', 'Çocuk Sineması', 'Özel Program', 'Çocuk Etkinliği', 'Yetişkin Sineması',
    'Anma Programı', 'Off-Road', 'Sergi', 'Yetişkin Tiyatrosu', 'Çocuk Tiyatrosu',
    'Söyleşi', 'Stand-up', 'Panel', 'Şiir Dinletisi', 'Ramazan Özel Cami Programı',
    'Sirk Çocuk', 'Kandil Programı', 'Sohbet', 'Konferans',
];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Önce mevcut etkinliği getir
$stmt = $pdo->prepare("SELECT * FROM etkinlikler WHERE id = :id");
$stmt->execute(['id' => $id]);
$etkinlik = $stmt->fetch();

if (!$etkinlik) {
    header('Location: etkinlik-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik'] ?? '');
    $tur = trim($_POST['tur'] ?? 'Konser');
    $mekan = trim($_POST['mekan'] ?? '');
    $etkinlikTarihi = trim($_POST['etkinlik_tarihi'] ?? '');
    $etkinlikSaati = trim($_POST['etkinlik_saati'] ?? '');
    $resimUrl = trim($_POST['resim_url'] ?? '');
    $seoBaslik = trim($_POST['seo_baslik'] ?? '');
    $seoAciklama = trim($_POST['seo_aciklama'] ?? '');
    $seoAnahtar = trim($_POST['seo_anahtar_kelimeler'] ?? '');

    // Bilgisayardan dosya seçildiyse, URL'nin önüne geçer
    if (isset($_FILES['resim_dosya']) && $_FILES['resim_dosya']['error'] === UPLOAD_ERR_OK) {
        $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $uzanti = strtolower(pathinfo($_FILES['resim_dosya']['name'], PATHINFO_EXTENSION));
        if (in_array($uzanti, $izinliUzantilar)) {
            $yeniAd = 'etkinlik_' . uniqid() . '.' . $uzanti;
            if (move_uploaded_file($_FILES['resim_dosya']['tmp_name'], '../uploads/' . $yeniAd)) {
                $resimUrl = 'uploads/' . $yeniAd;
            }
        }
    }

    if ($baslik === '' || $mekan === '' || $etkinlikTarihi === '') {
        $hataMesaji = 'Lütfen başlık, mekan ve tarih alanlarını doldurun.';
    } else {
        try {
            $guncelle = $pdo->prepare(
                "UPDATE etkinlikler
                 SET baslik = :baslik, tur = :tur, mekan = :mekan,
                     etkinlik_tarihi = :etkinlik_tarihi, etkinlik_saati = :etkinlik_saati, resim_url = :resim_url,
                     seo_baslik = :seo_baslik, seo_aciklama = :seo_aciklama, seo_anahtar_kelimeler = :seo_anahtar_kelimeler
                 WHERE id = :id"
            );
            $guncelle->execute([
                'baslik' => $baslik,
                'tur' => $tur,
                'mekan' => $mekan,
                'etkinlik_tarihi' => $etkinlikTarihi,
                'etkinlik_saati' => $etkinlikSaati,
                'resim_url' => $resimUrl,
                'seo_baslik' => $seoBaslik !== '' ? $seoBaslik : null,
                'seo_aciklama' => $seoAciklama !== '' ? $seoAciklama : null,
                'seo_anahtar_kelimeler' => $seoAnahtar !== '' ? $seoAnahtar : null,
                'id' => $id,
            ]);

            islemKaydet($pdo, 'Etkinlik Düzenledi', $baslik);
            header('Location: etkinlik-yonet.php?basarili=1');
            exit;
        } catch (PDOException $e) {
            $hataMesaji = 'Güncellenemedi. SEO alanları veritabanında henüz yoksa, önce "SEO Alanları Kurulumu" (admin/seo_migrasyon.php) sayfasını bir kez çalıştırın.';
        }
    }
    // Hata varsa, ekranda gösterebilmek için formu POST verisiyle dolduralım
    $etkinlik = array_merge($etkinlik, $_POST);
}

$sayfaBasligi = 'Etkinlik Düzenle';
$aktifMenu = 'etkinlikler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Etkinlik Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="etkinlik-duzenle.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-genel" type="button" role="tab">Genel</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-seo" type="button" role="tab">Seo</button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-genel" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Başlık *</label>
                        <input type="text" name="baslik" class="form-control" required
                               value="<?php echo htmlspecialchars($etkinlik['baslik']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tür</label>
                        <select name="tur" class="form-select">
                            <?php foreach ($turler as $t): ?>
                                <option value="<?php echo $t; ?>" <?php echo ($etkinlik['tur'] === $t) ? 'selected' : ''; ?>>
                                    <?php echo $t; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mekan *</label>
                        <input type="text" name="mekan" class="form-control" required
                               value="<?php echo htmlspecialchars($etkinlik['mekan']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tarih *</label>
                        <input type="date" name="etkinlik_tarihi" class="form-control" required
                               value="<?php echo htmlspecialchars($etkinlik['etkinlik_tarihi']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Saat</label>
                        <input type="text" name="etkinlik_saati" class="form-control" placeholder="21:30"
                               value="<?php echo htmlspecialchars($etkinlik['etkinlik_saati']); ?>">
                    </div>
                    <div class="col-12">
                        <img src="../<?php echo htmlspecialchars($etkinlik['resim_url']); ?>" class="mb-2 rounded-3" style="max-height:120px;"
                             onerror="this.style.display='none';">
                        <label class="form-label d-block">Bilgisayardan Yeni Görsel Yükle (seçilirse mevcut görselin yerine geçer)</label>
                        <input type="file" name="resim_dosya" class="form-control" accept="image/*">
                    </div>
                    <div class="col-12">
                        <label class="form-label">ya da Görsel URL</label>
                        <input type="text" name="resim_url" class="form-control"
                               value="<?php echo htmlspecialchars($etkinlik['resim_url']); ?>">
                    </div>
                    <div class="col-12">
                        <div class="alert alert-light border d-flex justify-content-between align-items-center flex-wrap gap-2 mb-0">
                            <span class="small text-muted"><i class="bi bi-info-circle me-1"></i>Yukarısı sadece bu etkinliğin kendi görseli. Bu etkinliğe ait ayrı "Fotoğraf Galerisi" bölümüne fotoğraf eklemek için:</span>
                            <a href="fotograf-galerisi.php?sayfa=etkinlik-<?php echo $id; ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-images me-1"></i> Galeriye Fotoğraf Ekle</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-seo" role="tabpanel">
                <p class="text-muted small">Bu alanlar boş bırakılırsa arama motorlarında tür, mekan ve tarih bilgisinden otomatik bir açıklama kullanılır.</p>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">SEO Başlık <span class="text-muted small">(önerilen: 50-60 karakter)</span></label>
                        <input type="text" name="seo_baslik" class="form-control" maxlength="70"
                               value="<?php echo htmlspecialchars($etkinlik['seo_baslik'] ?? ''); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">SEO Açıklama <span class="text-muted small">(önerilen: 120-160 karakter)</span></label>
                        <textarea name="seo_aciklama" rows="3" class="form-control" maxlength="160"><?php echo htmlspecialchars($etkinlik['seo_aciklama'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Anahtar Kelimeler <span class="text-muted small">(virgülle ayırın)</span></label>
                        <input type="text" name="seo_anahtar_kelimeler" class="form-control" placeholder="gebze, belediye, etkinlik"
                               value="<?php echo htmlspecialchars($etkinlik['seo_anahtar_kelimeler'] ?? ''); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
