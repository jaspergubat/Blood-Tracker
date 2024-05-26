<?php
    session_start();
    include 'core/database.php';
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['account_type'] !== 'ADMIN') {
        header('Location: login.php');
        exit;
    }
    $title = 'Admin';
    $contentView = 'admin/index.php';
    include 'admin/index.php';
?>
