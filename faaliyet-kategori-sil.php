<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT baslik FROM faaliyet_kategorileri WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekBaslik = $silinecek->fetchColumn();

    // Bu kategoriye bağlı tüm ögeler de birlikte silinir.
    $stmtOge = $pdo->prepare("DELETE FROM faaliyet_ogeleri WHERE kategori_id = :id");
    $stmtOge->execute(['id' => $id]);

    $stmtKat = $pdo->prepare("DELETE FROM faaliyet_kategorileri WHERE id = :id");
    $stmtKat->execute(['id' => $id]);

    if ($silinecekBaslik !== false) {
        islemKaydet($pdo, 'Faaliyet Kategorisi Sildi', $silinecekBaslik);
    }
}

header('Location: faaliyet-alanlari-yonet.php?basarili=1');
exit;
