<?php
$servername = "localhost:3306";
$username = "root"; 
$password = ""; 
$dbname = "ql"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối Database thất bại: " . $conn->connect_error);
}
?>