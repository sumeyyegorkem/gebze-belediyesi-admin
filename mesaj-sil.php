<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT ad_soyad, konu FROM iletisim_mesajlari WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekSatir = $silinecek->fetch();

    $sil = $pdo->prepare("DELETE FROM iletisim_mesajlari WHERE id = :id");
    $sil->execute(['id' => $id]);

    if ($silinecekSatir) {
        islemKaydet($pdo, 'Mesajı Sildi', $silinecekSatir['ad_soyad'] . ' - ' . $silinecekSatir['konu']);
    }
}

header('Location: mesajlar-yonet.php?basarili=1');
exit;
