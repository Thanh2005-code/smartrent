<?php
session_start();

// Kiểm tra: Nếu chưa đăng nhập HOẶC không phải là Admin (role khác 1)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    // Lập tức đuổi ra ngoài trang đăng nhập
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản lý Smartrent</title>
    
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="assets/compiled/css/iconly.css">
</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="app">
        
        <?php include 'sidebar.php'; ?>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>