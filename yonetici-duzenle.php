<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';
require_once 'includes/yonetici-yardimci.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM yoneticiler WHERE id = :id");
$stmt->execute(['id' => $id]);
$yonetici = $stmt->fetch();

if (!$yonetici) {
    header('Location: yoneticiler-yonet.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $kullaniciAdi = trim($_POST['kullanici_adi'] ?? '');
    $unvan = trim($_POST['unvan'] ?? '');
    $avatarRengi = $_POST['avatar_rengi'] ?? 'mavi';
    $sifre = $_POST['sifre'] ?? '';
    $sifreTekrar = $_POST['sifre_tekrar'] ?? '';
    $fotoSil = isset($_POST['foto_sil']);

    if (!array_key_exists($avatarRengi, adminAvatarRenkleri())) {
        $avatarRengi = 'mavi';
    }

    if ($adSoyad === '' || $kullaniciAdi === '') {
        $hataMesaji = 'Lütfen ad soyad ve kullanıcı adını doldurun.';
    } elseif ($sifre !== '' && strlen($sifre) < 6) {
        $hataMesaji = 'Yeni şifre en az 6 karakter olmalı.';
    } elseif ($sifre !== $sifreTekrar) {
        $hataMesaji = 'Şifreler birbiriyle uyuşmuyor.';
    } else {
        $kontrol = $pdo->prepare("SELECT COUNT(*) AS toplam FROM yoneticiler WHERE kullanici_adi = :kullanici_adi AND id != :id");
        $kontrol->execute(['kullanici_adi' => $kullaniciAdi, 'id' => $id]);
        if ($kontrol->fetch()['toplam'] > 0) {
            $hataMesaji = 'Bu kullanıcı adı başka bir yönetici tarafından kullanılıyor.';
        } else {
            $fotoYolu = $yonetici['fotograf'];
            if ($fotoSil) {
                $fotoYolu = null;
            }
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

            $parametreler = [
                'ad_soyad' => $adSoyad,
                'kullanici_adi' => $kullaniciAdi,
                'unvan' => ($unvan !== '' ? $unvan : null),
                'avatar_rengi' => $avatarRengi,
                'fotograf' => $fotoYolu,
                'id' => $id,
            ];

            if ($sifre !== '') {
                $guncelle = $pdo->prepare(
                    "UPDATE yoneticiler SET ad_soyad = :ad_soyad, kullanici_adi = :kullanici_adi, unvan = :unvan,
                     avatar_rengi = :avatar_rengi, fotograf = :fotograf, sifre = :sifre WHERE id = :id"
                );
                $parametreler['sifre'] = password_hash($sifre, PASSWORD_DEFAULT);
            } else {
                $guncelle = $pdo->prepare(
                    "UPDATE yoneticiler SET ad_soyad = :ad_soyad, kullanici_adi = :kullanici_adi, unvan = :unvan,
                     avatar_rengi = :avatar_rengi, fotograf = :fotograf WHERE id = :id"
                );
            }
            $guncelle->execute($parametreler);

            // Kendi hesabını düzenliyorsa oturumdaki görünen adı da güncelle
            if ($id === (int)($_SESSION['admin_id'] ?? 0)) {
                $_SESSION['admin_ad'] = $adSoyad;
            }

            islemKaydet($pdo, 'Yönetici Düzenledi', $adSoyad);
            header('Location: yoneticiler-yonet.php?basarili=1');
            exit;
        }
    }
    $yonetici['ad_soyad'] = $adSoyad;
    $yonetici['kullanici_adi'] = $kullaniciAdi;
    $yonetici['unvan'] = $unvan;
    $yonetici['avatar_rengi'] = $avatarRengi;
}

$sayfaBasligi = 'Yönetici Düzenle';
$aktifMenu = 'yoneticiler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Yönetici Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($hataMesaji); ?></div>
    <?php endif; ?>

    <form method="POST" action="yonetici-duzenle.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <?php echo adminAvatarHtml($yonetici['ad_soyad'], $yonetici['fotograf'], $yonetici['avatar_rengi'], 'admin-profil-avatar'); ?>
                <div class="mt-3">
                    <label class="form-label small">Profil Fotoğrafı</label>
                    <input type="file" name="fotograf" class="form-control form-control-sm" accept="image/*">
                    <?php if (!empty($yonetici['fotograf'])): ?>
                        <div class="form-check mt-2 text-start">
                            <input class="form-check-input" type="checkbox" name="foto_sil" id="fotoSil">
                            <label class="form-check-label small" for="fotoSil">Fotoğrafı kaldır</label>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Ad Soyad *</label>
                        <input type="text" name="ad_soyad" class="form-control" required
                               value="<?php echo htmlspecialchars($yonetici['ad_soyad']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Unvan</label>
                        <input type="text" name="unvan" class="form-control" placeholder="Örn. İçerik Editörü"
                               value="<?php echo htmlspecialchars($yonetici['unvan'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kullanıcı Adı *</label>
                        <input type="text" name="kullanici_adi" class="form-control" required
                               value="<?php echo htmlspecialchars($yonetici['kullanici_adi']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label d-block">Avatar Rengi</label>
                        <?php echo adminRenkSeciciHtml($yonetici['avatar_rengi']); ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Yeni Şifre</label>
                        <input type="password" name="sifre" class="form-control" minlength="6" placeholder="Boş bırakırsanız değişmez">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Yeni Şifre (Tekrar)</label>
                        <input type="password" name="sifre_tekrar" class="form-control" minlength="6">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
                        <a href="yoneticiler-yonet.php" class="btn btn-outline-secondary px-4">Vazgeç</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>
