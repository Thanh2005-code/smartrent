<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header('Location: ../login.php');
    exit();
}
include '../connect.php';

$year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');
$res = $conn->query("SELECT MONTH(created_at) AS m, COUNT(*) AS total
    FROM motel WHERE YEAR(created_at) = $year GROUP BY MONTH(created_at) ORDER BY m");
$months = array_fill(1, 12, 0);
while ($row = $res->fetch_assoc()) {
    $months[(int) $row['m']] = (int) $row['total'];
}
$chart_labels = [];
$chart_data = [];
for ($i = 1; $i <= 12; $i++) {
    $chart_labels[] = 'T' . $i;
    $chart_data[] = $months[$i];
}
$labels_json = json_encode($chart_labels);
$data_json = json_encode($chart_data);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê tin đăng - Smartrent Admin</title>
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
                    <li class="sidebar-item"><a href="component-card.php" class="sidebar-link"><i class="bi bi-file-earmark-text-fill"></i> <span>Quản lý tin đăng</span></a></li>
                    <li class="sidebar-item"><a href="account-profile.php" class="sidebar-link"><i class="bi bi-people-fill"></i> <span>Quản lý tài khoản</span></a></li>
                    <li class="sidebar-item active"><a href="ui-chart-apexcharts.php" class="sidebar-link"><i class="bi bi-bar-chart-fill"></i> <span>Thống kê</span></a></li>
                    <li class="sidebar-item"><a href="../logout.php" class="sidebar-link"><i class="bi bi-box-arrow-right"></i> <span>Đăng xuất</span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="main">
        <header class="mb-3"><a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a></header>
        <div class="page-heading"><h3>Thống kê số tin đăng theo tháng</h3></div>
        <div class="page-content">
            <section class="section">
                <form method="GET" class="mb-3">
                    <label>Năm: </label>
                    <select name="year" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                        <?php for ($y = (int) date('Y'); $y >= (int) date('Y') - 3; $y--): ?>
                        <option value="<?php echo $y; ?>"<?php echo $year === $y ? ' selected' : ''; ?>><?php echo $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </form>
                <div class="card">
                    <div class="card-header"><h4>Số lượng tin đăng trong năm <?php echo $year; ?></h4></div>
                    <div class="card-body">
                        <div id="bar"></div>
                        <table class="table table-sm mt-4">
                            <thead><tr><th>Tháng</th><th>Số tin</th></tr></thead>
                            <tbody>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                            <tr><td>Tháng <?php echo $i; ?></td><td><?php echo $months[$i]; ?></td></tr>
                            <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/compiled/js/app.js"></script>
<script>
new ApexCharts(document.querySelector('#bar'), {
    chart: { type: 'bar', height: 350 },
    series: [{ name: 'Tin đăng', data: <?php echo $data_json; ?> }],
    xaxis: { categories: <?php echo $labels_json; ?> }
}).render();
</script>
</body>
</html>
