<?php
session_start();
include 'connect.php'; 
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
                        <li><a href="profile.php"><i class="fa fa-user"></i> <?php echo $_SESSION['fullname']; ?></a></li>
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
        <div class="col-lg-4 col-md-6 align-self-center mb-30 properties-items col-md-6 adv">
          <div class="item">
            <a href="property-details.php"><img src="assets/images/property-01.jpg" alt=""></a>
            <span class="category">Phòng khép kín</span>
            <h6>1.500.000 VNĐ</h6>
            <h4><a href="property-details.php">Số 10 Bạch Liêu, Bến Thủy</a></h4>
            <ul>
              <li>Số người ở: <span>2</span></li>
              <li>Phòng tắm: <span>1</span></li>
              <li>Diện tích: <span>20m2</span></li>
              <li>Tầng số: <span>1</span></li>
              <li>Chỗ để xe: <span>Có (Miễn phí)</span></li>
            </ul>
            <div class="main-button">
              <a href="property-details.php">Xem chi tiết</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-30 properties-items col-md-6 str">
          <div class="item">
            <a href="property-details.php"><img src="assets/images/property-02.jpg" alt=""></a>
            <span class="category">Nhà nguyên căn</span>
            <h6>4.500.000 VNĐ</h6>
            <h4><a href="property-details.php">Ngõ 4 Nguyễn Văn Trỗi, Bến Thủy</a></h4>
            <ul>
              <li>Phòng ngủ: <span>3</span></li>
              <li>Phòng tắm: <span>2</span></li>
              <li>Diện tích: <span>75m2</span></li>
              <li>Số tầng: <span>2</span></li>
              <li>Chỗ để xe: <span>Sân rộng</span></li>
            </ul>
            <div class="main-button">
              <a href="property-details.php">Xem chi tiết</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-30 properties-items col-md-6 adv rac">
          <div class="item">
            <a href="property-details.php"><img src="assets/images/property-03.jpg" alt=""></a>
            <span class="category">Chung cư mini</span>
            <h6>3.200.000 VNĐ</h6>
            <h4><a href="property-details.php">Chung cư mini đường Lê Duẩn</a></h4>
            <ul>
              <li>Phòng ngủ: <span>1</span></li>
              <li>Phòng tắm: <span>1</span></li>
              <li>Diện tích: <span>35m2</span></li>
              <li>Tầng số: <span>3</span></li>
              <li>Chỗ để xe: <span>Hầm gửi xe</span></li>
            </ul>
            <div class="main-button">
              <a href="property-details.php">Xem chi tiết</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-30 properties-items col-md-6 str">
          <div class="item">
            <a href="property-details.php"><img src="assets/images/property-04.jpg" alt=""></a>
            <span class="category">Phòng khép kín</span>
            <h6>1.200.000 VNĐ</h6>
            <h4><a href="property-details.php">Khối 5 phường Trường Thi</a></h4>
            <ul>
              <li>Số người ở: <span>2</span></li>
              <li>Phòng tắm: <span>1</span></li>
              <li>Diện tích: <span>18m2</span></li>
              <li>Tầng số: <span>2</span></li>
              <li>Chỗ để xe: <span>Trong nhà</span></li>
            </ul>
            <div class="main-button">
              <a href="property-details.php">Xem chi tiết</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-30 properties-items col-md-6 rac str">
          <div class="item">
            <a href="property-details.php"><img src="assets/images/property-05.jpg" alt=""></a>
            <span class="category">Chung cư mini</span>
            <h6>2.800.000 VNĐ</h6>
            <h4><a href="property-details.php">Căn hộ dịch vụ đường Võ Thị Sáu</a></h4>
            <ul>
              <li>Phòng ngủ: <span>1</span></li>
              <li>Phòng tắm: <span>1</span></li>
              <li>Diện tích: <span>28m2</span></li>
              <li>Tầng số: <span>5 (Thang máy)</span></li>
              <li>Chỗ để xe: <span>Bảo vệ 24/7</span></li>
            </ul>
            <div class="main-button">
              <a href="property-details.php">Xem chi tiết</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-30 properties-items col-md-6 rac adv">
          <div class="item">
            <a href="property-details.php"><img src="assets/images/property-06.jpg" alt=""></a>
            <span class="category">Phòng khép kín</span>
            <h6>1.600.000 VNĐ</h6>
            <h4><a href="property-details.php">Ngõ 12 đường Hermann Gmeiner</a></h4>
            <ul>
              <li>Số người ở: <span>2</span></li>
              <li>Phòng tắm: <span>1</span></li>
              <li>Diện tích: <span>22m2</span></li>
              <li>Tầng số: <span>1</span></li>
              <li>Chỗ để xe: <span>Có camera</span></li>
            </ul>
            <div class="main-button">
              <a href="property-details.php">Xem chi tiết</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-30 properties-items col-md-6 rac str">
          <div class="item">
            <a href="property-details.php"><img src="assets/images/property-03.jpg" alt=""></a>
            <span class="category">Nhà nguyên căn</span>
            <h6>5.000.000 VNĐ</h6>
            <h4><a href="property-details.php">Nhà mặt đường Phong Định Cảng</a></h4>
            <ul>
              <li>Phòng ngủ: <span>4</span></li>
              <li>Phòng tắm: <span>3</span></li>
              <li>Diện tích: <span>90m2</span></li>
              <li>Số tầng: <span>2</span></li>
              <li>Chỗ để xe: <span>Vỉa hè rộng</span></li>
            </ul>
            <div class="main-button">
              <a href="property-details.php">Xem chi tiết</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-30 properties-items col-md-6 rac adv">
          <div class="item">
            <a href="property-details.php"><img src="assets/images/property-02.jpg" alt=""></a>
            <span class="category">Phòng khép kín</span>
            <h6>1.400.000 VNĐ</h6>
            <h4><a href="property-details.php">Gần chợ Quyết, phường Bến Thủy</a></h4>
            <ul>
              <li>Số người ở: <span>2</span></li>
              <li>Phòng tắm: <span>1</span></li>
              <li>Diện tích: <span>20m2</span></li>
              <li>Tầng số: <span>2</span></li>
              <li>Chỗ để xe: <span>Có mái che</span></li>
            </ul>
            <div class="main-button">
              <a href="property-details.php">Xem chi tiết</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 align-self-center mb-30 properties-items col-md-6 rac adv">
          <div class="item">
            <a href="property-details.php"><img src="assets/images/property-01.jpg" alt=""></a>
            <span class="category">Chung cư mini</span>
            <h6>3.500.000 VNĐ</h6>
            <h4><a href="property-details.php">Căn hộ Studio đường Nguyễn Du</a></h4>
            <ul>
              <li>Phòng ngủ: <span>1</span></li>
              <li>Phòng tắm: <span>1</span></li>
              <li>Diện tích: <span>40m2</span></li>
              <li>Tầng số: <span>4</span></li>
              <li>Chỗ để xe: <span>An ninh tốt</span></li>
            </ul>
            <div class="main-button">
              <a href="property-details.php">Xem chi tiết</a>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <ul class="pagination">
            <li><a href="#">1</a></li>
            <li><a class="is_active" href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">>></a></li>
          </ul>
        </div>
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