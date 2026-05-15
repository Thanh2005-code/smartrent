<?php 
include '../connect.php'; 
include 'includes/header.php'; 
?>
<?php
$total_motel = $conn->query("SELECT COUNT(ID) as total FROM MOTEL")->fetch_assoc()['total'];
$total_pending = $conn->query("SELECT COUNT(ID) as total FROM MOTEL WHERE approve = 0")->fetch_assoc()['total'];
$total_user = $conn->query("SELECT COUNT(ID) as total FROM USER")->fetch_assoc()['total'];
$total_views = $conn->query("SELECT SUM(count_view) as total FROM MOTEL")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Smartrent Admin</title>

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
                        <div class="logo">
                            <a href="index.php">SMARTRENT</a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu Quản trị</li>
                        <li class="sidebar-item active">
                            <a href="index.php" class='sidebar-link'><i class="bi bi-grid-fill"></i> <span>Bảng điều khiển</span></a>
                        </li>
                        <li class="sidebar-item">
                            <a href="motel_manage.php" class='sidebar-link'><i class="bi bi-house-door-fill"></i> <span>Quản lý phòng trọ</span></a>
                        </li>
                        <li class="sidebar-item">
                            <a href="user_manage.php" class='sidebar-link'><i class="bi bi-people-fill"></i> <span>Quản lý tin đăng</span></a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../logout.php" class='sidebar-link'><i class="bi bi-box-arrow-right"></i> <span>Đăng xuất</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a>
            </header>

            <div class="page-heading">
                <h3>Thống kê hệ thống</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-9">
                        <div class="row">
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <h6 class="text-muted font-semibold">Tổng phòng</h6>
                                        <h6 class="font-extrabold mb-0"><?php echo $total_motel; ?></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <h6 class="text-muted font-semibold text-danger">Chờ duyệt</h6>
                                        <h6 class="font-extrabold mb-0"><?php echo $total_pending; ?></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <h6 class="text-muted font-semibold">Thành viên</h6>
                                        <h6 class="font-extrabold mb-0"><?php echo $total_user; ?></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <h6 class="text-muted font-semibold">Lượt xem</h6>
                                        <h6 class="font-extrabold mb-0"><?php echo number_format($total_views); ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Phòng trọ chờ duyệt mới nhất</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-lg">
                                                <thead>
                                                    <tr>
                                                        <th>Tiêu đề</th>
                                                        <th>Giá tiền</th>
                                                        <th>Trạng thái</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql_list = "SELECT title, price FROM MOTEL WHERE approve = 0 ORDER BY created_at DESC LIMIT 5";
                                                    $res_list = $conn->query($sql_list);
                                                    while($row = $res_list->fetch_assoc()):
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $row['title']; ?></td>
                                                        <td><?php echo number_format($row['price']); ?> đ</td>
                                                        <td><span class="badge bg-warning">Đang chờ</span></td>
                                                    </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-3">
                        <div class="card">
                            <div class="card-body py-4 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xl">
                                        <img src="assets/compiled/jpg/1.jpg" alt="Face 1">
                                    </div>
                                    <div class="ms-3 name">
                                        <h5 class="font-bold"><?php echo $_SESSION['fullname']; ?></h5>
                                        <h6 class="text-muted mb-0">Quản trị viên</h6>
                                    </div>
                                </div>
                            </div>
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

           <?php include 'includes/footer.php'; ?>