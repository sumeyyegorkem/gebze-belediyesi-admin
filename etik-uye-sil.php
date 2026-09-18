<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT ad_soyad FROM etik_komisyonu_uyeleri WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekAdSoyad = $silinecek->fetchColumn();

    $sil = $pdo->prepare("DELETE FROM etik_komisyonu_uyeleri WHERE id = :id");
    $sil->execute(['id' => $id]);

    if ($silinecekAdSoyad !== false) {
        islemKaydet($pdo, 'Etik Komisyonu Üyesi Sildi', $silinecekAdSoyad);
    }
}

header('Location: etik-uye-yonet.php?basarili=1');
exit;
