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

    <title>Smartrent - Liên hệ với chúng tôi</title>

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
                      <li><a href="properties.php">Phòng trọ</a></li>
                      <li><a href="contact.php" class="active">Liên hệ</a></li>
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
          <span class="breadcrumb"><a href="index.php">Trang chủ</a>  /  Liên hệ</span>
          <h3>Liên hệ chúng tôi</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="contact-page section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="section-heading">
            <h6>| Liên hệ</h6>
            <h2>Kết nối trực tiếp với đội ngũ hỗ trợ</h2>
          </div>
          <p>Nếu bạn gặp bất kỳ khó khăn nào trong quá trình tìm kiếm phòng trọ hoặc muốn hợp tác đăng tải thông tin căn hộ lên hệ thống Smartrent, hãy kết nối ngay với chúng tôi để nhận được sự hỗ trợ nhanh nhất từ các tư vấn viên. Hệ thống được thiết kế bám sát nhu cầu thực tế của sinh viên Đại học Vinh.</p>
          <div class="row">
            <div class="col-lg-12">
              <div class="item phone">
                <img src="assets/images/phone-icon.png" alt="" style="max-width: 52px;">
                <h6>010-020-0340<br><span>Số điện thoại hotline</span></h6>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="item email">
                <img src="assets/images/email-icon.png" alt="" style="max-width: 52px;">
                <h6>hotro@smartrent.vn<br><span>Email hỗ trợ kĩ thuật</span></h6>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <form id="contact-form" action="" method="post">
            <div class="row">
              <div class="col-lg-12">
                <fieldset>
                  <label for="name">Họ và tên</label>
                  <input type="text" name="name" id="name" placeholder="Nhập họ tên của bạn..." autocomplete="on" required>
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="email">Địa chỉ Email</label>
                  <input type="text" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Nhập Email của bạn..." required="">
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="subject">Tiêu đề</label>
                  <input type="text" name="subject" id="subject" placeholder="Tiêu đề lời nhắn..." autocomplete="on" >
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="message">Nội dung tin nhắn</label>
                  <textarea name="message" id="message" placeholder="Nhập nội dung bạn cần hỗ trợ hoặc góp ý vào đây..."></textarea>
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <button type="submit" id="form-submit" class="orange-button">Gửi lời nhắn</button>
                </fieldset>
              </div>
            </div>
          </form>
        </div>
        <div class="col-lg-12">
          <div id="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3780.1171457850553!2d105.6931557759325!3d18.658763564929853!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1m3!1d3780.1171457850553!2m2!1d105.6957307!2d18.6587636!5e0!3m2!1svi!2s!4v1710000000000!5m2!1svi!2s" width="100%" height="500px" frameborder="0" style="border:0; border-radius: 10px; box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.15);" allowfullscreen=""></iframe>
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