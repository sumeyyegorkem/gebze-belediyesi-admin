<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT ad FROM muhtarlar WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekAd = $silinecek->fetchColumn();

    $stmt = $pdo->prepare("DELETE FROM muhtarlar WHERE id = :id");
    $stmt->execute(['id' => $id]);

    if ($silinecekAd !== false) {
        islemKaydet($pdo, 'Muhtar Sildi', $silinecekAd);
    }
}

header('Location: muhtar-yonet.php?basarili=1');
exit;
