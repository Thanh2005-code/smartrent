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

// --- XỬ LÝ LOGIC ---
if (isset($_GET['delete'])) {
    $del_id = (int) $_GET['delete'];
    $stmt = $conn->prepare('DELETE FROM motel WHERE ID = ?');
    $stmt->bind_param('i', $del_id);
    if ($stmt->execute()) $msg = 'Đã xóa phòng trọ thành công.';
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
if (isset($_GET['reject'])) {
    $rid = (int) $_GET['reject'];
    $conn->query('UPDATE motel SET approve = 3 WHERE ID = ' . $rid);
    $msg = 'Đã từ chối tin đăng.';
}

// Lấy danh sách phòng
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
    <title>Quản lý phòng trọ - Smartrent</title>
    <link rel="stylesheet" href="assets/compiled/css/app.css">
</head>
<body>
<div id="app">
    <div id="main">
        <div class="page-content">
            <?php if ($msg !== ''): ?>
                <div class="alert alert-primary"><?php echo $msg; ?></div>
            <?php endif; ?>
            
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
                                <a href="?approve=<?php echo (int) $row['ID']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Duyệt phòng này?')">Duyệt</a>
                                <a href="?reject=<?php echo (int) $row['ID']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Từ chối phòng này?')">Từ chối</a>
                            <?php elseif ((int) $row['approve'] === 1): ?>
                                <a href="?hide=<?php echo (int) $row['ID']; ?>" class="btn btn-sm btn-secondary" onclick="return confirm('Ẩn phòng này?')">Ẩn</a>
                            <?php elseif ((int) $row['approve'] === 2 || (int) $row['approve'] === 3): ?>
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
</body>
</html>