<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT baslik_on FROM hero_slaytlari WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekBaslik = $silinecek->fetchColumn();

    $stmt = $pdo->prepare("DELETE FROM hero_slaytlari WHERE id = :id");
    $stmt->execute(['id' => $id]);

    if ($silinecekBaslik !== false) {
        islemKaydet($pdo, 'Hero Slayt Sildi', $silinecekBaslik);
    }
}

header('Location: hero-slayt-yonet.php?basarili=1');
exit;
