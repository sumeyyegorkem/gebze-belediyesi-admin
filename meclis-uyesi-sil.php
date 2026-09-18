<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT ad_soyad FROM meclis_uyeleri WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekAdSoyad = $silinecek->fetchColumn();

    $sil = $pdo->prepare("DELETE FROM meclis_uyeleri WHERE id = :id");
    $sil->execute(['id' => $id]);

    if ($silinecekAdSoyad !== false) {
        islemKaydet($pdo, 'Meclis Üyesi Sildi', $silinecekAdSoyad);
    }
}

header('Location: meclis-uyesi-yonet.php?basarili=1');
exit;
