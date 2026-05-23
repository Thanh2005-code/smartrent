<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header('Location: ../login.php');
    exit();
}
require_once __DIR__ . '/../includes/helpers.php';
include '../connect.php';

$user_id = isset($_GET['user_id']) ? trim((string) $_GET['user_id']) : '';
$price = isset($_GET['price']) ? trim((string) $_GET['price']) : '';
$sort = isset($_GET['sort']) ? trim((string) $_GET['sort']) : 'created_desc';
$keyword = isset($_GET['keyword']) ? trim((string) $_GET['keyword']) : '';

$where = ['1=1'];
$types = '';
$params = [];

if ($user_id !== '') {
    $where[] = 'motel.user_id = ?';
    $types .= 'i';
    $params[] = (int) $user_id;
}
if ($price === '1') {
    $where[] = 'motel.price < 1500000';
} elseif ($price === '2') {
    $where[] = 'motel.price >= 1500000 AND motel.price <= 3000000';
} elseif ($price === '3') {
    $where[] = 'motel.price > 3000000';
}
if ($keyword !== '') {
    $where[] = 'motel.title LIKE ?';
    $types .= 's';
    $params[] = '%' . $keyword . '%';
}

$order = 'motel.created_at DESC';
if ($sort === 'created_asc') {
    $order = 'motel.created_at ASC';
} elseif ($sort === 'price_asc') {
    $order = 'motel.price ASC';
} elseif ($sort === 'price_desc') {
    $order = 'motel.price DESC';
}

$sql = "SELECT motel.*, districts.Name AS district_name, user.Name AS owner_name
        FROM motel
        LEFT JOIN districts ON motel.district_id = districts.ID
        LEFT JOIN user ON motel.user_id = user.ID
        WHERE " . implode(' AND ', $where) . " ORDER BY $order";
$stmt = $conn->prepare($sql);
if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$list = $stmt->get_result();
$users = $conn->query('SELECT ID, Name FROM user ORDER BY Name');
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
    <div id="sidebar">
        <div class="sidebar-wrapper active">
            <div class="sidebar-header position-relative"><div class="logo"><a href="index.php">SMARTRENT</a></div></div>
            <div class="sidebar-menu">
                <ul class="menu">
                    <li class="sidebar-title">Menu Quản trị</li>
                    <li class="sidebar-item"><a href="index.php" class="sidebar-link"><i class="bi bi-grid-fill"></i> <span>Bảng điều khiển</span></a></li>
                     <li class="sidebar-item"><a href="table-datatable.php" class="sidebar-link"><i class="bi bi-house-door-fill"></i> <span>Quản lý phòng trọ</span></a></li>
                    <li class="sidebar-item active"><a href="component-card.php" class="sidebar-link"><i class="bi bi-file-earmark-text-fill"></i> <span>Quản lý tin đăng</span></a></li>
                    <li class="sidebar-item"><a href="account-profile.php" class="sidebar-link"><i class="bi bi-people-fill"></i> <span>Quản lý tài khoản</span></a></li>
                    <li class="sidebar-item"><a href="ui-chart-apexcharts.php" class="sidebar-link"><i class="bi bi-bar-chart-fill"></i> <span>Thống kê</span></a></li>
                    <li class="sidebar-item"><a href="../logout.php" class="sidebar-link"><i class="bi bi-box-arrow-right"></i> <span>Đăng xuất</span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="main">
        <header class="mb-3"><a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a></header>
        <div class="page-heading"><h3>Quản lý tin đăng</h3></div>
        <div class="page-content">
            <section class="section">
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET" class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label>Tài khoản đăng</label>
                                <select name="user_id" class="form-select">
                                    <option value="">Tất cả</option>
                                    <?php while ($u = $users->fetch_assoc()): ?>
                                    <option value="<?php echo (int) $u['ID']; ?>"<?php echo $user_id === (string) $u['ID'] ? ' selected' : ''; ?>><?php echo htmlspecialchars($u['Name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Khoảng giá</label>
                                <select name="price" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="1"<?php echo $price === '1' ? ' selected' : ''; ?>>&lt; 1.5tr</option>
                                    <option value="2"<?php echo $price === '2' ? ' selected' : ''; ?>>1.5-3tr</option>
                                    <option value="3"<?php echo $price === '3' ? ' selected' : ''; ?>>&gt; 3tr</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Sắp xếp</label>
                                <select name="sort" class="form-select">
                                    <option value="created_desc"<?php echo $sort === 'created_desc' ? ' selected' : ''; ?>>Mới nhất</option>
                                    <option value="created_asc"<?php echo $sort === 'created_asc' ? ' selected' : ''; ?>>Cũ nhất</option>
                                    <option value="price_asc"<?php echo $sort === 'price_asc' ? ' selected' : ''; ?>>Giá tăng</option>
                                    <option value="price_desc"<?php echo $sort === 'price_desc' ? ' selected' : ''; ?>>Giá giảm</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Từ khóa</label>
                                <input type="text" name="keyword" class="form-control" value="<?php echo htmlspecialchars($keyword); ?>">
                            </div>
                            <div class="col-md-2 d-flex gap-2">
    <button type="submit" class="btn btn-primary w-50">Lọc</button>
    <a href="form-layout.php" class="btn btn-success w-50"><i class="bi bi-plus-lg"></i> Thêm</a>
</div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><h4>Danh sách tin đăng (<?php echo $list->num_rows; ?>)</h4></div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Người đăng</th>
                                    <th>Giá</th>
                                    <th>Khu vực</th>
                                    <th>Ngày đăng</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                           <?php while ($row = $list->fetch_assoc()):
    list($lb, $cl) = smartrent_approve_label($row['approve']);
?>
    <tr>
        <td><a href="form-layout.php?id=<?php echo (int) $row['ID']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></td>
        <td><?php echo htmlspecialchars($row['owner_name'] ?? ''); ?></td>
        <td><?php echo number_format((int) $row['price']); ?> đ</td>
        <td><?php echo htmlspecialchars($row['district_name'] ?? ''); ?></td>
        <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
        <td><span class="badge <?php echo $cl; ?>"><?php echo $lb; ?></span></td>
        
        <td>
            <a href="form-layout.php?id=<?php echo (int) $row['ID']; ?>" class="btn btn-sm btn-outline-warning">
                <i class="bi bi-pencil-fill"></i> Sửa
            </a>
        </td>
    </tr> <?php endwhile; ?>
    
                            </tbody>
                        </table>
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
