<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$yardimcilar = $pdo->query("SELECT * FROM baskan_yardimcilari ORDER BY sira ASC")->fetchAll();

$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $mudur = trim($_POST['mudur'] ?? '');
    $eposta = trim($_POST['eposta'] ?? '');
    $sayfa = trim($_POST['sayfa'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    $foto = trim($_POST['foto'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $adres = trim($_POST['adres'] ?? '');
    $biyografi = trim($_POST['biyografi'] ?? '');
    $yonetmelik = trim($_POST['yonetmelik'] ?? '');
    $baskanYardimcisiId = ($_POST['baskan_yardimcisi_id'] ?? '') !== '' ? (int)$_POST['baskan_yardimcisi_id'] : null;
    $sira = (int)($_POST['sira'] ?? 0);

    if ($ad === '') {
        $hataMesaji = 'Lütfen müdürlük adını doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO mudurlukler (ad, mudur, eposta, sayfa, aciklama, foto, telefon, adres, biyografi, yonetmelik, baskan_yardimcisi_id, sira)
             VALUES (:ad, :mudur, :eposta, :sayfa, :aciklama, :foto, :telefon, :adres, :biyografi, :yonetmelik, :baskan_yardimcisi_id, :sira)"
        );
        $stmt->execute([
            'ad' => $ad,
            'mudur' => $mudur,
            'eposta' => $eposta,
            'sayfa' => $sayfa !== '' ? $sayfa : null,
            'aciklama' => $aciklama,
            'foto' => $foto,
            'telefon' => $telefon,
            'adres' => $adres,
            'biyografi' => $biyografi,
            'yonetmelik' => $yonetmelik,
            'baskan_yardimcisi_id' => $baskanYardimcisiId,
            'sira' => $sira,
        ]);

        islemKaydet($pdo, 'Müdürlük Ekledi', $ad);
        header('Location: mudurlukler-yonet.php?basarili=1');
        exit;
    }
}

$sayfaBasligi = 'Yeni Müdürlük Ekle';
$aktifMenu = 'mudurlukler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle-fill me-2"></i>Yeni Müdürlük Ekle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="mudurlukler-ekle.php">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Müdürlük Adı *</label>
                <input type="text" name="ad" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['ad'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo htmlspecialchars($_POST['sira'] ?? '0'); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Müdür Adı</label>
                <input type="text" name="mudur" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['mudur'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Bağlı Olduğu Başkan Yardımcısı</label>
                <select name="baskan_yardimcisi_id" class="form-select">
                    <option value="">Doğrudan Başkana Bağlı</option>
                    <?php foreach ($yardimcilar as $y): ?>
                        <option value="<?php echo $y['id']; ?>"><?php echo htmlspecialchars($y['ad']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">E-posta</label>
                <input type="text" name="eposta" class="form-control" placeholder="ornek@gebze.bel.tr"
                       value="<?php echo htmlspecialchars($_POST['eposta'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Telefon</label>
                <input type="text" name="telefon" class="form-control" placeholder="0262 642 04 30"
                       value="<?php echo htmlspecialchars($_POST['telefon'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">İlgili Hizmet Sayfası (dosya adı)</label>
                <input type="text" name="sayfa" class="form-control" placeholder="fen-isleri.php"
                       value="<?php echo htmlspecialchars($_POST['sayfa'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Adres</label>
                <input type="text" name="adres" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['adres'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Fotoğraf URL (müdür fotoğrafı)</label>
                <input type="text" name="foto" class="form-control" placeholder="https://..."
                       value="<?php echo htmlspecialchars($_POST['foto'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Açıklama</label>
                <textarea name="aciklama" rows="3" class="form-control"><?php echo htmlspecialchars($_POST['aciklama'] ?? ''); ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Müdür Biyografisi</label>
                <textarea name="biyografi" rows="5" class="form-control"><?php echo htmlspecialchars($_POST['biyografi'] ?? ''); ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Müdürlük Yönetmeliği</label>
                <textarea name="yonetmelik" rows="5" class="form-control"><?php echo htmlspecialchars($_POST['yonetmelik'] ?? ''); ?></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Kaydet</button>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>

