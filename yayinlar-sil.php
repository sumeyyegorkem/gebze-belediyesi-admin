<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT baslik FROM yayinlar WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekBaslik = $silinecek->fetchColumn();

    $sil = $pdo->prepare("DELETE FROM yayinlar WHERE id = :id");
    $sil->execute(['id' => $id]);

    if ($silinecekBaslik !== false) {
        islemKaydet($pdo, 'Yayın Sildi', $silinecekBaslik);
    }
}

header('Location: yayinlar-yonet.php?basarili=1');
exit;
