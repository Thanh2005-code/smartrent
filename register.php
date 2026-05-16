<?php
session_start();
include 'connect.php';

$error = '';
$success = '';

if (isset($_POST['btn_register'])) {

    // Lấy dữ liệu từ form
    $fullname        = trim(isset($_POST['fullname']) ? $_POST['fullname'] : '');
    $email           = trim(isset($_POST['email']) ? $_POST['email'] : '');
    $phone           = trim(isset($_POST['phone']) ? $_POST['phone'] : '');
    $username        = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $password_raw    = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_raw     = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // ================= KIỂM TRA RÀNG BUỘC =================

    // Họ tên
    if (empty($fullname)) {
        $error = "Họ và tên không được để trống!";
    }

    else if (mb_strlen($fullname) < 10) {
        $error = "Họ và tên phải có ít nhất 10 ký tự!";
    }

    // Email
    else if (empty($email)) {
        $error = "Email không được để trống!";
    }

    else if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
        $error = "Email phải đúng định dạng abc@gmail.com!";
    }

    // Số điện thoại
    else if (empty($phone)) {
        $error = "Số điện thoại không được để trống!";
    }

    else if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
        $error = "Số điện thoại phải gồm 10 hoặc 11 chữ số!";
    }

    // Username
    else if (empty($username)) {
        $error = "Tên đăng nhập không được để trống!";
    }

    else if (preg_match('/\s/', $username)) {
        $error = "Tên đăng nhập không được chứa dấu cách!";
    }

    else if (strlen($username) < 5) {
        $error = "Tên đăng nhập phải có ít nhất 5 ký tự!";
    }

    // Password
    else if (empty($password_raw)) {
        $error = "Mật khẩu không được để trống!";
    }

    else if (strlen($password_raw) <= 12) {
        $error = "Mật khẩu phải lớn hơn 12 ký tự!";
    }

    else if (!preg_match('/^[A-Z]/', $password_raw)) {
        $error = "Ký tự đầu tiên của mật khẩu phải là chữ in hoa!";
    }

    else if (!preg_match('/[\W]/', $password_raw)) {
        $error = "Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt!";
    }

    // Xác nhận mật khẩu
    else if (empty($confirm_raw)) {
        $error = "Vui lòng nhập lại mật khẩu!";
    }

    else if ($password_raw != $confirm_raw) {
        $error = "Mật khẩu xác nhận không khớp!";
    }

    else {

        // ================= MÃ HÓA MD5 =================
        $password_md5 = md5($password_raw);

        // ================= KIỂM TRA TRÙNG =================
        $check_sql = "SELECT id FROM user 
                      WHERE Username='$username' 
                      OR Email='$email'";

        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {

            $error = "Tên đăng nhập hoặc Email đã tồn tại!";

        } else {

            // ================= THÊM DỮ LIỆU =================
            $insert_sql = "INSERT INTO user
                          (Name, Username, Password, Email, Phone, Role)
                          VALUES
                          ('$fullname', '$username', '$password_md5', '$email', '$phone', 0)";

            if ($conn->query($insert_sql) === TRUE) {

                $success = "Đăng ký tài khoản thành công!";

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
    <title>Đăng ký - SmartRent</title>

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
                    if ($error != '') {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }

                    if ($success != '') {
                        echo "<div class='alert alert-success'>$success</div>";
                    }
                    ?>

                    <!-- Họ tên -->
                    <div class="form-group position-relative has-icon-left mb-4">

                        <input
                            type="text"
                            class="form-control form-control-xl"
                            name="fullname"
                            placeholder="Họ và tên"
                            minlength="10"
                            value="<?php echo isset($fullname) ? $fullname : ''; ?>"
                            oninvalid="if(this.value==''){this.setCustomValidity('Họ và tên không được để trống!');}else{this.setCustomValidity('Họ và tên phải có ít nhất 10 ký tự!');}"
                            oninput="this.setCustomValidity('')"
                            required
                        >

                        <div class="form-control-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>

                    </div>

                    <!-- Email -->
                    <!-- Email -->
<div class="form-group position-relative has-icon-left mb-4">

  <input
    type="email"
    class="form-control form-control-xl"
    name="email"
    placeholder="abc@gmail.com"
    required
    value="<?php echo isset($email) ? $email : ''; ?>"

    onblur="validateEmail(this)"
    oninput="this.setCustomValidity('')"
>
    <div class="form-control-icon">
        <i class="bi bi-envelope"></i>
    </div>

</div>

                    <!-- Số điện thoại -->
                    <div class="form-group position-relative has-icon-left mb-4">

                        <input
                            type="text"
                            class="form-control form-control-xl"
                            name="phone"
                            placeholder="Số điện thoại"
                            pattern="[0-9]{10,11}"
                            value="<?php echo isset($phone) ? $phone : ''; ?>"
                            oninvalid="if(this.value==''){this.setCustomValidity('Số điện thoại không được để trống!');}else{this.setCustomValidity('Số điện thoại phải gồm 10 hoặc 11 chữ số!');}"
                            oninput="this.setCustomValidity('')"
                            required
                        >

                        <div class="form-control-icon">
                            <i class="bi bi-telephone"></i>
                        </div>

                    </div>

                    <!-- Username -->
                    <div class="form-group position-relative has-icon-left mb-4">

                        <input
                            type="text"
                            class="form-control form-control-xl"
                            name="username"
                            placeholder="Tên đăng nhập"
                            minlength="5"
                            pattern="^\S+$"
                            value="<?php echo isset($username) ? $username : ''; ?>"
                            oninvalid="if(this.value==''){this.setCustomValidity('Tên đăng nhập không được để trống!');}else{this.setCustomValidity('Tên đăng nhập không được chứa dấu cách và phải từ 5 ký tự trở lên!');}"
                            oninput="this.setCustomValidity('')"
                            required
                        >

                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
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

                    </div>

                    <button
                        type="submit"
                        name="btn_register"
                        class="btn btn-primary btn-block btn-lg shadow-lg mt-5">

                        Đăng ký ngay

                    </button>

                </form>

                <div class="text-center mt-5 text-lg fs-5">

                    <p class='text-gray-600'>
                        Đã có tài khoản?
                        <a href="login.php" class="font-bold">
                            Đăng nhập
                        </a>
                    </p>

                    <p class="text-muted mt-3">
                        <i>"Chủ trọ nhàn tay phòng đầy mỗi ngày"</i>
                    </p>

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