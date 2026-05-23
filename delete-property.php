<?php
session_start(); 

include 'connect.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
if (isset($_GET['id'])) {
    $motel_id = (int)$_GET['id'];
    $user_id = (int)$_SESSION['user_id'];

    $sql = "DELETE FROM motel WHERE ID = ? AND user_id = ?";

    $tmp = $conn->prepare($sql); 
 
    $tmp->bind_param('ii', $motel_id, $user_id); 
    $tmp->execute();

    if ($tmp == true) { 
        echo "<script>alert('Xóa dữ liệu thành công'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Xóa dữ liệu không thành công: " . $conn->error . "'); window.location.href='profile.php';</script>";
    }
    $tmp->close(); 
    $conn->close(); 
} else {
    header('Location: profile.php');
    exit();
}
?>