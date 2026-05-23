<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header('Location: ../login.php');
    exit();
}
require_once __DIR__ . '/../includes/helpers.php';
include '../connect.php';

// --- XỬ LÝ LOGIC CẬP NHẬT TRẠNG THÁI ---
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

// Lấy danh sách tin
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
    <title>Quản lý tin đăng - Smartrent Admin</title>
    <link rel="stylesheet" href="assets/compiled/css/app.css">
</head>
<body>
<div id="app">
    <div id="main">
        <div class="page-content">
            <section class="section">
                <div class="card">
                    <div class="card-header"><h4>Danh sách tin đăng (<?php echo $list->num_rows; ?>)</h4></div>
                    <div class="card-body">
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
                                        
                                        <?php if ((int)$row['approve'] === 0): // Chờ duyệt ?>
                                            <a href="?action=show&id=<?php echo $row['ID']; ?>" class="btn btn-sm btn-success">Duyệt</a>
                                            <a href="?action=reject&id=<?php echo $row['ID']; ?>" class="btn btn-sm btn-danger">Từ chối</a>
                                        <?php elseif ((int)$row['approve'] === 1): // Đang hiển thị ?>
                                            <a href="?action=hide&id=<?php echo $row['ID']; ?>" class="btn btn-sm btn-secondary">Ẩn</a>
                                        <?php else: // Đã ẩn hoặc Bị từ chối ?>
                                            <a href="?action=show&id=<?php echo $row['ID']; ?>" class="btn btn-sm btn-success">Hiện</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
</body>
</html>