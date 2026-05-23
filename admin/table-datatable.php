<?php
session_start();
// Kiểm tra quyền truy cập Admin (role=2)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header('Location: ../login.php');
    exit();
}
require_once __DIR__ . '/../includes/helpers.php';
include '../connect.php';

$msg = '';

// --- XỬ LÝ LOGIC (GIỮ NGUYÊN) ---
if (isset($_GET['delete'])) {
    $del_id = (int) $_GET['delete'];
    $stmt = $conn->prepare('DELETE FROM motel WHERE ID = ?');
    $stmt->bind_param('i', $del_id);
    if ($stmt->execute()) $msg = 'Đã xóa phòng trọ thành công.';
}
if (isset($_GET['approve'])) { $conn->query('UPDATE motel SET approve = 1 WHERE ID = ' . (int)$_GET['approve']); $msg = 'Đã duyệt phòng trọ.'; }
if (isset($_GET['hide'])) { $conn->query('UPDATE motel SET approve = 2 WHERE ID = ' . (int)$_GET['hide']); $msg = 'Đã ẩn phòng trọ.'; }
if (isset($_GET['show'])) { $conn->query('UPDATE motel SET approve = 1 WHERE ID = ' . (int)$_GET['show']); $msg = 'Đã hiển thị phòng trọ.'; }
if (isset($_GET['reject'])) { $conn->query('UPDATE motel SET approve = 3 WHERE ID = ' . (int)$_GET['reject']); $msg = 'Đã từ chối tin đăng.'; }

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
</head>
<body>
<script src="assets/static/js/initTheme.js"></script>
<div id="app">
    <!-- Sidebar -->
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
                    <li class="sidebar-item"><a href="index.php" class='sidebar-link'><i class="bi bi-grid-fill"></i> <span>Bảng điều khiển</span></a></li>
                    <li class="sidebar-item active"><a href="table-datatable.php" class="sidebar-link"><i class="bi bi-house-door-fill"></i> <span>Quản lý phòng trọ</span></a></li>
                    <li class="sidebar-item"><a href="component-card.php" class='sidebar-link'><i class="bi bi-file-earmark-text-fill"></i> <span>Quản lý tin đăng</span></a></li>
                    <li class="sidebar-item"><a href="admin-contacts.php" class='sidebar-link'><i class="bi bi-envelope-fill"></i> <span>Quản lý liên hệ</span></a></li>
                    <li class="sidebar-item"><a href="account-profile.php" class='sidebar-link'><i class="bi bi-people-fill"></i> <span>Quản lý tài khoản</span></a></li>
                    <li class="sidebar-item"><a href="../logout.php" class='sidebar-link'><i class="bi bi-box-arrow-right"></i> <span>Đăng xuất</span></a></li>
                       </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main">
        <header class="mb-3"><a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a></header>
        
        <div class="page-heading"><h3>Quản lý phòng trọ</h3></div>
        
        <div class="page-content">
            <?php if ($msg !== ''): ?>
                <div class="alert alert-primary"><?php echo $msg; ?></div>
            <?php endif; ?>
            
            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th><th>Tiêu đề</th><th>Giá</th><th>Khu vực</th><th>Chủ trọ</th><th>Trạng thái</th><th>Hành động</th>
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
                                                <a href="?approve=<?php echo (int) $row['ID']; ?>" class="btn btn-sm btn-success">Duyệt</a>
                                                <a href="?reject=<?php echo (int) $row['ID']; ?>" class="btn btn-sm btn-danger">Từ chối</a>
                                            <?php elseif ((int) $row['approve'] === 1): ?>
                                                <a href="?hide=<?php echo (int) $row['ID']; ?>" class="btn btn-sm btn-secondary">Ẩn</a>
                                            <?php else: ?>
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
    </div>
</div>
<script src="assets/compiled/js/app.js"></script>
</body>
</html>