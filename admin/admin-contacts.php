<?php
session_start();
// Lùi 1 thư mục để kết nối tới file connect.php ở thư mục gốc
include '../connect.php';

// Kiểm tra quyền truy cập (giả định role 2 là Admin)
if (!isset($_SESSION['user_id']) || (int)$_SESSION['role'] !== 2) { 
    header('Location: ../login.php');
    exit();
}

// Xử lý xóa tin nhắn
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM contacts WHERE id = $id");
    echo "<script>alert('Đã xóa tin nhắn!'); window.location.href='admin-contacts.php';</script>";
}

// Lấy danh sách liên hệ
$contacts = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý liên hệ - Smartrent Admin</title>
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="assets/compiled/css/iconly.css">
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
                        <li class="sidebar-item"><a href="index.php" class='sidebar-link'><i class="bi bi-grid-fill"></i> <span>Bảng điều khiển</span></a></li>
                        <li class="sidebar-item"><a href="table-datatable.php" class="sidebar-link"><i class="bi bi-house-door-fill"></i> <span>Quản lý phòng trọ</span></a></li>
                        <li class="sidebar-item"><a href="component-card.php" class='sidebar-link'><i class="bi bi-file-earmark-text-fill"></i> <span>Quản lý tin đăng</span></a></li>
                        <li class="sidebar-item active"><a href="admin-contacts.php" class='sidebar-link'><i class="bi bi-envelope-fill"></i> <span>Quản lý liên hệ</span></a></li>
                        <li class="sidebar-item"><a href="account-profile.php" class='sidebar-link'><i class="bi bi-people-fill"></i> <span>Quản lý tài khoản</span></a></li>
                        <li class="sidebar-item"><a href="ui-chart-apexcharts.php" class='sidebar-link'><i class="bi bi-bar-chart-fill"></i> <span>Thống kê</span></a></li>
                        <li class="sidebar-item"><a href="../logout.php" class='sidebar-link'><i class="bi bi-box-arrow-right"></i> <span>Đăng xuất</span></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a>
            </header>

            <div class="page-heading">
                <h3>Quản lý tin nhắn liên hệ</h3>
            </div>
            
            <div class="page-content">
                <section class="section">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Tiêu đề</th>
                                        <th>Nội dung</th>
                                        <th>Ngày gửi</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($contacts->num_rows > 0): ?>
                                        <?php while ($row = $contacts->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                            <td><?php echo htmlspecialchars($row['message']); ?></td>
                                            <td><?php echo $row['created_at']; ?></td>
                                            <td>
                                                <a href="admin-contacts.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa tin nhắn này?')">Xóa</a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center">Chưa có liên hệ nào.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted text-center">
                    <p>2026 &copy; Smartrent - <i>"Chủ trọ nhàn tay phòng đầy mỗi ngày"</i></p>
                </div>
            </footer>
        </div>
    </div>
    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/compiled/js/app.js"></script>
</body>
</html>