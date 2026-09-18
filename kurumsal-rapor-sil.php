<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT baslik FROM kurumsal_raporlar WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekBaslik = $silinecek->fetchColumn();

    $sil = $pdo->prepare("DELETE FROM kurumsal_raporlar WHERE id = :id");
    $sil->execute(['id' => $id]);

    if ($silinecekBaslik !== false) {
        islemKaydet($pdo, 'Kurumsal Rapor Sildi', $silinecekBaslik);
    }
}

header('Location: kurumsal-rapor-yonet.php?basarili=1');
exit;
