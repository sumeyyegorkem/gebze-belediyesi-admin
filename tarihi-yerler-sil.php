<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT baslik FROM tarihi_yerler WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekBaslik = $silinecek->fetchColumn();

    $sil = $pdo->prepare("DELETE FROM tarihi_yerler WHERE id = :id");
    $sil->execute(['id' => $id]);

    if ($silinecekBaslik !== false) {
        islemKaydet($pdo, 'Tarihi Yer Sildi', $silinecekBaslik);
    }
}

header('Location: tarihi-yerler-yonet.php?basarili=1');
exit;
