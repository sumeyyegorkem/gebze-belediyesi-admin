<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT ad FROM uye_birlikler WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekAd = $silinecek->fetchColumn();

    $stmt = $pdo->prepare("DELETE FROM uye_birlikler WHERE id = :id");
    $stmt->execute(['id' => $id]);

    if ($silinecekAd !== false) {
        islemKaydet($pdo, 'Üye Birlik Sildi', $silinecekAd);
    }
}

header('Location: uye-birlik-yonet.php?basarili=1');
exit;
