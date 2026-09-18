<?php
require_once 'oturum_kontrol.php';
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $silinecek = $pdo->prepare("SELECT baslik, sayfa FROM fotograf_galerisi WHERE id = :id");
    $silinecek->execute(['id' => $id]);
    $silinecekSatir = $silinecek->fetch();

    $sil = $pdo->prepare("DELETE FROM fotograf_galerisi WHERE id = :id");
    $sil->execute(['id' => $id]);

    if ($silinecekSatir) {
        $hedefAdi = !empty($silinecekSatir['baslik']) ? $silinecekSatir['baslik'] : $silinecekSatir['sayfa'];
        islemKaydet($pdo, 'Galeri Fotoğrafı Sildi', $hedefAdi);
    }
}

header('Location: fotograf-galerisi.php?basarili=1');
exit;
