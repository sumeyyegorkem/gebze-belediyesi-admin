<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';
require_once 'includes/yonetici-yardimci.php';

$hataMesaji = '';
$secilenRenk = 'mavi';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $kullaniciAdi = trim($_POST['kullanici_adi'] ?? '');
    $unvan = trim($_POST['unvan'] ?? '');
    $secilenRenk = $_POST['avatar_rengi'] ?? 'mavi';
    $sifre = $_POST['sifre'] ?? '';
    $sifreTekrar = $_POST['sifre_tekrar'] ?? '';

    if (!array_key_exists($secilenRenk, adminAvatarRenkleri())) {
        $secilenRenk = 'mavi';
    }

    if ($adSoyad === '' || $kullaniciAdi === '' || $sifre === '') {
        $hataMesaji = 'Lütfen tüm alanları doldurun.';
    } elseif (strlen($sifre) < 6) {
        $hataMesaji = 'Şifre en az 6 karakter olmalı.';
    } elseif ($sifre !== $sifreTekrar) {
        $hataMesaji = 'Şifreler birbiriyle uyuşmuyor.';
    } else {
        $kontrol = $pdo->prepare("SELECT COUNT(*) AS toplam FROM yoneticiler WHERE kullanici_adi = :kullanici_adi");
        $kontrol->execute(['kullanici_adi' => $kullaniciAdi]);
        if ($kontrol->fetch()['toplam'] > 0) {
            $hataMesaji = 'Bu kullanıcı adı zaten kullanılıyor.';
        } else {
            $fotoYolu = null;
            if (isset($_FILES['fotograf']) && $_FILES['fotograf']['error'] === UPLOAD_ERR_OK) {
                $izinliUzantilar = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                $uzanti = strtolower(pathinfo($_FILES['fotograf']['name'], PATHINFO_EXTENSION));
                if (in_array($uzanti, $izinliUzantilar, true)) {
                    $yeniAd = 'yonetici_' . uniqid() . '.' . $uzanti;
                    if (move_uploaded_file($_FILES['fotograf']['tmp_name'], '../uploads/' . $yeniAd)) {
                        $fotoYolu = 'uploads/' . $yeniAd;
                    }
                }
            }

            $hashliSifre = password_hash($sifre, PASSWORD_DEFAULT);
            $ekle = $pdo->prepare(
                "INSERT INTO yoneticiler (kullanici_adi, sifre, ad_soyad, unvan, avatar_rengi, fotograf)
                 VALUES (:kullanici_adi, :sifre, :ad_soyad, :unvan, :avatar_rengi, :fotograf)"
            );
            $ekle->execute([
                'kullanici_adi' => $kullaniciAdi,
                'sifre' => $hashliSifre,
                'ad_soyad' => $adSoyad,
                'unvan' => ($unvan !== '' ? $unvan : null),
                'avatar_rengi' => $secilenRenk,
                'fotograf' => $fotoYolu,
            ]);

            islemKaydet($pdo, 'Yönetici Ekledi', $adSoyad);
            header('Location: yoneticiler-yonet.php?basarili=1');
            exit;
        }
    }
}

$sayfaBasligi = 'Yeni Yönetici Ekle';
$aktifMenu = 'yoneticiler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-person-plus-fill me-2"></i>Yeni Yönetici Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($hataMesaji); ?></div>
    <?php endif; ?>

    <form method="POST" action="yonetici-ekle.php" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Ad Soyad *</label>
                <input type="text" name="ad_soyad" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ad_soyad'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Unvan</label>
                <input type="text" name="unvan" class="form-control" placeholder="Örn. İçerik Editörü"
                       value="<?php echo htmlspecialchars($_POST['unvan'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Kullanıcı Adı *</label>
                <input type="text" name="kullanici_adi" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['kullanici_adi'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Profil Fotoğrafı</label>
                <input type="file" name="fotograf" class="form-control" accept="image/*">
            </div>
            <div class="col-md-6">
                <label class="form-label">Şifre * (en az 6 karakter)</label>
                <input type="password" name="sifre" class="form-control" required minlength="6">
            </div>
            <div class="col-md-6">
                <label class="form-label">Şifre (Tekrar) *</label>
                <input type="password" name="sifre_tekrar" class="form-control" required minlength="6">
            </div>
            <div class="col-12">
                <label class="form-label d-block">Avatar Rengi (fotoğraf yoksa kullanılır)</label>
                <?php echo adminRenkSeciciHtml($secilenRenk); ?>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
                <a href="yoneticiler-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
