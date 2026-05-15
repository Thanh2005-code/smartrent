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
    
    <title>Smartrent - Hệ thống tìm kiếm phòng trọ</title>

    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-villa-agency.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    
    <style>
        .search-form {
            background: #f35525;
            padding: 30px;
            border-radius: 10px;
            margin-top: -50px;
            position: relative;
            z-index: 10;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
        }
        .search-form select, .search-form button {
            height: 50px;
            border-radius: 5px;
            border: none;
        }
    </style>
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
                      <li><a href="index.php" class="active">Trang chủ</a></li>
                      <li><a href="properties.php">Phòng trọ</a></li>
                      <li><a href="contact.php">Liên hệ</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="profile.php"><i class="fa fa-user"></i> <?php echo $_SESSION['fullname']; ?></a></li>
                        <li><a href="logout.php" style="background-color: #f35525; color: #fff; border-radius: 25px; padding: 8px 20px !important;">Đăng xuất</a></li>
                    <?php else: ?>
                        <li><a href="login.php"><i class="fa fa-sign-in-alt"></i> Đăng nhập</a></li>
                    <?php endif; ?>
                  </ul>   
                    <a class='menu-trigger'><span>Menu</span></a>
                </nav>
            </div>
        </div>
    </div>
  </header>

  <div class="main-banner">
    <div class="owl-carousel owl-banner">
      <div class="item item-1">
        <div class="header-text">
          <span class="category">Bến Thủy, <em>Nghệ An</em></span>
          <h2>Tìm phòng nhanh!<br>Gần ĐH Vinh nhất</h2>
        </div>
      </div>
      <div class="item item-2">
        <div class="header-text">
          <span class="category">Trường Thi, <em>Nghệ An</em></span>
          <h2>Đa dạng!<br>Phòng trọ, chung cư mini</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="search-form">
        <form action="search.php" method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="district" class="form-select">
                    <option value="">Địa điểm (Quận/Huyện)</option>
                    <?php 
                    $dist_sql = "SELECT * FROM DISTRICTS";
                    $dist_res = $conn->query($dist_sql);
                    while($d = $dist_res->fetch_assoc()) echo "<option value='".$d['ID']."'>".$d['Name']."</option>";
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="price" class="form-select">
                    <option value="">Khoảng giá</option>
                    <option value="1">Dưới 1.5 triệu</option>
                    <option value="2">1.5 - 3 triệu</option>
                    <option value="3">Trên 3 triệu</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="utility" class="form-select">
                    <option value="">Tiện ích kèm theo</option>
                    <option value="wifi">Có Wifi</option>
                    <option value="ac">Có Điều hòa</option>
                    <option value="parking">Chỗ để xe</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-dark w-100">TÌM PHÒNG NGAY</button>
            </div>
        </form>
    </div>
  </div>

  <div class="section best-deal">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="tabs-content">
            <div class="row">
              <div class="nav-wrapper">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item"><button class="nav-link active" id="new-tab" data-bs-toggle="tab" data-bs-target="#new" type="button">Mới đăng tải</button></li>
                  <li class="nav-item"><button class="nav-link" id="view-tab" data-bs-toggle="tab" data-bs-target="#view" type="button">Xem nhiều nhất</button></li>
                  <li class="nav-item"><button class="nav-link" id="vinh-tab" data-bs-toggle="tab" data-bs-target="#vinh" type="button">Gần ĐH Vinh nhất</button></li>
                </ul>
              </div>              
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="new" role="tabpanel">
                   <div class="row mt-4">
                      <p class="text-center"><i>Dữ liệu phòng mới nhất đang cập nhật...</i></p>
                   </div>
                </div>
                <div class="tab-pane fade" id="view" role="tabpanel">
                   <div class="row mt-4">
                      <p class="text-center"><i>Dữ liệu phòng xem nhiều nhất đang cập nhật...</i></p>
                   </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="properties section">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 offset-lg-4">
          <div class="section-heading text-center">
            <h6>| Gợi ý</h6>
            <h2>Phòng trọ dành cho bạn</h2>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="item">
            <a href="property-details.php"><img src="assets/images/property-01.jpg" alt=""></a>
            <span class="category">Phòng khép kín</span>
            <h6>1.500.000 VNĐ</h6>
            <h4><a href="property-details.php">Số 10 Bạch Liêu, Bến Thủy</a></h4>
            <ul>
              <li>Người đăng: <span>Hoàng Văn Công</span></li>
              <li>Diện tích: <span>20m2</span></li>
              <li>Khu vực: <span>Bến Thủy</span></li>
              <li>Lượt xem: <span>150</span></li>
            </ul>
            <div class="main-button"><a href="property-details.php">Xem chi tiết</a></div>
          </div>
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