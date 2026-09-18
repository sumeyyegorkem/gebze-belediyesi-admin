<?php
/**
 * admin/oturum_kontrol.php
 * -------------------------------------------------------
 * Bu dosya, admin klasöründeki HER sayfanın en başında
 * çağrılır. Kullanıcı giriş yapmamışsa login sayfasına atar.
 * "Session" (oturum), kullanıcının giriş yaptığını tarayıcı
 * kapanana kadar hatırlamamızı sağlayan bir mekanizmadır.
 * -------------------------------------------------------
 */
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
