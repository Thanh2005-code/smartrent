<?php
session_start();
require_once __DIR__ . '/includes/helpers.php';
include 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['role']) || (int)$_SESSION['role'] !== 1) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';
$uid = (int)$_SESSION['user_id'];

if (isset($_POST['btn_add'])) {
    $title       = trim((string)$_POST['title']);
    $price       = (int)$_POST['price'];
    $area        = (int)$_POST['area'];
    $category_id = (int)$_POST['category_id'];
    $district_id = (int)$_POST['district_id'];
    
    // Xử lý chuỗi tiện ích từ các ô Checkbox
    $utils_arr   = isset($_POST['utilities']) ? $_POST['utilities'] : [];
    $utilities   = count($utils_arr) > 0 ? implode(', ', $utils_arr) : 'Không có';

    // Rà soát dữ liệu đầu vào cơ bản
    if ($title === '' || $price <= 0 || $area <= 0 || $category_id <= 0 || $district_id <= 0) {
        $error = 'Vui lòng điền đầy đủ thông tin vào các trường bắt buộc.';
    } else {
        // Xử lý tải tập tin hình ảnh phòng trọ lên hệ thống
        $image_file = '';
        if (isset($_FILES['images']) && $_FILES['images']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES['images']['tmp_name']);
            finfo_close($finfo);

            if (!isset($allowed_types[$mime])) {
                $error = 'Định dạng ảnh không hợp lệ! Vui lòng chỉ chọn tệp JPG, PNG hoặc WEBP.';
            } else {
                // Đặt tên file ngẫu nhiên để tránh trùng lặp tệp cũ trên máy chủ
                $ext = $allowed_types[$mime];
                $image_file = 'room_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $upload_path = __DIR__ . '/assets/images/' . $image_file;

                if (!move_uploaded_file($_FILES['images']['tmp_name'], $upload_path)) {
                    $error = 'Không thể lưu trữ tệp hình ảnh lên thư mục assets/images/.';
                    $image_file = '';
                }
            }
        }

        // Tiến hành ghi nhận thông tin vào Cơ sở dữ liệu nếu không phát sinh lỗi
        if ($error === '') {
            // Mặc định thiết lập: approve = 0 (Chờ Admin phê duyệt), status = 0 (Phòng còn trống)
            $stmt = $conn->prepare("INSERT INTO motel (title, price, area, category_id, district_id, utilities, images, user_id, approve, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, 0, NOW())");
            
            if ($stmt) {
                $stmt->bind_param('siiiissi', $title, $price, $area, $category_id, $district_id, $utilities, $image_file, $uid);
                if ($stmt->execute()) {
                    $success = 'Đăng tin bài phòng trọ thành công! Vui lòng kiên nhẫn chờ Ban quản trị duyệt bài để hiển thị công khai.';
                } else {
                    $error = 'Lỗi hệ thống CSDL: Không thể thực thi câu lệnh lưu trữ.';
                }
                $stmt->close();
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
    <title>Đăng phòng mới — Smartrent</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-villa-agency.css">
    <style>
        .form-card {
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
                <h3>Thêm Bài Đăng Phòng Trọ Mới</h3>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <?php if ($error !== ''): ?>
                <div class="alert alert-danger border-0 shadow-sm"><i class="fa fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success !== ''): ?>
                <div class="alert alert-success border-0 shadow-sm"><i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <div class="form-card">
                <form action="add-property.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="form-group-custom">
                        <label for="title">Tiêu đề bài đăng bài <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Ví dụ: Phòng khép kín đầy đủ tiện nghi số 10 Bạch Liêu" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label for="category_id">Phân loại loại hình <span class="text-danger">*</span></label>
                                <select id="category_id" name="category_id" class="form-select" required>
                                    <option value="" disabled selected>-- Chọn loại phòng --</option>
                                    <option value="1">Phòng khép kín</option>
                                    <option value="2">Nhà nguyên căn</option>
                                    <option value="3">Chung cư mini</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label for="district_id">Khu vực địa điểm <span class="text-danger">*</span></label>
                                <select id="district_id" name="district_id" class="form-select" required>
                                    <option value="" disabled selected>-- Chọn quận/huyện --</option>
                                    <?php
                                    $dist_res = $conn->query('SELECT * FROM districts');
                                    if ($dist_res) {
                                        while ($d = $dist_res->fetch_assoc()) {
                                            echo '<option value="' . (int) $d['ID'] . '">' . htmlspecialchars($d['Name']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label for="price">Giá cho thuê (VNĐ / Tháng) <span class="text-danger">*</span></label>
                                <input type="number" id="price" name="price" class="form-control" placeholder="Ví dụ: 1500000" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label for="area">Diện tích phòng (m²) <span class="text-danger">*</span></label>
                                <input type="number" id="area" name="area" class="form-control" placeholder="Ví dụ: 25" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label>Hệ thống tiện ích đi kèm</label>
                        <div class="d-flex flex-wrap gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="utilities[]" value="Có Wifi" id="util_wifi">
                                <label class="form-check-label" for="util_wifi">Có Wifi</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="utilities[]" value="Điều hòa" id="util_ac">
                                <label class="form-check-label" for="util_ac">Điều hòa</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="utilities[]" value="Chỗ để xe" id="util_parking">
                                <label class="form-check-label" for="util_parking">Chỗ để xe</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="utilities[]" value="Khép kín" id="util_private">
                                <label class="form-check-label" for="util_private">Khép kín</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-custom mb-4">
                        <label for="images">Hình ảnh thực tế minh họa phòng</label>
                        <input class="form-control" type="file" id="images" name="images" accept="image/*">
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" name="btn_add" class="btn btn-custom-submit px-4">Đăng tin phòng</button>
                        <a href="profile.php" class="btn btn-secondary rounded-pill px-4" style="padding-top:10px;">Quay lại hồ sơ</a>
                    </div>
                </form>
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