<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header('Location: ../login.php');
    exit();
}
require_once __DIR__ . '/../includes/helpers.php';
include '../connect.php';

$msg = '';
if (isset($_GET['delete'])) {
    $del_id = (int) $_GET['delete'];
    $stmt = $conn->prepare('DELETE FROM motel WHERE ID = ?');
    $stmt->bind_param('i', $del_id);
    if ($stmt->execute()) {
        $msg = 'Đã xóa phòng trọ.';
    }
    $stmt->close();
}
if (isset($_GET['approve'])) {
    $aid = (int) $_GET['approve'];
    $conn->query('UPDATE motel SET approve = 1 WHERE ID = ' . $aid);
    $msg = 'Đã duyệt phòng trọ.';
}
if (isset($_GET['hide'])) {
    $hid = (int) $_GET['hide'];
    $conn->query('UPDATE motel SET approve = 2 WHERE ID = ' . $hid);
    $msg = 'Đã ẩn phòng trọ.';
}
if (isset($_GET['show'])) {
    $sid = (int) $_GET['show'];
    $conn->query('UPDATE motel SET approve = 1 WHERE ID = ' . $sid);
    $msg = 'Đã hiển thị phòng trọ.';
}

$list = $conn->query("SELECT motel.*, districts.Name AS district_name, user.Name AS owner_name
    FROM motel
    LEFT JOIN districts ON motel.district_id = districts.ID
    LEFT JOIN user ON motel.user_id = user.ID
    ORDER BY motel.created_at DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý phòng trọ - Smartrent Admin</title>
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="assets/compiled/css/table-datatable.css">
</head>
<body>
<script src="assets/static/js/initTheme.js"></script>
<div id="app">
    <div id="sidebar">
        <div class="sidebar-wrapper active">
            <div class="sidebar-header position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo"><a href="index.php">SMARTRENT</a></div>
                </div>
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
        <div class="page-heading"><h3>Quản lý phòng trọ</h3></div>
        <div class="page-content">
            <?php if ($msg !== ''): ?><div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
            <section class="section">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Danh sách phòng trọ</h4>
                        <a href="form-layout.php" class="btn btn-primary btn-sm">+ Thêm phòng</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tiêu đề</th>
                                        <th>Giá</th>
                                        <th>Khu vực</th>
                                        <th>Người đăng</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php while ($row = $list->fetch_assoc()):
                                    list($st_label, $st_class) = smartrent_approve_label($row['approve']);
                                ?>
                                    <tr>
                                        <td><?php echo (int) $row['ID']; ?></td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo number_format((int) $row['price']); ?> đ</td>
                                        <td><?php echo htmlspecialchars($row['district_name'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['owner_name'] ?? ''); ?></td>
                                        <td><span class="badge <?php echo $st_class; ?>"><?php echo $st_label; ?></span></td>
                                        <td>
                                            <a href="form-layout.php?id=<?php echo (int) $row['ID']; ?>" class="btn btn-sm btn-info">Sửa</a>
                                            <?php if ((int) $row['approve'] === 0): ?>
                                            <a href="?approve=<?php echo (int) $row['ID']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Duyệt phòng này?')">Duyệt</a>
                                            <?php endif; ?>
                                            <?php if ((int) $row['approve'] === 1): ?>
                                            <a href="?hide=<?php echo (int) $row['ID']; ?>" class="btn btn-sm btn-secondary" onclick="return confirm('Ẩn phòng này?')">Ẩn</a>
                                            <?php endif; ?>
                                            <?php if ((int) $row['approve'] === 2): ?>
                                            <a href="?show=<?php echo (int) $row['ID']; ?>" class="btn btn-sm btn-success">Hiện</a>
                                            <?php endif; ?>
                                            <a href="?delete=<?php echo (int) $row['ID']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa phòng này?')">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer><div class="footer clearfix mb-0 text-muted text-center"><p>2026 &copy; Smartrent</p></div></footer>
    </div>
</div>
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/compiled/js/app.js"></script>
</body>
</html>
