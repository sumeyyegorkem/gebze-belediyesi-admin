<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT baslik FROM faaliyet_ogeleri WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekBaslik = $silinecek->fetchColumn();

    $stmt = $pdo->prepare("DELETE FROM faaliyet_ogeleri WHERE id = :id");
    $stmt->execute(['id' => $id]);

    if ($silinecekBaslik !== false) {
        islemKaydet($pdo, 'Faaliyet Öğesi Sildi', $silinecekBaslik);
    }
}

header('Location: faaliyet-alanlari-yonet.php?basarili=1');
exit;
