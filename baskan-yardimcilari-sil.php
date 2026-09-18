<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT ad FROM baskan_yardimcilari WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekAd = $silinecek->fetchColumn();

    $sil = $pdo->prepare("DELETE FROM baskan_yardimcilari WHERE id = :id");
    $sil->execute(['id' => $id]);

    if ($silinecekAd !== false) {
        islemKaydet($pdo, 'Başkan Yardımcısı Sildi', $silinecekAd);
    }
}

header('Location: baskan-yardimcilari-yonet.php?basarili=1');
exit;
