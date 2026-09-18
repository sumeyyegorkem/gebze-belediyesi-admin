<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT ad FROM kardes_sehirler WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekAd = $silinecek->fetchColumn();

    $stmt = $pdo->prepare("DELETE FROM kardes_sehirler WHERE id = :id");
    $stmt->execute(['id' => $id]);

    if ($silinecekAd !== false) {
        islemKaydet($pdo, 'Kardeş Şehir Sildi', $silinecekAd);
    }
}

header('Location: kardes-sehir-yonet.php?basarili=1');
exit;
