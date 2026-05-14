<?php
// Bắt buộc phải gọi session_start() trước khi muốn hủy nó
session_start();

// Xóa tất cả các biến trong session
session_unset(); 

// Hủy bỏ hoàn toàn session
session_destroy(); 

// Điều hướng người dùng quay lại trang chủ hoặc trang đăng nhập
header("Location: index.php");
exit();
?>