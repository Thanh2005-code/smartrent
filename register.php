<?php
session_start();
include 'connect.php';

$error = '';
$success = '';

if (isset($_POST['btn_register'])) {
    
    // Lấy thông tin từ form
    $fullname         = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $email            = isset($_POST['email']) ? $_POST['email'] : '';
    $phone            = isset($_POST['phone']) ? $_POST['phone'] : '';
    $username         = isset($_POST['username']) ? $_POST['username'] : '';
    $password_raw     = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_raw      = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Kiểm tra mật khẩu
    if ($password_raw != $confirm_raw) {
        $error = "Mật khẩu xác nhận không khớp!";
    } else {
        // Mã hóa MD5
        $password_md5 = md5($password_raw);

        // Kiểm tra trùng lặp
        $check_sql = "SELECT id FROM users WHERE username='$username' OR email='$email'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $error = "Tên đăng nhập hoặc Email đã tồn tại. Vui lòng chọn tên khác!";
        } else {
            // Thêm vào CSDL
            $insert_sql = "INSERT INTO users (fullname, username, password, email, phone, role) 
                           VALUES ('$fullname', '$username', '$password_md5', '$email', '$phone', 0)";
            
            if ($conn->query($insert_sql) === TRUE) {
                $success = "Đăng ký thành công! Vui lòng chuyển sang trang Đăng nhập.";
            } else {
                $error = "Lỗi hệ thống: " . $conn->error;
            }
        }
    }
    $conn->close();
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
                    <a href="index.php">
                        <h2 class="text-primary">SMARTRENT</h2>
                    </a>
                </div>

                <h1 class="auth-title">Đăng ký</h1>

                <p class="auth-subtitle mb-5">
                    Nhập thông tin để tạo tài khoản
                </p>

                    <form method="POST" action="">
                        
                        <?php 
                        if($error != '') echo "<div class='alert alert-danger'>$error</div>"; 
                        if($success != '') echo "<div class='alert alert-success'>$success</div>"; 
                        ?>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" name="fullname" placeholder="Họ và tên" required>
                            <div class="form-control-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email" class="form-control form-control-xl" name="email" placeholder="Email" required>
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" name="phone" placeholder="Số điện thoại" required>
                            <div class="form-control-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                        </div>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" name="username" placeholder="Tên đăng nhập" required>
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>

                    </div>

                    <!-- Password -->
                    <div class="form-group position-relative has-icon-left mb-4">

                        <input
                            type="password"
                            class="form-control form-control-xl"
                            name="password"
                            placeholder="Mật khẩu"
                            pattern="^(?=.{13,})(?=.*[\W])(?=.*[A-Z]).*$"
                            oninvalid="if(this.value==''){this.setCustomValidity('Mật khẩu không được để trống!');}else{this.setCustomValidity('Mật khẩu phải bắt đầu bằng chữ in hoa, lớn hơn 12 ký tự và có ít nhất 1 ký tự đặc biệt!');}"
                            oninput="this.setCustomValidity('')"
                            required
                        >

                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>

                    </div>

                    <!-- Nhập lại mật khẩu -->
                    <div class="form-group position-relative has-icon-left mb-4">

                        <input
                            type="password"
                            class="form-control form-control-xl"
                            name="confirm_password"
                            placeholder="Nhập lại mật khẩu"
                            oninvalid="if(this.value==''){this.setCustomValidity('Vui lòng nhập lại mật khẩu!');}"
                            oninput="this.setCustomValidity('')"
                            required
                        >

                        <div class="form-control-icon">
                            <i class="bi bi-shield-check"></i>
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

</div>
<script>

function validateEmail(input) {

    // Regex đúng định dạng abc@gmail.com
    let regex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

    // Nếu để trống
    if (input.value.trim() === '') {

        input.setCustomValidity('Email không được để trống!');

    }

    // Nếu sai định dạng
    else if (!regex.test(input.value)) {

        input.setCustomValidity('Email phải đúng định dạng abc@gmail.com!');

    }

    // Hợp lệ
    else {

        input.setCustomValidity('');

    }

    // Hiện thông báo ngay lập tức
    input.reportValidity();
}

</script>
</body>
</html>
