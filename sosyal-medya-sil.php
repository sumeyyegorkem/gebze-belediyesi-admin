<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT platform FROM sosyal_medya WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekPlatform = $silinecek->fetchColumn();

    $stmt = $pdo->prepare("DELETE FROM sosyal_medya WHERE id = :id");
    $stmt->execute(['id' => $id]);

    if ($silinecekPlatform !== false) {
        islemKaydet($pdo, 'Sosyal Medya Sildi', $silinecekPlatform);
    }
}

header('Location: sosyal-medya-yonet.php?basarili=1');
exit;
