<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT ad_soyad FROM eski_baskanlar WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekAdSoyad = $silinecek->fetchColumn();

    $sil = $pdo->prepare("DELETE FROM eski_baskanlar WHERE id = :id");
    $sil->execute(['id' => $id]);

    if ($silinecekAdSoyad !== false) {
        islemKaydet($pdo, 'Eski Başkan Sildi', $silinecekAdSoyad);
    }
}

header('Location: eski-baskan-yonet.php?basarili=1');
exit;
