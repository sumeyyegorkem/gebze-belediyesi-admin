<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['admin_id'])) {
    islemKaydet($pdo, 'Çıkış Yaptı');
}

$_SESSION = [];
session_unset();
session_destroy();
header('Location: login.php');
exit;
