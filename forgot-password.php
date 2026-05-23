<?php
session_start();
include 'connect.php';

$msg = "";
$msg_type = "";

if (isset($_POST['btn_forgot'])) {

    $username = trim($_POST['username']);

    if (empty($username)) {

        $msg = "Vui lòng nhập tài khoản";
        $msg_type = "danger";
    }

    else {

        $stmt = $conn->prepare("
            SELECT ID
            FROM `user`
            WHERE Username = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $update = $conn->prepare("
                UPDATE `user`
                SET forgot_password = 1
                WHERE Username = ?
            ");

            $update->bind_param("s", $username);

            $update->execute();

            $msg = "Yêu cầu reset mật khẩu đã được gửi tới Admin";
            $msg_type = "success";
        }

        else {

            $msg = "Tài khoản không tồn tại";
            $msg_type = "danger";
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

    <title>Quên mật khẩu - SmartRent</title>

    <link rel="stylesheet"
          href="assets/css/bootstrap.min.css">

    <style>

        body {

            margin: 0;
            padding: 0;

            height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            background:
                linear-gradient(
                    135deg,
                    #4e73df,
                    #224abe
                );

            font-family: Arial, sans-serif;
        }

        .forgot-box {

            width: 100%;
            max-width: 420px;

            background: #fff;

            padding: 35px;

            border-radius: 16px;

            box-shadow:
                0 10px 30px rgba(0,0,0,0.15);
        }

        .forgot-title {

            text-align: center;

            font-size: 28px;
            font-weight: bold;

            margin-bottom: 10px;

            color: #224abe;
        }

        .forgot-subtitle {

            text-align: center;

            color: #777;

            margin-bottom: 30px;
        }

        .form-control {

            height: 50px;

            border-radius: 10px;
        }

        .btn-reset {

            height: 50px;

            border-radius: 10px;

            font-weight: bold;

            font-size: 16px;
        }

        .back-login {

            text-align: center;

            margin-top: 20px;
        }

        .back-login a {

            text-decoration: none;

            font-weight: bold;

            color: #224abe;
        }

        .back-login a:hover {

            text-decoration: underline;
        }

    </style>

</head>

<body>

<div class="forgot-box">

    <div class="forgot-title">

        Quên mật khẩu

    </div>

    <div class="forgot-subtitle">

        Gửi yêu cầu reset mật khẩu tới Admin

    </div>

    <?php if (!empty($msg)) : ?>

        <div class="alert alert-<?= $msg_type ?>">

            <?= $msg ?>

        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">

            <label class="form-label">

                Tài khoản

            </label>

            <input type="text"
                   name="username"
                   class="form-control"
                   placeholder="Nhập tên tài khoản"
                   required>

        </div>

        <button type="submit"
                name="btn_forgot"
                class="btn btn-danger w-100 btn-reset ">

            Gửi yêu cầu reset

        </button>

    </form>

    <div class="back-login">

        <a href="login.php">

            ← Quay lại đăng nhập

        </a>

    </div>

</div>

</body>
</html>
