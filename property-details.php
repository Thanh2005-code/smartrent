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

    <title>Smartrent - Chi tiết phòng trọ</title>

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
                      <li><a href="property-details.php" class="active">Chi tiết phòng</a></li>
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
          <span class="breadcrumb"><a href="index.php">Trang chủ</a>  /  Chi tiết tin đăng</span>
          <h3>Thông tin phòng trọ</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="single-property section">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="main-image">
            <img src="assets/images/single-property.jpg" alt="Hình ảnh phòng trọ">
          </div>
          <div class="main-content">
            <span class="category">Phòng khép kín</span>
            <h4>Số 10 Đường Bạch Liêu, Phường Bến Thủy, TP. Vinh</h4>
            <p>Chào mừng bạn đến với hệ thống tìm kiếm phòng trọ thông minh <strong>Smartrent</strong> dành riêng cho sinh viên Đại học Vinh. Căn phòng khép kín tại trục đường Bạch Liêu sở hữu vị trí cực kỳ đắc địa, giúp bạn tiết kiệm tối đa thời gian di chuyển đến giảng đường, thư viện và các khu vực tiện ích xung quanh trường.
            
            <br><br>Phòng được trang bị đầy đủ cơ sở vật chất cơ bản, không gian thoáng đãng, an ninh đảm bảo và có camera giám sát 24/7. Để xem trực tiếp phòng hoặc trao đổi thêm thông tin chi tiết với chính chủ, vui lòng nhấn vào nút liên hệ hẹn lịch hoặc sử dụng hòm thư hỗ trợ trực tuyến của chúng tôi.</p>
          </div> 
          <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Vị trí này có thuận tiện di chuyển không?
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-collapse-body" style="padding: 20px 25px;">
                  Cực kỳ thuận tiện! Căn phòng nằm trên trục đường <strong>Bạch Liêu</strong>, ngay sát cổng phụ và khu ký túc xá của <code>Trường Đại học Vinh</code>. Bạn chỉ mất chưa đầy 3 phút đi bộ để đến giảng đường mà không cần lo lắng về vấn đề tắc đường hay phương tiện di chuyển.
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Quy trình đặt cọc và thanh toán như thế nào?
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-collapse-body" style="padding: 20px 25px;">
                  Hợp đồng thuê nhà minh bạch, rõ ràng. Người thuê chỉ cần thực hiện đặt cọc trước 1 tháng tiền phòng để giữ chỗ. Tiền phòng hàng tháng có thể linh hoạt thanh toán qua hình thức chuyển khoản ngân hàng hoặc nộp tiền mặt trực tiếp cho chủ trọ từ ngày 1 đến ngày 5 đầu tháng.
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Tại sao nên tìm phòng qua Smartrent?
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-collapse-body" style="padding: 20px 25px;">
                  Smartrent cam kết 100% các tin đăng phòng trọ đều đã được đội ngũ quản trị viên kiểm duyệt thực tế, thông tin chính xác, hình ảnh trực quan, hỗ trợ cơ chế phát hiện chống gian lận hiệu quả, giúp các bạn sinh viên dễ dàng tìm được không gian sống an toàn và ưng ý nhất.
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="info-table">
            <ul>
              <li>
                <img src="assets/images/info-icon-01.png" alt="" style="max-width: 52px;">
                <h4>20 m2<br><span>Diện tích phòng</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-02.png" alt="" style="max-width: 52px;">
                <h4>Hợp đồng<br><span>Ký kết rõ ràng</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-03.png" alt="" style="max-width: 52px;">
                <h4>Thanh toán<br><span>Linh hoạt hàng tháng</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-04.png" alt="" style="max-width: 52px;">
                <h4>An ninh<br><span>Camera 24/7</span></h4>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="section best-deal">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="section-heading">
            <h6>| Gợi ý tốt nhất</h6>
            <h2>Tìm kiếm lựa chọn phù hợp nhất với bạn!</h2>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="tabs-content">
            <div class="row">
              <div class="nav-wrapper ">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="appartment-tab" data-bs-toggle="tab" data-bs-target="#appartment" type="button" role="tab" aria-controls="appartment" aria-selected="true">Phòng khép kín</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="villa-tab" data-bs-toggle="tab" data-bs-target="#villa" type="button" role="tab" aria-controls="villa" aria-selected="false">Nhà nguyên căn</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="penthouse-tab" data-bs-toggle="tab" data-bs-target="#penthouse" type="button" role="tab" aria-controls="penthouse" aria-selected="false">Chung cư mini</button>
                  </li>
                </ul>
              </div>              
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="appartment" role="tabpanel" aria-labelledby="appartment-tab">
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="info-table">
                        <ul>
                          <li>Diện tích sử dụng <span>20 m2</span></li>
                          <li>Tầng số <span>1</span></li>
                          <li>Số người ở tối đa <span>2 người</span></li>
                          <li>Chỗ để xe máy <span>Có sẵn</span></li>
                          <li>Hình thức trả tiền <span>Chuyển khoản</span></li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <img src="assets/images/deal-01.jpg" alt="">
                    </div>
                    <div class="col-lg-3">
                      <h4>Tổng quan về phòng khép kín</h4>
                      <p>Không gian thiết kế nhỏ gọn, tối ưu công năng sử dụng thích hợp cho các bạn sinh viên ở ghép từ 1 đến 2 người. Khu vực nấu ăn và nhà vệ sinh được bố trí khép kín riêng biệt bên trong phòng để đảm bảo tính riêng tư tuyệt đối.</p>
                      <div class="icon-button">
                        <a href="contact.php"><i class="fa fa-calendar"></i> Hẹn lịch xem phòng</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="villa" role="tabpanel" aria-labelledby="villa-tab">
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="info-table">
                        <ul>
                          <li>Diện tích đất <span>75 m2</span></li>
                          <li>Tổng số tầng <span>2 tầng</span></li>
                          <li>Số phòng ngủ <span>3 phòng</span></li>
                          <li>Sân để xe <span>Rộng rãi</span></li>
                          <li>Hình thức trả tiền <span>Chuyển khoản</span></li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <img src="assets/images/deal-02.jpg" alt="">
                    </div>
                    <div class="col-lg-3">
                      <h4>Chi tiết về nhà nguyên căn</h4>
                      <p>Giải pháp tối ưu và tiết kiệm chi phí dành cho các nhóm bạn thân học cùng lớp hoặc cùng quê muốn ở chung từ 4 đến 6 người. Thiết kế nhiều tầng có sân vườn phơi đồ thông thoáng, tự do giờ giấc sinh hoạt không chung chủ.</p>
                      <div class="icon-button">
                        <a href="contact.php"><i class="fa fa-calendar"></i> Hẹn lịch xem phòng</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="penthouse" role="tabpanel" aria-labelledby="penthouse-tab">
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="info-table">
                        <ul>
                          <li>Diện tích căn hộ <span>35 m2</span></li>
                          <li>Vị trí tầng <span>Tầng 3</span></li>
                          <li>Số phòng ngủ <span>1 phòng</span></li>
                          <li>Khu vực gửi xe <span>Hầm giữ xe</span></li>
                          <li>An ninh tòa nhà <span>Bảo vệ 24/7</span></li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <img src="assets/images/deal-03.jpg" alt="">
                    </div>
                    <div class="col-lg-3">
                      <h4>Thông tin về chung cư mini</h4>
                      <p>Căn hộ dịch vụ cao cấp full nội thất bao gồm giường tủ, điều hòa, tủ lạnh, nóng lạnh và khu vực bếp nấu hiện đại. Thích hợp cho người đi làm hoặc sinh viên mong muốn một không gian sống tiện nghi, yên tĩnh, văn minh.</p>
                      <div class="icon-button">
                        <a href="contact.php"><i class="fa fa-calendar"></i> Hẹn lịch xem phòng</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer-no-gap">
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