<?php
/**
 * admin/index.php
 * -------------------------------------------------------
 * Bu dosya olmadan /admin/ klasörüne doğrudan girildiğinde Apache,
 * klasördeki tüm dosyaların isim listesini (dizin listelemesi) gösteriyordu.
 * Bunun yerine ziyaretçiyi doğrudan giriş sayfasına yönlendiriyoruz —
 * zaten giriş yapmışsa login.php kendisi panel.php'ye yönlendirir.
 * -------------------------------------------------------
 */
header('Location: login.php');
exit;
