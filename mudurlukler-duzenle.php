<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$yardimcilar = $pdo->query("SELECT * FROM baskan_yardimcilari ORDER BY sira ASC")->fetchAll();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM mudurlukler WHERE id = :id");
$stmt->execute(['id' => $id]);
$mudurluk = $stmt->fetch();

if (!$mudurluk) {
    header('Location: mudurlukler-yonet.php');
    exit;
}

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
        $guncelle = $pdo->prepare(
            "UPDATE mudurlukler
             SET ad = :ad, mudur = :mudur, eposta = :eposta, sayfa = :sayfa, aciklama = :aciklama,
                 foto = :foto, telefon = :telefon, adres = :adres, biyografi = :biyografi,
                 yonetmelik = :yonetmelik, baskan_yardimcisi_id = :baskan_yardimcisi_id, sira = :sira
             WHERE id = :id"
        );
        $guncelle->execute([
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
            'id' => $id,
        ]);

        islemKaydet($pdo, 'Müdürlük Düzenledi', $ad);
        header('Location: mudurlukler-yonet.php?basarili=1');
        exit;
    }
    $mudurluk = array_merge($mudurluk, $_POST);
}

$sayfaBasligi = 'Müdürlük Düzenle';
$aktifMenu = 'mudurlukler';
include 'includes/admin-baslangic.php';
?>

<div class="card admin-kart p-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>Müdürlük Düzenle</h4>

    <?php if ($hataMesaji): ?>
        <div class="alert alert-danger"><?php echo $hataMesaji; ?></div>
    <?php endif; ?>

    <form method="POST" action="mudurlukler-duzenle.php?id=<?php echo $id; ?>">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Müdürlük Adı *</label>
                <input type="text" name="ad" class="form-control" required
                       value="<?php echo htmlspecialchars($mudurluk['ad']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sıra</label>
                <input type="number" name="sira" class="form-control" value="<?php echo (int)$mudurluk['sira']; ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Müdür Adı</label>
                <input type="text" name="mudur" class="form-control"
                       value="<?php echo htmlspecialchars($mudurluk['mudur']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Bağlı Olduğu Başkan Yardımcısı</label>
                <select name="baskan_yardimcisi_id" class="form-select">
                    <option value="">Doğrudan Başkana Bağlı</option>
                    <?php foreach ($yardimcilar as $y): ?>
                        <option value="<?php echo $y['id']; ?>" <?php echo ((string)$mudurluk['baskan_yardimcisi_id'] === (string)$y['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($y['ad']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">E-posta</label>
                <input type="text" name="eposta" class="form-control"
                       value="<?php echo htmlspecialchars($mudurluk['eposta']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Telefon</label>
                <input type="text" name="telefon" class="form-control"
                       value="<?php echo htmlspecialchars($mudurluk['telefon']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">İlgili Hizmet Sayfası (dosya adı)</label>
                <input type="text" name="sayfa" class="form-control" placeholder="fen-isleri.php"
                       value="<?php echo htmlspecialchars($mudurluk['sayfa'] ?? ''); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Adres</label>
                <input type="text" name="adres" class="form-control"
                       value="<?php echo htmlspecialchars($mudurluk['adres']); ?>">
            </div>
            <div class="col-12">
                <?php if (!empty($mudurluk['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($mudurluk['foto']); ?>" class="mb-2 rounded-3" style="max-height:120px;" onerror="this.style.display='none';">
                <?php endif; ?>
                <label class="form-label d-block">Fotoğraf URL (müdür fotoğrafı)</label>
                <input type="text" name="foto" class="form-control" placeholder="https://..."
                       value="<?php echo htmlspecialchars($mudurluk['foto']); ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Açıklama</label>
                <textarea name="aciklama" rows="3" class="form-control"><?php echo htmlspecialchars($mudurluk['aciklama']); ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Müdür Biyografisi</label>
                <textarea name="biyografi" rows="5" class="form-control"><?php echo htmlspecialchars($mudurluk['biyografi']); ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Müdürlük Yönetmeliği</label>
                <textarea name="yonetmelik" rows="5" class="form-control"><?php echo htmlspecialchars($mudurluk['yonetmelik']); ?></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-belediye px-4"><i class="bi bi-save-fill me-1"></i> Güncelle</button>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/admin-bitis.php'; ?>

