<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/helpers.php';
include 'connect.php';

if (!isset($_SESSION['login_failures'])) {
    $_SESSION['login_failures'] = 0;
}

$error = '';
$require_captcha = $_SESSION['login_failures'] >= 3;

if (isset($_POST['btn_login'])) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password_raw = isset($_POST['password']) ? $_POST['password'] : '';
    $password_md5 = md5($password_raw);

    if ($require_captcha) {
        $token = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
        if (!smartrent_verify_recaptcha(RECAPTCHA_SECRET_KEY, $token)) {
            $error = 'Vui lòng xác nhận Google reCAPTCHA.';
            $_SESSION['login_failures']++;
            $require_captcha = $_SESSION['login_failures'] >= 3;
        }
    }

    if ($error === '') {
        $stmt = $conn->prepare('SELECT ID, Name, Email, Role, Avatar FROM user WHERE Username = ? AND Password = ?');
        if ($stmt) {
            $stmt->bind_param('ss', $username, $password_md5);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $_SESSION['login_failures'] = 0; // Reset số lần sai khi đăng nhập đúng

                $_SESSION['user_id'] = $row['ID'];
                $_SESSION['fullname'] = $row['Name'];
                $_SESSION['role'] = (int)$row['Role']; // Lưu Role (0, 1 hoặc 2) vào Session
                $_SESSION['avatar'] = $row['Avatar'] ?? '';

                // SỬA LUỒNG PHÂN QUYỀN: Chỉ Admin (Role = 2) mới được vào trang Quản trị
                if ((int)$row['Role'] === 2) {
                    header('Location: admin/index.php');
                } else {
                    // Người thuê (Role = 0) và Chủ trọ (Role = 1) đều về Trang chủ
                    header('Location: index.php');
                }
                exit();
            }
            $stmt->close();
        }

        $_SESSION['login_failures']++;
        $error = 'Sai tên đăng nhập hoặc mật khẩu!';
        $require_captcha = $_SESSION['login_failures'] >= 3;
    }
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
    <?php if ($require_captcha && RECAPTCHA_SITE_KEY !== ''): ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
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

                        <?php if ($error != '') {
                            echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . '</div>';
                        } ?>

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

                        <?php if ($require_captcha): ?>
                        <div class="mb-4">
                            <?php if (RECAPTCHA_SITE_KEY !== ''): ?>
                            <div class="g-recaptcha" 
                                 data-sitekey="<?php echo htmlspecialchars(RECAPTCHA_SITE_KEY); ?>" 
                                 data-expired-callback="onRecaptchaExpired"></div>
                            
                            <script>
                                function onRecaptchaExpired() {
                                    if (typeof grecaptcha !== 'undefined') {
                                        grecaptcha.reset();
                                    }
                                }
                            </script>
                            <?php else: ?>
                            <div class="alert alert-warning">Chưa cấu hình RECAPTCHA_SITE_KEY trong config.php.</div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

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
                        <p class="text-muted mt-3"><i>"Chủ trọ nhàn tay - Phòng đầy mỗi ngày"</i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>