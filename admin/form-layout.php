<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header('Location: ../login.php');
    exit();
}
require_once __DIR__ . '/../includes/helpers.php';
include '../connect.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$edit = null;
if ($id > 0) {
    $stmt = $conn->prepare('SELECT * FROM motel WHERE ID = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$edit) {
        header('Location: component-card.php');
        exit();
    }
}

$users = $conn->query('SELECT ID, Name FROM user ORDER BY Name');
$districts = $conn->query('SELECT ID, Name FROM districts ORDER BY Name');
$error = '';

if (isset($_POST['btn_save'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (int) ($_POST['price'] ?? 0);
    $area = (int) ($_POST['area'] ?? 0);
    $address = trim($_POST['address'] ?? '');
    $lating = trim($_POST['lating'] ?? '');
    $images = trim($_POST['images'] ?? '');
    $user_id = (int) ($_POST['user_id'] ?? 0);
    $category_id = (int) ($_POST['category_id'] ?? 1);
    $district_id = (int) ($_POST['district_id'] ?? 0);
    $utilities = trim($_POST['utilities'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $approve = (int) ($_POST['approve'] ?? 0);

    if ($title === '' || $price <= 0 || $user_id <= 0 || $district_id <= 0) {
        $error = 'Vui lòng nhập đầy đủ tiêu đề, giá, người đăng và khu vực.';
    } else {
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE motel SET title=?, description=?, price=?, area=?, address=?, lating=?, images=?, user_id=?, category_id=?, district_id=?, utilities=?, phone=?, approve=? WHERE ID=?');
            $stmt->bind_param('ssiisssiissiii', $title, $description, $price, $area, $address, $lating, $images, $user_id, $category_id, $district_id, $utilities, $phone, $approve, $id);
        } else {
            $stmt = $conn->prepare('INSERT INTO motel (title, description, price, area, address, lating, images, user_id, category_id, district_id, utilities, phone, approve) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->bind_param('ssiisssiissii', $title, $description, $price, $area, $address, $lating, $images, $user_id, $category_id, $district_id, $utilities, $phone, $approve);
        }
        if ($stmt->execute()) {
            header('Location: component-card.php');
            exit();
        }
        $error = 'Lưu thất bại: ' . $stmt->error;
        $stmt->close();
    }
}

$v = function ($key, $default = '') use ($edit) {
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }
    return $edit[$key] ?? $default;
};
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id > 0 ? 'Sửa' : 'Thêm'; ?> phòng trọ - Smartrent Admin</title>
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app-dark.css">
</head>
<body>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
                <div class="logo"><a href="index.php">SMARTRENT</a></div>
    </div>
    <div class="sidebar-menu">
        <ul class="menu">
                    <li class="sidebar-title">Menu Quản trị</li>
                    <li class="sidebar-item"><a href="index.php" class="sidebar-link"><i class="bi bi-grid-fill"></i> <span>Bảng điều khiển</span></a></li>
                    <li class="sidebar-item active"><a href="table-datatable.php" class="sidebar-link"><i class="bi bi-house-door-fill"></i> <span>Quản lý phòng trọ</span></a></li>
                    <li class="sidebar-item"><a href="component-card.php" class="sidebar-link"><i class="bi bi-file-earmark-text-fill"></i> <span>Quản lý tin đăng</span></a></li>
                    <li class="sidebar-item"><a href="account-profile.php" class="sidebar-link"><i class="bi bi-people-fill"></i> <span>Quản lý tài khoản</span></a></li>
                    <li class="sidebar-item"><a href="ui-chart-apexcharts.php" class="sidebar-link"><i class="bi bi-bar-chart-fill"></i> <span>Thống kê</span></a></li>
                    <li class="sidebar-item"><a href="../logout.php" class="sidebar-link"><i class="bi bi-box-arrow-right"></i> <span>Đăng xuất</span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="main">
        <header class="mb-3"><a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a></header>
        <div class="page-heading"><h3><?php echo $id > 0 ? 'Sửa phòng trọ' : 'Thêm phòng trọ'; ?></h3></div>
        <div class="page-content">
            <?php if ($error !== ''): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            <section class="section">
                <div class="card">
                        <div class="card-body">
                        <form method="POST" class="form">
                                    <div class="row">
                                <div class="col-md-8 form-group mb-3">
                                    <label>Tiêu đề</label>
                                    <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($v('title')); ?>">
                                        </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label>Giá (VNĐ)</label>
                                    <input type="number" name="price" class="form-control" required value="<?php echo (int) $v('price', 0); ?>">
                                        </div>
                                <div class="col-12 form-group mb-3">
                                    <label>Mô tả</label>
                                    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($v('description')); ?></textarea>
                                        </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label>Địa chỉ</label>
                                    <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($v('address')); ?>">
                                        </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label>Tọa độ (lat,lng)</label>
                                    <input type="text" name="lating" class="form-control" placeholder="18.6736,105.6923" value="<?php echo htmlspecialchars($v('lating')); ?>">
                                        </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label>Diện tích (m2)</label>
                                    <input type="number" name="area" class="form-control" value="<?php echo (int) $v('area', 0); ?>">
                                        </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label>Loại phòng</label>
                                    <select name="category_id" class="form-select">
                                        <option value="1"<?php echo (int) $v('category_id', 1) === 1 ? ' selected' : ''; ?>>Phòng khép kín</option>
                                        <option value="2"<?php echo (int) $v('category_id', 1) === 2 ? ' selected' : ''; ?>>Chung cư mini</option>
                                        <option value="3"<?php echo (int) $v('category_id', 1) === 3 ? ' selected' : ''; ?>>Nhà nguyên căn</option>
                                    </select>
                                        </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label>Trạng thái</label>
                                    <select name="approve" class="form-select">
                                        <option value="0"<?php echo (int) $v('approve', 0) === 0 ? ' selected' : ''; ?>>Chờ duyệt</option>
                                        <option value="1"<?php echo (int) $v('approve', 0) === 1 ? ' selected' : ''; ?>>Hiển thị</option>
                                        <option value="2"<?php echo (int) $v('approve', 0) === 2 ? ' selected' : ''; ?>>Ẩn</option>
                                    </select>
                                        </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label>Khu vực</label>
                                    <select name="district_id" class="form-select" required>
                                        <option value="">-- Chọn --</option>
                                        <?php while ($d = $districts->fetch_assoc()): ?>
                                        <option value="<?php echo (int) $d['ID']; ?>"<?php echo (int) $v('district_id', 0) === (int) $d['ID'] ? ' selected' : ''; ?>><?php echo htmlspecialchars($d['Name']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label>Người đăng</label>
                                    <select name="user_id" class="form-select" required>
                                        <option value="">-- Chọn --</option>
                                        <?php while ($u = $users->fetch_assoc()): ?>
                                        <option value="<?php echo (int) $u['ID']; ?>"<?php echo (int) $v('user_id', 0) === (int) $u['ID'] ? ' selected' : ''; ?>><?php echo htmlspecialchars($u['Name']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label>Tiện ích</label>
                                    <input type="text" name="utilities" class="form-control" value="<?php echo htmlspecialchars($v('utilities')); ?>">
                                </div>
                                <div class="col-md-3 form-group mb-3">
                                    <label>SĐT liên hệ</label>
                                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($v('phone')); ?>">
                                </div>
                                <div class="col-md-3 form-group mb-3">
                                    <label>Tên file ảnh</label>
                                    <input type="text" name="images" class="form-control" placeholder="phong1.jpg" value="<?php echo htmlspecialchars($v('images')); ?>">
                                </div>
                            </div>
                            <button type="submit" name="btn_save" class="btn btn-primary">Lưu</button>
                            <a href="table-datatable.php" class="btn btn-light">Hủy</a>
                            </form>
            </div>
        </div>
    </section>
        </div>
        </div>
    </div>
    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/compiled/js/app.js"></script>
</body>
</html>
