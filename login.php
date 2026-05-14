<?php
session_start();
include 'connect.php'; 

$error = '';
if (isset($_POST['btn_login'])) {
    
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password_raw = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Mã hóa mật khẩu người dùng vừa nhập để đem đi so sánh
    $password_md5 = md5($password_raw);

    // Truy vấn kiểm tra với mật khẩu đã mã hóa
    $sql = "SELECT id, fullname, role FROM users WHERE username='$username' AND password='$password_md5'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['fullname'] = $row['fullname'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == 1) {
            header("Location: admin/index.php"); 
        } else {
            header("Location: index.php"); 
        }
        exit();
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu!";
    }
    
    // Đóng kết nối
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Smartrent Dashboard</title>
    
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
                    <div class="auth-logo">
                        <a href="index.php"><h2 class="text-primary">SMARTRENT</h2></a>
                    </div>
                    <h1 class="auth-title">Đăng nhập.</h1>
                    <p class="auth-subtitle mb-5">Hệ thống quản lý phòng trọ.</p>

                    <form method="POST" action="">
                        
                        <?php if($error != '') echo "<div class='alert alert-danger'>$error</div>"; ?>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" name="username" placeholder="Tên đăng nhập" required>
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
                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>
                        <button type="submit" name="btn_login" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Đăng nhập</button>
                    </form>
                    
                    <div class="text-center mt-5 text-lg fs-5">
                        <p class="text-gray-600">Chưa có tài khoản? <a href="register.php" class="font-bold">Đăng ký ngay</a>.</p>
                        <p class="text-muted mt-3"><i>"Chủ trọ nhàn tay phòng đầy mỗi ngày"</i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>