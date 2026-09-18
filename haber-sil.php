<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT baslik FROM haberler WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekBaslik = $silinecek->fetchColumn();

    $sil = $pdo->prepare("DELETE FROM haberler WHERE id = :id");
    $sil->execute(['id' => $id]);

    if ($silinecekBaslik !== false) {
        islemKaydet($pdo, 'Haber Sildi', $silinecekBaslik);
    }
}

header('Location: haberler-yonet.php?basarili=1');
exit;
