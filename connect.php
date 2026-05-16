<?php
$servername = "localhost:3307";
$username = "root"; 
$password = ""; 
$dbname = "qlpt"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối Database thất bại: " . $conn->connect_error);
}
// Đã xóa dòng echo thông báo thành công ở đây để tránh làm lỗi giao diện chính
?>