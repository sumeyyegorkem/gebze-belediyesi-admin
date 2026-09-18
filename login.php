<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: panel.php');
    exit;
}

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kullaniciAdi = trim($_POST['kullanici_adi'] ?? '');
    $sifre = $_POST['sifre'] ?? '';

    if ($kullaniciAdi === '' || $sifre === '') {
        $hataMesaji = 'Lütfen kullanıcı adı ve şifrenizi girin.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM yoneticiler WHERE kullanici_adi = :kullanici_adi");
        $stmt->execute(['kullanici_adi' => $kullaniciAdi]);
        $yonetici = $stmt->fetch();

        if ($yonetici && password_verify($sifre, $yonetici['sifre'])) {
            $_SESSION['admin_id'] = $yonetici['id'];
            $_SESSION['admin_ad'] = $yonetici['ad_soyad'];
            islemKaydet($pdo, 'Giriş Yaptı');
            header('Location: panel.php');
            exit;
        } else {
            $hataMesaji = 'Kullanıcı adı ya da şifre hatalı.';
        }
    }
}

// Hiç yönetici yoksa, ilk kurulum sayfasına yönlendirici bir uyarı gösterelim
$yoneticiSayisi = $pdo->query("SELECT COUNT(*) AS toplam FROM yoneticiler")->fetch()['toplam'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Girişi | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=63" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body class="admin-govde d-flex align-items-center" style="min-height:100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card admin-kart p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-lock-fill" style="font-size:2.5rem;color:var(--lacivert);"></i>
                    <h4 class="fw-bold mt-2 mb-0">Yönetici Girişi</h4>
                    <p class="text-muted small mb-0">Gebze Belediyesi Yönetim Paneli</p>
                </div>

                <?php if ($yoneticiSayisi == 0): ?>
                    <div class="alert alert-warning small">
                        Henüz hiç yönetici hesabı yok. <a href="ilk_kurulum.php">İlk kurulumu buradan yapabilirsiniz.</a>
                    </div>
                <?php endif; ?>

                <?php if ($hataMesaji): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($hataMesaji); ?></div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label class="form-label">Kullanıcı Adı</label>
                        <input type="text" name="kullanici_adi" class="form-control" required autofocus
                               value="<?php echo htmlspecialchars($_POST['kullanici_adi'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Şifre</label>
                        <input type="password" name="sifre" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-belediye w-100"><i class="bi bi-box-arrow-in-right me-1"></i> Giriş Yap</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
