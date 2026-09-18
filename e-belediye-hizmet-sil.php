<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT baslik FROM e_belediye_hizmetleri WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekBaslik = $silinecek->fetchColumn();

    $stmt = $pdo->prepare("DELETE FROM e_belediye_hizmetleri WHERE id = :id");
    $stmt->execute(['id' => $id]);

    if ($silinecekBaslik !== false) {
        islemKaydet($pdo, 'E-Belediye Hizmeti Sildi', $silinecekBaslik);
    }
}

header('Location: e-belediye-yonet.php?basarili=1');
exit;
