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

// ==========================================
// BỔ SUNG: XỬ LÝ CHỦ TRỌ BÁO CÁO ĐÃ THUÊ
// ==========================================
if (isset($_POST['btn_mark_rented'])) {
    $motel_id = (int)$_POST['motel_id'];
    // Bảo mật: Chỉ được cập nhật trạng thái phòng thuộc sở hữu của chính mình
    $stmt_rent = $conn->prepare('UPDATE motel SET status = 1 WHERE ID = ? AND user_id = ?');
    if ($stmt_rent) {
        $stmt_rent->bind_param('ii', $motel_id, $uid);
        if ($stmt_rent->execute()) {
            $success = 'Đã gửi báo cáo: Cập nhật trạng thái phòng thành ĐÃ THUÊ thành công!';
        } else {
            $error = 'Có lỗi xảy ra khi cập nhật trạng thái phòng.';
        }
        $stmt_rent->close();
    }
}

// ==========================================
// 1. XỬ LÝ CẬP NHẬT THÔNG TIN TÀI KHOẢN
// ==========================================
if (isset($_POST['btn_update_info'])) {
    $name = trim((string)$_POST['name']);
    $email = trim((string)$_POST['email']);
    $phone = trim((string)$_POST['phone']);

    if ($name === '' || $email === '') {
        $error = 'Họ tên và Email không được để trống.';
    } else {
        $upd_info = $conn->prepare('UPDATE user SET Name = ?, Email = ?, Phone = ? WHERE ID = ?');
        if ($upd_info) {
            $upd_info->bind_param('sssi', $name, $email, $phone, $uid);
            if ($upd_info->execute()) {
                $success = 'Cập nhật thông tin tài khoản thành công.';
                $_SESSION['fullname'] = $name; 
            } else {
                $error = 'Có lỗi xảy ra khi cập nhật thông tin.';
            }
            $upd_info->close();
        }
    }
}

// ==========================================
// 2. XỬ LÝ ĐỔI MẬT KHẨU TÀI KHOẢN
// ==========================================
if (isset($_POST['btn_change_pass'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($old_pass === '' || $new_pass === '' || $confirm_pass === '') {
        $error = 'Vui lòng điền đầy đủ các trường mật khẩu.';
    } elseif ($new_pass !== $confirm_pass) {
        $error = 'Mật khẩu mới và xác nhận mật khẩu không trùng khớp.';
    } else {
        $stmt_pass = $conn->prepare('SELECT Password FROM user WHERE ID = ? LIMIT 1');
        if ($stmt_pass) {
            $stmt_pass->bind_param('i', $uid);
            $stmt_pass->execute();
            $res_pass = $stmt_pass->get_result();
            $user_pass = $res_pass->fetch_assoc();
            $stmt_pass->close();

            if ($user_pass) {
                if (md5($old_pass) !== $user_pass['Password']) {
                    $error = 'Mật khẩu cũ không chính xác.';
                } else {
                    $new_pass_md5 = md5($new_pass);
                    $upd_pass = $conn->prepare('UPDATE user SET Password = ? WHERE ID = ?');
                    if ($upd_pass) {
                        $upd_pass->bind_param('si', $new_pass_md5, $uid);
                        if ($upd_pass->execute()) {
                            $success = 'Đổi mật khẩu thành công.';
                        } else {
                            $error = 'Có lỗi xảy ra khi đổi mật khẩu.';
                        }
                        $upd_pass->close();
                    }
                }
            }
        }
    }
}

// ==========================================
// 3. XỬ LÝ TẢI ẢNH ĐẠI DIỆN
// ==========================================
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
        if ($finfo) { finfo_close($finfo); }
        
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
                        if (is_file($oldPath)) { @unlink($oldPath); }
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

// Lấy danh sách phòng trọ do chính user này đăng để hiển thị ở Tab Quản lý tin
$my_motels = [];
$stmt_my_list = $conn->prepare("SELECT motel.*, districts.Name as district_name 
                                FROM motel 
                                JOIN districts ON motel.district_id = districts.ID 
                                WHERE motel.user_id = ? 
                                ORDER BY motel.created_at DESC");
if ($stmt_my_list) {
    $stmt_my_list->bind_param('i', $uid);
    $stmt_my_list->execute();
    $res_my_list = $stmt_my_list->get_result();
    while ($row = $res_my_list->fetch_assoc()) {
        $my_motels[] = $row;
    }
    $stmt_my_list->close();
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
        .form-group-custom {
            margin-bottom: 1.25rem;
        }
        .form-group-custom label {
            font-weight: 500;
            margin-bottom: 0.4rem;
            color: #333;
        }
        .btn-custom-submit {
            background-color: #f35525;
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-custom-submit:hover {
            background-color: #e24416;
            color: #fff;
        }
        .nav-tabs-custom .nav-link {
            color: #333;
            font-weight: 500;
            border: none;
            padding: 12px 20px;
        }
        .nav-tabs-custom .nav-link.active {
            color: #f35525;
            border-bottom: 3px solid #f35525;
            background: none;
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
                <h3>Quản lý tài khoản cá nhân</h3>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <?php if ($error !== ''): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($success !== ''): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <ul class="nav nav-tabs nav-tabs-custom mb-4" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-pane" type="button" role="tab">Thông tin tài khoản</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="motel-tab" data-bs-toggle="tab" data-bs-target="#motel-pane" type="button" role="tab">Tin đăng của tôi (<?php echo count($my_motels); ?>)</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="info-pane" role="tabpanel">
            <div class="row">
                <div class="col-lg-4 text-center mb-4">
                    <div class="profile-card">
                        <form action="profile.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <?php if ($avatar_href !== ''): ?>
                                    <img src="<?php echo htmlspecialchars($avatar_href); ?>" class="avatar-preview" alt="Avatar">
                                <?php else: ?>
                                    <div class="avatar-placeholder"><i class="fa fa-user"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label font-weight-bold">Thay đổi ảnh đại diện</label>
                                <input class="form-control" type="file" name="avatar" accept="image/*">
                            </div>
                            <button type="submit" name="btn_avatar" class="btn btn-custom-submit w-100">Tải ảnh lên</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="profile-card">
                        <h4 class="mb-4">Cập nhật thông tin cá nhân</h4>
                        <form action="profile.php" method="POST">
                            <div class="form-group-custom">
                                <label>Tên đăng nhập (Không thể sửa)</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['Username']); ?>" disabled>
                            </div>
                            <div class="form-group-custom">
                                <label>Họ và tên</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['Name']); ?>" required>
                            </div>
                            <div class="form-group-custom">
                                <label>Địa chỉ Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                            </div>
                            <div class="form-group-custom">
                                <label>Số điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['Phone']); ?>">
                            </div>
                            <button type="submit" name="btn_update_info" class="btn btn-custom-submit mt-2">Lưu thay đổi</button>
                        </form>
                    </div>

                    <div class="profile-card">
                        <h4 class="mb-4">Đổi mật khẩu tài khoản</h4>
                        <form action="profile.php" method="POST">
                            <div class="form-group-custom">
                                <label>Mật khẩu hiện tại</label>
                                <input type="password" name="old_password" class="form-control" required>
                            </div>
                            <div class="form-group-custom">
                                <label>Mật khẩu mới</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="form-group-custom">
                                <label>Xác nhận mật khẩu mới</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" name="btn_change_pass" class="btn btn-custom-submit mt-2">Đổi mật khẩu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="motel-pane" role="tabpanel">
            <div class="profile-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Danh sách phòng trọ bạn đã đăng</h4>
                    <a href="add-property.php" class="btn btn-success rounded-pill px-4"><i class="fa fa-plus"></i> Đăng phòng mới</a>
                </div>

                <?php if (count($my_motels) === 0): ?>
                    <p class="text-center text-muted py-4">Bạn chưa đăng bài phòng trọ nào lên hệ thống.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th>Hình ảnh</th>
                                    <th>Tiêu đề bài đăng</th>
                                    <th>Khu vực</th>
                                    <th>Giá phòng</th>
                                    <th>Trạng thái duyệt</th>
                                    <th>Tình trạng phòng</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($my_motels as $motel): 
                                    $img = !empty($motel['images']) ? 'assets/images/' . $motel['images'] : 'assets/images/property-01.jpg';
                                    $is_rented = isset($motel['status']) && (int)$motel['status'] === 1;
                                ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo htmlspecialchars($img); ?>" style="width:70px; height:50px; object-fit:cover; border-radius:5px;">
                                        </td>
                                        <td>
                                            <a href="property-details.php?id=<?php echo $motel['ID']; ?>" target="_blank" class="text-dark font-weight-bold"><?php echo htmlspecialchars($motel['title']); ?></a>
                                        </td>
                                        <td><?php echo htmlspecialchars($motel['district_name']); ?></td>
                                        <td class="text-danger font-weight-bold"><?php echo number_format($motel['price'], 0, ',', '.'); ?>đ</td>
                                        <td>
                                            <?php if ((int)$motel['approve'] === 1): ?>
                                                <span class="badge bg-success">Đã duyệt</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($is_rented): ?>
                                                <span class="badge bg-secondary">Đã cho thuê</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary">Còn trống</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <?php if (!$is_rented): ?>
                                                    <form action="profile.php" method="POST" onsubmit="return confirm('Xác nhận đánh dấu phòng này ĐÃ THUÊ?');">
                                                        <input type="hidden" name="motel_id" value="<?php echo $motel['ID']; ?>">
                                                        <button type="submit" name="btn_mark_rented" class="btn btn-sm btn-outline-danger" title="Báo cáo đã thuê"><i class="fa fa-home"></i> Đã thuê</button>
                                                    </form>
                                                <?php endif; ?>
                                                
                                                <a href="edit-property.php?id=<?php echo $motel['ID']; ?>" class="btn btn-sm btn-outline-primary" title="Sửa tin"><i class="fa fa-edit"></i></a>
                                                <a href="delete-property.php?id=<?php echo $motel['ID']; ?>" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Bạn có chắc chắn muốn xóa tin đăng này?')" title="Xóa tin"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <div class="col-lg-12">
            <p>Copyright © 2026 Smartrent. Thiết kế bám sát Case Study ĐH Vinh. 
            <br><i>"Chủ trọ nhàn tay - Phòng đầy mỗi ngày"</i></p>
        </div>
    </div>
</footer>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/counter.js"></script>
  <script src="assets/js/custom.js"></script>
</body>
</html>