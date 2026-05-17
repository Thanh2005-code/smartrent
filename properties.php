<?php
session_start();
require_once __DIR__ . '/includes/helpers.php';
include 'connect.php';

$district = isset($_GET['district']) ? trim((string) $_GET['district']) : '';
$price = isset($_GET['price']) ? trim((string) $_GET['price']) : '';
$utility = isset($_GET['utility']) ? trim((string) $_GET['utility']) : '';
$search = smartrent_build_motel_search($district, $price, $utility);

$sql = "SELECT motel.*, districts.Name AS district_name, user.Name AS owner_name
        FROM motel
        JOIN districts ON motel.district_id = districts.ID
        JOIN user ON motel.user_id = user.ID
        WHERE " . $search['sql'] . "
        ORDER BY motel.created_at DESC";
$stmt = $conn->prepare($sql);
if ($search['types'] !== '') {
    $stmt->bind_param($search['types'], ...$search['params']);
}
$stmt->execute();
$list_res = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>Smartrent - Danh sách phòng trọ</title>

    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-villa-agency.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
  </head>

<body>

  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <div class="sub-header">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-8">
          <ul class="info">
            <li><i class="fa fa-envelope"></i> hotro@smartrent.vn</li>
            <li><i class="fa fa-map"></i> Trường Đại học Vinh, Nghệ An</li>
          </ul>
        </div>
        <div class="col-lg-4 col-md-4">
          <ul class="social-links">
            <li><a href="#"><i class="fab fa-facebook"></i></a></li>
            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <a href="index.php" class="logo">
                        <h1>Smartrent</h1>
                    </a>
                    <ul class="nav">
                      <li><a href="index.php">Trang chủ</a></li>
                      <li><a href="properties.php" class="active">Phòng trọ</a></li>
                      <li><a href="contact.php">Liên hệ</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="profile.php"><i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['fullname']); ?></a></li>
                        <li><a href="logout.php" style="background-color: #f35525; color: #fff; border-radius: 25px; padding: 8px 20px !important;">Đăng xuất</a></li>
                    <?php else: ?>
                        <li><a href="login.php"><i class="fa fa-sign-in-alt"></i> Đăng nhập</a></li>
                    <?php endif; ?>
                  </ul>   
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    </nav>
            </div>
        </div>
    </div>
  </header>
  <div class="page-heading header-text">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <span class="breadcrumb"><a href="index.php">Trang chủ</a> / Danh sách phòng</span>
          <h3>Phòng trọ tin cậy</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="section properties">
    <div class="container">
      <ul class="properties-filter">
        <li>
          <a class="is_active" href="#!" data-filter="*">Tất cả</a>
        </li>
        <li>
          <a href="#!" data-filter=".adv">Phòng khép kín</a>
        </li>
        <li>
          <a href="#!" data-filter=".str">Nhà nguyên căn</a>
        </li>
        <li>
          <a href="#!" data-filter=".rac">Chung cư mini</a>
        </li>
      </ul>
      <div class="row properties-box">
        <?php if ($list_res->num_rows === 0): ?>
        <div class="col-lg-12">
          <p class="text-center">Không tìm thấy phòng trọ phù hợp. Vui lòng thử lại với tiêu chí khác.</p>
        </div>
        <?php else: ?>
        <?php while ($row = $list_res->fetch_assoc()):
            $filter_class = smartrent_category_filter_class($row['category_id']);
            $image_file = smartrent_motel_image($row['images']);
            $category_name = smartrent_category_name($row['category_id']);
        ?>
        <div class="col-lg-4 col-md-6 align-self-center mb-30 properties-items col-md-6 <?php echo $filter_class; ?>">
          <div class="item">
            <a href="property-details.php?id=<?php echo (int) $row['ID']; ?>"><img src="assets/images/<?php echo htmlspecialchars($image_file); ?>" alt=""></a>
            <span class="category"><?php echo htmlspecialchars($category_name); ?></span>
            <h6><?php echo number_format((int) $row['price'], 0, ',', '.'); ?> VNĐ</h6>
            <h4><a href="property-details.php?id=<?php echo (int) $row['ID']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h4>
            <ul>
              <li>Người đăng: <span><?php echo htmlspecialchars($row['owner_name']); ?></span></li>
              <li>Khu vực: <span><?php echo htmlspecialchars($row['district_name']); ?></span></li>
              <li>Diện tích: <span><?php echo (int) $row['area']; ?>m2</span></li>
              <li>Tiện ích: <span><?php echo htmlspecialchars($row['utilities']); ?></span></li>
            </ul>
            <div class="main-button">
              <a href="property-details.php?id=<?php echo (int) $row['ID']; ?>">Xem chi tiết</a>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <footer>
    <div class="container">
      <div class="col-lg-12">
        <p>Copyright © 2026 Smartrent. Thiết kế bám sát Case Study ĐH Vinh. 
        <br><i>"Chủ trọ nhàn tay phòng đầy mỗi ngày"</i></p>
      </div>
    </div>
  </footer>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/counter.js"></script>
  <script src="assets/js/custom.js"></script>

  </body>
</html>
