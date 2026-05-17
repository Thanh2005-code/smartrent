<?php
session_start();
require_once __DIR__ . '/includes/helpers.php';
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

$uid = (int) $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT Name, Username, Email, Phone, Avatar FROM user WHERE ID = ? LIMIT 1');
$user = null;
if ($stmt) {
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();
}

if (!$user) {
    header('HTTP/1.1 404 Not Found');
    exit('Không tìm thấy người dùng.');
}

$avatar_raw = isset($user['Avatar']) ? trim((string) $user['Avatar']) : '';
$avatar_href = smartrent_avatar_url($avatar_raw);

if (isset($_POST['btn_avatar'])) {
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = 'Vui lòng chọn file ảnh.';
    } elseif ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Lỗi tải file lên.';
    } else {
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? finfo_file($finfo, $_FILES['avatar']['tmp_name']) : '';
        if ($finfo) {
            finfo_close($finfo);
        }
        if (!isset($allowed[$mime])) {
            $error = 'Chỉ cho phép ảnh JPG, PNG, WEBP hoặc GIF.';
        } elseif ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
            $error = 'Dung lượng ảnh tối đa 2MB.';
        } else {
            $uploadDir = __DIR__ . '/uploads/avatars/';
            if (!is_dir($uploadDir) && !@mkdir($uploadDir, 0755, true)) {
                $error = 'Không tạo được thư mục chứa ảnh.';
            } else {
                $basename = 'u' . $uid . '_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
                $absolute = $uploadDir . $basename;
                $relative = 'uploads/avatars/' . $basename;

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $absolute)) {
                    $old = isset($user['Avatar']) ? trim((string) $user['Avatar']) : '';
                    if ($old !== '') {
                        $oldRel = str_replace('\\', '/', $old);
                        if (strpos($oldRel, 'uploads/avatars/') !== 0 && strpos($oldRel, '/') === false && strpos($oldRel, '\\') === false) {
                            $oldRel = 'uploads/avatars/' . $oldRel;
                        }
                        $oldPath = __DIR__ . '/' . $oldRel;
                        if (is_file($oldPath)) {
                            @unlink($oldPath);
                        }
                    }

                    $upd = $conn->prepare('UPDATE user SET Avatar = ? WHERE ID = ?');
                    if ($upd) {
                        $upd->bind_param('si', $relative, $uid);
                        $upd->execute();
                        $upd->close();
                    }
                    $user['Avatar'] = $relative;
                    $_SESSION['avatar'] = $relative;
                    $success = 'Cập nhật ảnh đại diện thành công.';
                    $avatar_raw = $relative;
                    $avatar_href = smartrent_avatar_url($avatar_raw);
                } else {
                    $error = 'Không lưu được file trên máy chủ.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Hồ sơ — Smartrent</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-villa-agency.css">
    <style>
        .avatar-preview {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #f35525;
        }
        .avatar-placeholder {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: #eee;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 3rem;
            border: 3px solid #f35525;
        }
        .profile-card {
            padding: 2.5rem;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.06);
            margin-bottom: 3rem;
        }
    </style>
</head>

<body>
<div class="sub-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8">
                <ul class="info">
                    <li><i class="fa fa-envelope"></i> hotro@smartrent.vn</li>
                    <li><i class="fa fa-map"></i> Trường Đại học Vinh, Nghệ An</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <a href="index.php" class="logo"><h1>Smartrent</h1></a>
                    <ul class="nav">
                        <li><a href="index.php">Trang chủ</a></li>
                        <li><a href="properties.php">Phòng trọ</a></li>
                        <li><a href="contact.php">Liên hệ</a></li>
                        <li><a href="profile.php" class="active"><i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['fullname']); ?></a></li>
                        <li><a href="logout.php" style="background-color:#f35525;color:#fff;border-radius:25px;padding:8px 20px !important;">Đăng xuất</a></li>
                    </ul>
                    <a class="menu-trigger"><span>Menu</span></a>
                </nav>
            </div>
        </div>
    </div>
</header>

<div class="page-heading header-text">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Hồ sơ tài khoản</h3>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="profile-card">
                    <div class="text-center mb-4">
                        <?php if ($avatar_href !== ''): ?>
                            <img class="avatar-preview" src="<?php echo htmlspecialchars($avatar_href); ?>" alt="Avatar"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';">
                            <div class="avatar-placeholder" style="display:none;"><i class="fa fa-user"></i></div>
                        <?php else: ?>
                            <div class="avatar-placeholder"><i class="fa fa-user"></i></div>
                        <?php endif; ?>
                    </div>

                    <?php if ($error !== ''): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <?php if ($success !== ''): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                    <?php endif; ?>

                    <h5 class="mb-3">Thông tin</h5>
                    <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($user['Name']); ?></p>
                    <p><strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($user['Username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
                    <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($user['Phone'] ?? ''); ?></p>

                    <hr class="my-4">
                    <h5 class="mb-3">Ảnh đại diện</h5>
                    <p class="text-muted small">JPG, PNG, WEBP hoặc GIF, tối đa 2MB. </p>
                    <form method="POST" action="" enctype="multipart/form-data" class="mt-3">
                        <div class="mb-3">
                            <input type="file" name="avatar" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif" required>
                        </div>
                        <button type="submit" name="btn_avatar" class="btn btn-primary">Tải ảnh lên</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<footer style="margin-top:80px;">
    <div class="container text-center py-4 text-muted"><p>© Smartrent</p></div>
</footer>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/isotope.min.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>
