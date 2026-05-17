<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "qlpt"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối Database thất bại: " . $conn->connect_error);
}
?>