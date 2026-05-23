<?php
session_start();
include 'connect.php';

$error = '';
$success = '';

if (isset($_POST['btn_register'])) {
    
    // Lấy thông tin từ form
    $fullname         = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $email            = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone            = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $username         = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password_raw     = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_raw      = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Lấy giá trị vai trò (0: Người thuê, 1: Chủ trọ)
    $role             = isset($_POST['role']) ? (int)$_POST['role'] : 0;

    // Kiểm tra mật khẩu
    if ($password_raw != $confirm_raw) {
        $error = "Mật khẩu xác nhận không khớp!";
    } else {
        // Mã hóa MD5
        $password_md5 = md5($password_raw);

        // BẢO MẬT: Dùng Prepared Statement để kiểm tra trùng lặp
        $stmt_check = $conn->prepare("SELECT ID FROM user WHERE Username = ? OR Email = ?");
        $stmt_check->bind_param('ss', $username, $email);
        $stmt_check->execute();
        $check_result = $stmt_check->get_result();

        if ($check_result && $check_result->num_rows > 0) {
            $error = "Tên đăng nhập hoặc Email đã tồn tại. Vui lòng chọn tên khác!";
        } else {
            // BẢO MẬT: Dùng Prepared Statement để Thêm vào CSDL
            $stmt_insert = $conn->prepare("INSERT INTO user (Name, Username, Password, Email, Phone, Role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param('sssssi', $fullname, $username, $password_md5, $email, $phone, $role);
            
            if ($stmt_insert->execute()) {
                // Tự động chuyển hướng sau khi đăng ký thành công
                $success = "Đăng ký thành công! Đang chuyển hướng sang trang Đăng nhập trong 3 giây...";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 3000);
                      </script>";
            } else {
                $error = "Lỗi hệ thống, không thể đăng ký lúc này.";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
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
                        <!-- Đã kích hoạt hàm validateEmail -->
                        <input type="email" class="form-control form-control-xl" name="email" placeholder="Email" oninput="validateEmail(this)" required>
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

                    <div class="form-group position-relative mb-4">
                        <select name="role" class="form-select form-select-xl" style="height: calc(3rem + 2px); padding-left: 1.5rem; font-size: 1.2rem; border-radius: .5rem;" required>
                            <option value="" disabled selected>-- Chọn mục đích tham gia --</option>
                            <option value="0">Tôi muốn tìm phòng trọ (Người thuê)</option>
                            <option value="1">Tôi muốn đăng tin cho thuê (Chủ trọ)</option>
                        </select>
                    </div>

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
                    </div>

                    <button type="submit" name="btn_register" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Đăng ký ngay</button>
                </form>
                
                <div class="text-center mt-5 text-lg fs-5">
                    <p class='text-gray-600'>Đã có tài khoản? <a href="login.php" class="font-bold">Đăng nhập</a>.</p>
                    <p class="text-muted mt-3"><i>"Chủ trọ nhàn tay - Phòng đầy mỗi ngày"</i></p>
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