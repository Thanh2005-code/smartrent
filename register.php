<?php
session_start();
include 'connect.php';

$error = '';
$success = '';

if (isset($_POST['btn_register'])) {
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password_raw = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_raw = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if ($password_raw != $confirm_raw) {
        $error = 'Mật khẩu xác nhận không khớp!';
    } elseif ($fullname === '' || $email === '' || $username === '') {
        $error = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
    } else {
        $password_md5 = md5($password_raw);

        $chk = $conn->prepare('SELECT ID FROM user WHERE Username = ? OR Email = ? LIMIT 1');
        if ($chk) {
            $chk->bind_param('ss', $username, $email);
            $chk->execute();
            $dup = $chk->get_result();
            if ($dup->num_rows > 0) {
                $error = 'Tên đăng nhập hoặc Email đã tồn tại. Vui lòng chọn tên khác!';
            } else {
                $ins = $conn->prepare('INSERT INTO user (Name, Username, Email, Password, Role, Phone, Avatar) VALUES (?, ?, ?, ?, 0, ?, NULL)');
                if ($ins) {
                    $ins->bind_param('sssss', $fullname, $username, $email, $password_md5, $phone);
                    if ($ins->execute()) {
                        $success = 'Đăng ký thành công! Vui lòng chuyển sang trang Đăng nhập.';
                    } else {
                        $error = 'Lỗi hệ thống: ' . $conn->error;
                    }
                    $ins->close();
                } else {
                    $error = 'Lỗi hệ thống: ' . $conn->error;
                }
            }
            $chk->close();
        } else {
            $error = 'Lỗi hệ thống: ' . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Smartrent Dashboard</title>

    <link rel="stylesheet" crossorigin href="admin/assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="admin/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="admin/assets/compiled/css/auth.css">
</head>

<body>
    <script src="admin/assets/static/js/initTheme.js"></script>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-6 col-12 mx-auto">
                <div id="auth-left">
                    <div class="auth-logo mb-4">
                        <a href="index.php"><h2 class="text-primary">SMARTRENT</h2></a>
                    </div>
                    <h1 class="auth-title">Đăng ký.</h1>
                    <p class="auth-subtitle mb-5">Nhập thông tin để tìm kiếm phòng trọ ưng ý.</p>

                    <form method="POST" action="">

                        <?php
                        if ($error != '') {
                            echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . '</div>';
                        }
                        if ($success != '') {
                            echo "<div class='alert alert-success'>" . htmlspecialchars($success) . '</div>';
                        }
                        ?>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" name="fullname" placeholder="Họ và tên" required value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                            <div class="form-control-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email" class="form-control form-control-xl" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" name="phone" placeholder="Số điện thoại" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            <div class="form-control-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" name="username" placeholder="Tên đăng nhập" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" name="password" placeholder="Mật khẩu" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                        </div>

                        <button type="submit" name="btn_register" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Đăng ký ngay</button>
                    </form>

                    <div class="text-center mt-5 text-lg fs-5">
                        <p class='text-gray-600'>Đã có tài khoản? <a href="login.php" class="font-bold">Đăng nhập</a>.</p>
                        <p class="text-muted mt-3"><i>"Chủ trọ nhàn tay phòng đầy mỗi ngày"</i></p>
                    </div>
                </div>
            </div>
            </div>
    </div>
</body>
</html>
