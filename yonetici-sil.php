<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    if ($id === (int)($_SESSION['admin_id'] ?? 0)) {
        header('Location: yoneticiler-yonet.php?hata=kendi');
        exit;
    }

    $toplam = (int)$pdo->query("SELECT COUNT(*) AS toplam FROM yoneticiler")->fetch()['toplam'];
    if ($toplam <= 1) {
        header('Location: yoneticiler-yonet.php?hata=son');
        exit;
    }

    $silinecek = $pdo->prepare("SELECT ad_soyad FROM yoneticiler WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekAd = $silinecek->fetchColumn();

    $sil = $pdo->prepare("DELETE FROM yoneticiler WHERE id = :id");
    $sil->execute(['id' => $id]);

    if ($silinecekAd !== false) {
        islemKaydet($pdo, 'Yönetici Sildi', $silinecekAd);
    }
}

header('Location: yoneticiler-yonet.php?basarili=1');
exit;
