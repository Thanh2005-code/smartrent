<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {

    header("Location: ../login.php");
    exit();
}

include '../connect.php';

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $default_password = md5('123456');

    $stmt = $conn->prepare("
        UPDATE USER
        SET
            Password = ?,
            forgot_password = 0,
            reset_done = 1
        WHERE ID = ?
    ");

    $stmt->bind_param(
        "si",
        $default_password,
        $id
    );

    if ($stmt->execute()) {

        header("Location: account-profile.php?reset=123456");
    }

    else {

        header("Location: account-profile.php?reset=error");
    }
}
?>
