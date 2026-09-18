<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$turler = [
    'Konser', 'Çocuk Sineması', 'Özel Program', 'Çocuk Etkinliği', 'Yetişkin Sineması',
    'Anma Programı', 'Off-Road', 'Sergi', 'Yetişkin Tiyatrosu', 'Çocuk Tiyatrosu',
    'Söyleşi', 'Stand-up', 'Panel', 'Şiir Dinletisi', 'Ramazan Özel Cami Programı',
    'Sirk Çocuk', 'Kandil Programı', 'Sohbet', 'Konferans',
];

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

    if ($resimUrl === '') {
        $resimUrl = 'https://placehold.co/500x300?text=Etkinlik';
    }

    if ($baslik === '' || $mekan === '' || $etkinlikTarihi === '') {
        $hataMesaji = 'Lütfen başlık, mekan ve tarih alanlarını doldurun.';
    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO etkinlikler (baslik, tur, mekan, etkinlik_tarihi, etkinlik_saati, resim_url, seo_baslik, seo_aciklama, seo_anahtar_kelimeler)
                 VALUES (:baslik, :tur, :mekan, :etkinlik_tarihi, :etkinlik_saati, :resim_url, :seo_baslik, :seo_aciklama, :seo_anahtar_kelimeler)"
            );
            $stmt->execute([
                'baslik' => $baslik,
                'tur' => $tur,
                'mekan' => $mekan,
                'etkinlik_tarihi' => $etkinlikTarihi,
                'etkinlik_saati' => $etkinlikSaati,
                'resim_url' => $resimUrl,
                'seo_baslik' => $seoBaslik !== '' ? $seoBaslik : null,
                'seo_aciklama' => $seoAciklama !== '' ? $seoAciklama : null,
                'seo_anahtar_kelimeler' => $seoAnahtar !== '' ? $seoAnahtar : null,
            ]);

            islemKaydet($pdo, 'Etkinlik Ekledi', $baslik);
            header('Location: etkinlik-yonet.php?basarili=1');
            exit;
        } catch (PDOException $e) {
            $hataMesaji = 'Kaydedilemedi. SEO alanları veritabanında henüz yoksa, önce "SEO Alanları Kurulumu" (admin/seo_migrasyon.php) sayfasını bir kez çalıştırın.';
        }
    }
}

$sayfaBasligi = 'Yeni Etkinlik Ekle';
$aktifMenu = 'etkinlikler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Etkinlik Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="etkinlik-ekle.php" enctype="multipart/form-data">
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
                               value="<?php echo htmlspecialchars($_POST['baslik'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tür</label>
                        <select name="tur" class="form-select">
                            <?php foreach ($turler as $t): ?>
                                <option value="<?php echo $t; ?>"><?php echo $t; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mekan *</label>
                        <input type="text" name="mekan" class="form-control" required
                               value="<?php echo htmlspecialchars($_POST['mekan'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tarih *</label>
                        <input type="date" name="etkinlik_tarihi" class="form-control" required
                               value="<?php echo htmlspecialchars($_POST['etkinlik_tarihi'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Saat</label>
                        <input type="text" name="etkinlik_saati" class="form-control" placeholder="21:30"
                               value="<?php echo htmlspecialchars($_POST['etkinlik_saati'] ?? ''); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Bilgisayardan Görsel Yükle (varsa URL'nin önüne geçer)</label>
                        <input type="file" name="resim_dosya" class="form-control" accept="image/*">
                    </div>
                    <div class="col-12">
                        <label class="form-label">ya da Görsel URL (ikisi de boşsa otomatik görsel kullanılır)</label>
                        <input type="text" name="resim_url" class="form-control" placeholder="https://..."
                               value="<?php echo htmlspecialchars($_POST['resim_url'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-seo" role="tabpanel">
                <p class="text-muted small">Bu alanlar boş bırakılırsa arama motorlarında tür, mekan ve tarih bilgisinden otomatik bir açıklama kullanılır.</p>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">SEO Başlık <span class="text-muted small">(önerilen: 50-60 karakter)</span></label>
                        <input type="text" name="seo_baslik" class="form-control" maxlength="70"
                               value="<?php echo htmlspecialchars($_POST['seo_baslik'] ?? ''); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">SEO Açıklama <span class="text-muted small">(önerilen: 120-160 karakter)</span></label>
                        <textarea name="seo_aciklama" rows="3" class="form-control" maxlength="160"><?php echo htmlspecialchars($_POST['seo_aciklama'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Anahtar Kelimeler <span class="text-muted small">(virgülle ayırın)</span></label>
                        <input type="text" name="seo_anahtar_kelimeler" class="form-control" placeholder="gebze, belediye, etkinlik"
                               value="<?php echo htmlspecialchars($_POST['seo_anahtar_kelimeler'] ?? ''); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
