<?php
session_start();
// Kiểm tra quyền truy cập Admin (role=2)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header('Location: ../login.php');
    exit();
}
require_once __DIR__ . '/../includes/helpers.php';
include '../connect.php';

// --- XỬ LÝ LOGIC ---
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    // 1: Hiển thị, 2: Ẩn, 3: Từ chối
    if ($action === 'show') {
        $conn->query("UPDATE motel SET approve = 1 WHERE ID = $id");
    } elseif ($action === 'hide') {
        $conn->query("UPDATE motel SET approve = 2 WHERE ID = $id");
    } elseif ($action === 'reject') {
        $conn->query("UPDATE motel SET approve = 3 WHERE ID = $id");
    }
    header("Location: component-card.php"); 
    exit();
}

$sql = "SELECT motel.*, districts.Name AS district_name, user.Name AS owner_name
        FROM motel
        LEFT JOIN districts ON motel.district_id = districts.ID
        LEFT JOIN user ON motel.user_id = user.ID
        ORDER BY motel.created_at DESC";
$list = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tin đăng - Smartrent Admin</title>
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app-dark.css">
</head>
<body>
<script src="assets/static/js/initTheme.js"></script>
<div id="app">
    <!-- Sidebar -->
    <div id="sidebar"><div class="sidebar-wrapper active"><div class="sidebar-header"><div class="logo"><a href="index.php">SMARTRENT</a></div></div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-item"><a href="index.php" class="sidebar-link"><i class="bi bi-grid-fill"></i> <span>Bảng điều khiển</span></a></li>
                <li class="sidebar-item"><a href="table-datatable.php" class="sidebar-link"><i class="bi bi-house-door-fill"></i> <span>Quản lý phòng trọ</span></a></li>
                <li class="sidebar-item active"><a href="component-card.php" class="sidebar-link"><i class="bi bi-file-earmark-text-fill"></i> <span>Quản lý tin đăng</span></a></li>
                <li class="sidebar-item"><a href="admin-contacts.php" class="sidebar-link"><i class="bi bi-envelope-fill"></i> <span>Quản lý liên hệ</span></a></li>
                <li class="sidebar-item"><a href="account-profile.php" class="sidebar-link"><i class="bi bi-people-fill"></i> <span>Quản lý tài khoản</span></a></li>
                <li class="sidebar-item"><a href="../logout.php" class="sidebar-link"><i class="bi bi-box-arrow-right"></i> <span>Đăng xuất</span></a></li>
            </ul>
        </div>
    </div></div>

    <div id="main">
        <header class="mb-3"><a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a></header>
        
        <div class="page-heading"><h3>Quản lý tin đăng</h3></div>
        
        <div class="page-content">
            <section class="section">
                <div class="card">
                    <div class="card-header"><h4>Danh sách tin đăng (<?php echo $list->num_rows; ?>)</h4></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr><th>Tiêu đề</th><th>Giá</th><th>Trạng thái</th><th>Hành động</th></tr>
                                </thead>
                                <tbody>
                                <?php while ($row = $list->fetch_assoc()):
                                    list($lb, $cl) = smartrent_approve_label($row['approve']);
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo number_format((int)$row['price']); ?> đ</td>
                                        <td><span class="badge <?php echo $cl; ?>"><?php echo $lb; ?></span></td>
                                        <td>
                                            <a href="form-layout.php?id=<?php echo $row['ID']; ?>" class="btn btn-sm btn-outline-warning">Sửa</a>
                                            
                                            <?php if ((int)$row['approve'] === 0): ?>
                                                <a href="?action=show&id=<?php echo $row['ID']; ?>" class="btn btn-sm btn-success">Duyệt</a>
                                                <a href="?action=reject&id=<?php echo $row['ID']; ?>" class="btn btn-sm btn-danger">Từ chối</a>
                                            <?php elseif ((int)$row['approve'] === 1): ?>
                                                <a href="?action=hide&id=<?php echo $row['ID']; ?>" class="btn btn-sm btn-secondary">Ẩn</a>
                                            <?php else: ?>
                                                <a href="?action=show&id=<?php echo $row['ID']; ?>" class="btn btn-sm btn-success">Hiện</a>
                                            <?php endif; ?>
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