
<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header('Location: ../login.php');
    exit();
}

include '../connect.php';

$user_id = $_SESSION['user_id'];

$msg = "";
$msg_type = "";

if (isset($_POST['btn_change'])) {

    $old_password     = md5(trim($_POST['old_password']));
    $new_password     = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    if (
        empty($_POST['old_password']) ||
        empty($new_password) ||
        empty($confirm_password)
    ) {

        $msg = "Vui lòng nhập đầy đủ thông tin";
        $msg_type = "danger";
    }

    elseif ($new_password != $confirm_password) {

        $msg = "Mật khẩu xác nhận không khớp";
        $msg_type = "danger";
    }

    elseif (strlen($new_password) < 6) {

        $msg = "Mật khẩu mới phải từ 6 ký tự trở lên";
        $msg_type = "danger";
    }

    elseif ($old_password == md5($new_password)) {

        $msg = "Mật khẩu mới không được trùng mật khẩu cũ";
        $msg_type = "danger";
    }

    else {

        $stmt = $conn->prepare("
            SELECT Password
            FROM USER
            WHERE ID = ?
            LIMIT 1
        ");

        $stmt->bind_param("i", $user_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $user = $result->fetch_assoc();

            if ($user['Password'] != $old_password) {

                $msg = "Mật khẩu cũ không chính xác";
                $msg_type = "danger";
            }

            else {
                $new_password_md5 = md5($new_password);

                $update = $conn->prepare("
                    UPDATE USER
                    SET Password = ?
                    WHERE ID = ?
                ");

                $update->bind_param(
                    "si",
                    $new_password_md5,
                    $user_id
                );

                if ($update->execute()) {

                    $msg = "Đổi mật khẩu thành công. Đang chuyển về đăng nhập...";
                    $msg_type = "success";

                    session_destroy();

                    echo "
                    <script>

                        setTimeout(function(){

                            window.location.href='../login.php';

                        },2000);

                    </script>
                    ";
                }

                else {

                    $msg = "Có lỗi xảy ra";
                    $msg_type = "danger";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Đổi mật khẩu - SmartRent</title>

    <link rel="stylesheet"
          crossorigin
          href="assets/compiled/css/app.css">

    <link rel="stylesheet"
          crossorigin
          href="assets/compiled/css/app-dark.css">

    <link rel="stylesheet"
          crossorigin
          href="assets/compiled/css/iconly.css">

</head>

<body>

<script src="assets/static/js/initTheme.js"></script>

<div id="app">

    <!-- SIDEBAR -->
    <div id="sidebar">

        <div class="sidebar-wrapper active">

            <div class="sidebar-header position-relative">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="logo">

                        <a href="index.php">SMARTRENT</a>

                    </div>

                </div>

            </div>

            <div class="sidebar-menu">

                <ul class="menu">

                    <li class="sidebar-title">
                        Menu Quản trị
                    </li>

                    <li class="sidebar-item">
                        <a href="index.php" class="sidebar-link">

                            <i class="bi bi-grid-fill"></i>

                            <span>Bảng điều khiển</span>

                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="table-datatable.php"
                           class="sidebar-link">

                            <i class="bi bi-house-door-fill"></i>

                            <span>Quản lý phòng trọ</span>

                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="component-card.php"
                           class="sidebar-link">

                            <i class="bi bi-file-earmark-text-fill"></i>

                            <span>Quản lý tin đăng</span>

                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="account-profile.php"
                           class="sidebar-link">

                            <i class="bi bi-people-fill"></i>

                            <span>Quản lý tài khoản</span>

                        </a>
                    </li>

                    <li class="sidebar-item active">
                        <a href="doimatkhau.php"
                           class="sidebar-link">

                            <i class="bi bi-key-fill"></i>

                            <span>Đổi mật khẩu</span>

                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="../logout.php"
                           class="sidebar-link">

                            <i class="bi bi-box-arrow-right"></i>

                            <span>Đăng xuất</span>

                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </div>

    <!-- MAIN -->
    <div id="main">

        <header class="mb-3">

            <a href="#"
               class="burger-btn d-block d-xl-none">

                <i class="bi bi-justify fs-3"></i>

            </a>

        </header>

        <div class="page-heading">

            <h3>Đổi mật khẩu</h3>

        </div>

        <div class="page-content">

            <section class="row">

                <div class="col-md-6">

                    <div class="card">

                        <div class="card-header">

                            <h4>Thông tin mật khẩu</h4>

                        </div>

                        <div class="card-body">

                            <?php if (!empty($msg)) : ?>

                                <div class="alert alert-<?= $msg_type ?>">

                                    <?= $msg ?>

                                </div>

                            <?php endif; ?>

                            <form method="POST">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Mật khẩu cũ
                                    </label>

                                    <input type="password"
                                           name="old_password"
                                           class="form-control"
                                           required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Mật khẩu mới
                                    </label>

                                    <input type="password"
                                           name="new_password"
                                           class="form-control"
                                           required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Nhập lại mật khẩu mới
                                    </label>

                                    <input type="password"
                                           name="confirm_password"
                                           class="form-control"
                                           required>

                                </div>

                                <button type="submit"
                                        name="btn_change"
                                        class="btn btn-primary">

                                    Đổi mật khẩu

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </section>

        </div>

    </div>

</div>

<script src="assets/compiled/js/app.js"></script>

</body>
</html>

