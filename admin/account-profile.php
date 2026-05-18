<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header('Location: ../login.php');
    exit();
}
include '../connect.php';

$msg = '';
$view_id = isset($_GET['view']) ? (int) $_GET['view'] : 0;

if (isset($_GET['delete'])) {
    $del_id = (int) $_GET['delete'];
    if ($del_id !== (int) $_SESSION['user_id']) {
        $chk = $conn->query('SELECT COUNT(*) AS c FROM motel WHERE user_id = ' . $del_id)->fetch_assoc()['c'];
        if ($chk > 0) {
            $msg = 'Không xóa được: tài khoản còn tin đăng phòng.';
        } else {
            $stmt = $conn->prepare('DELETE FROM user WHERE ID = ?');
            $stmt->bind_param('i', $del_id);
            if ($stmt->execute()) {
                $msg = 'Đã xóa tài khoản.';
            }
            $stmt->close();
        }
    } else {
        $msg = 'Không thể xóa tài khoản đang đăng nhập.';
    }
}

if (isset($_POST['btn_add'])) {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = md5($_POST['password'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = (int) ($_POST['role'] ?? 0);
    if ($name && $username && $email && $_POST['password']) {
        $stmt = $conn->prepare('INSERT INTO user (Name, Username, Email, Password, Role, Phone) VALUES (?,?,?,?,?,?)');
        $stmt->bind_param('ssssis', $name, $username, $email, $password, $role, $phone);
        if ($stmt->execute()) {
            $msg = 'Đã thêm tài khoản.';
        } else {
            $msg = 'Thêm thất bại (username/email có thể trùng).';
        }
        $stmt->close();
    } else {
        $msg = 'Vui lòng nhập đủ thông tin tài khoản mới.';
    }
}

$users = $conn->query('SELECT * FROM user ORDER BY ID DESC');
$view_user = null;
if ($view_id > 0) {
    $stmt = $conn->prepare('SELECT * FROM user WHERE ID = ?');
    $stmt->bind_param('i', $view_id);
    $stmt->execute();
    $view_user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tài khoản - Smartrent Admin</title>
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app-dark.css">
</head>
<body>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
            <div class="sidebar-header position-relative"><div class="logo"><a href="index.php">SMARTRENT</a></div></div>
    <div class="sidebar-menu">
        <ul class="menu">
                    <li class="sidebar-title">Menu Quản trị</li>
                    <li class="sidebar-item"><a href="index.php" class="sidebar-link"><i class="bi bi-grid-fill"></i> <span>Bảng điều khiển</span></a></li>
                    <li class="sidebar-item"><a href="component-card.php" class="sidebar-link"><i class="bi bi-file-earmark-text-fill"></i> <span>Quản lý tin đăng</span></a></li>
                    <li class="sidebar-item active"><a href="account-profile.php" class="sidebar-link"><i class="bi bi-people-fill"></i> <span>Quản lý tài khoản</span></a></li>
                    <li class="sidebar-item"><a href="ui-chart-apexcharts.php" class="sidebar-link"><i class="bi bi-bar-chart-fill"></i> <span>Thống kê</span></a></li>
                    <li class="sidebar-item"><a href="../logout.php" class="sidebar-link"><i class="bi bi-box-arrow-right"></i> <span>Đăng xuất</span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="main">
        <header class="mb-3"><a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a></header>
        <div class="page-heading"><h3>Quản lý tài khoản</h3></div>
        <div class="page-content">
            <?php if ($msg !== ''): ?><div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
    <section class="section">
        <div class="row">
                    <div class="col-lg-4">
                <div class="card">
                            <div class="card-header"><h4>Thêm tài khoản</h4></div>
                    <div class="card-body">
                                <form method="POST">
                                    <div class="form-group mb-2"><label>Họ tên</label><input type="text" name="name" class="form-control" required></div>
                                    <div class="form-group mb-2"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                                    <div class="form-group mb-2"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                                    <div class="form-group mb-2"><label>Mật khẩu</label><input type="password" name="password" class="form-control" required></div>
                                    <div class="form-group mb-2"><label>SĐT</label><input type="text" name="phone" class="form-control"></div>
                                    <div class="form-group mb-2"><label>Vai trò</label>
                                        <select name="role" class="form-select">
                                            <option value="0">Người dùng</option>
                                            <option value="1">Admin</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="btn_add" class="btn btn-primary">Thêm</button>
                                </form>
                            </div>
                        </div>
                        <?php if ($view_user): ?>
                        <div class="card mt-3">
                            <div class="card-header"><h4>Chi tiết tài khoản</h4></div>
                    <div class="card-body">
                                <p><strong>ID:</strong> <?php echo (int) $view_user['ID']; ?></p>
                                <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($view_user['Name']); ?></p>
                                <p><strong>Username:</strong> <?php echo htmlspecialchars($view_user['Username']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($view_user['Email']); ?></p>
                                <p><strong>SĐT:</strong> <?php echo htmlspecialchars($view_user['Phone'] ?? ''); ?></p>
                                <p><strong>Vai trò:</strong> <?php echo (int) $view_user['Role'] === 1 ? 'Admin' : 'Người dùng'; ?></p>
                            </div>
                            </div>
                        <?php endif; ?>
                            </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header"><h4>Danh sách tài khoản</h4></div>
                            <div class="card-body table-responsive">
                                <table class="table table-striped">
                                    <thead><tr><th>ID</th><th>Họ tên</th><th>Username</th><th>Email</th><th>Vai trò</th><th>Thao tác</th></tr></thead>
                                    <tbody>
                                    <?php while ($u = $users->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo (int) $u['ID']; ?></td>
                                        <td><?php echo htmlspecialchars($u['Name']); ?></td>
                                        <td><?php echo htmlspecialchars($u['Username']); ?></td>
                                        <td><?php echo htmlspecialchars($u['Email']); ?></td>
                                        <td><?php echo (int) $u['Role'] === 1 ? 'Admin' : 'User'; ?></td>
                                        <td>
                                            <a href="?view=<?php echo (int) $u['ID']; ?>" class="btn btn-sm btn-info">Xem</a>
                                            <a href="?delete=<?php echo (int) $u['ID']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa tài khoản?')">Xóa</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                </div>
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
