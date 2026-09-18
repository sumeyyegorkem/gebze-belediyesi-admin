<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT baslik FROM e_belediye_bolumler WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekBaslik = $silinecek->fetchColumn();

    // Bu bölüme bağlı tüm hizmetler de birlikte silinir.
    $stmtHizmet = $pdo->prepare("DELETE FROM e_belediye_hizmetleri WHERE bolum_id = :id");
    $stmtHizmet->execute(['id' => $id]);

    $stmtBolum = $pdo->prepare("DELETE FROM e_belediye_bolumler WHERE id = :id");
    $stmtBolum->execute(['id' => $id]);

    if ($silinecekBaslik !== false) {
        islemKaydet($pdo, 'E-Belediye Bölümü Sildi', $silinecekBaslik);
    }
}

header('Location: e-belediye-yonet.php?basarili=1');
exit;
