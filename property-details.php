<?php
session_start();
require_once __DIR__ . '/includes/helpers.php';
include 'connect.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: properties.php');
    exit();
}

$sql = "SELECT motel.*, districts.Name AS district_name, user.Name AS owner_name,
               user.Phone AS owner_phone, user.Email AS owner_email, user.Avatar AS owner_avatar
        FROM motel
        JOIN districts ON motel.district_id = districts.ID
        JOIN user ON motel.user_id = user.ID
        WHERE motel.ID = ? AND motel.approve = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$m = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$m) {
    header('Location: properties.php');
    exit();
}

$conn->query('UPDATE motel SET count_view = count_view + 1 WHERE ID = ' . $id);

$image_file = smartrent_motel_image($m['images']);
$category_name = smartrent_category_name($m['category_id']);
$map_url = smartrent_map_embed_url($m['lating'], $m['address']);
$owner_avatar = smartrent_avatar_url($m['owner_avatar']);
$price_fmt = number_format((int) $m['price'], 0, ',', '.');
$created_fmt = date('d/m/Y', strtotime($m['created_at']));
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
            <img src="assets/images/<?php echo htmlspecialchars($image_file); ?>" alt="Hình ảnh phòng trọ">
          </div>
          <div class="main-content">
            <span class="category"><?php echo htmlspecialchars($category_name); ?></span>
            <h4><?php echo htmlspecialchars($m['title']); ?> — <?php echo htmlspecialchars($m['address']); ?>, <?php echo htmlspecialchars($m['district_name']); ?></h4>
            <p><?php echo nl2br(htmlspecialchars($m['description'])); ?></p>
            <p><strong>Giá thuê:</strong> <?php echo $price_fmt; ?> VNĐ/tháng &nbsp;|&nbsp; <strong>Tiện ích:</strong> <?php echo htmlspecialchars($m['utilities']); ?></p>
          </div> 
          <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOwner">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOwner" aria-expanded="true" aria-controls="collapseOwner">
                  Thông tin người đăng
                </button>
              </h2>
              <div id="collapseOwner" class="accordion-collapse collapse show" aria-labelledby="headingOwner" data-bs-parent="#accordionExample">
                <div class="accordion-collapse-body" style="padding: 20px 25px;">
                  <?php if ($owner_avatar !== ''): ?>
                  <img src="<?php echo htmlspecialchars($owner_avatar); ?>" alt="" style="width:64px;height:64px;border-radius:50%;object-fit:cover;margin-bottom:10px;">
                  <?php endif; ?>
                  <strong><?php echo htmlspecialchars($m['owner_name']); ?></strong><br>
                  <i class="fa fa-phone"></i> <?php echo htmlspecialchars($m['phone'] ?: $m['owner_phone']); ?><br>
                  <i class="fa fa-envelope"></i> <?php echo htmlspecialchars($m['owner_email']); ?><br>
                  Ngày đăng: <strong><?php echo $created_fmt; ?></strong> | Lượt xem: <strong><?php echo (int) $m['count_view'] + 1; ?></strong>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingMap">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMap" aria-expanded="false" aria-controls="collapseMap">
                  Bản đồ vị trí
                </button>
              </h2>
              <div id="collapseMap" class="accordion-collapse collapse" aria-labelledby="headingMap" data-bs-parent="#accordionExample">
                <div class="accordion-collapse-body" style="padding: 20px 25px;">
                  <iframe src="<?php echo htmlspecialchars($map_url); ?>" width="100%" height="350" style="border:0;" allowfullscreen loading="lazy"></iframe>
                  <p class="mt-2 mb-0"><i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($m['address']); ?>, <?php echo htmlspecialchars($m['district_name']); ?></p>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                  Vị trí này có thuận tiện di chuyển không?
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-collapse-body" style="padding: 20px 25px;">
                  Phòng tại <strong><?php echo htmlspecialchars($m['address']); ?></strong>, thuộc <strong><?php echo htmlspecialchars($m['district_name']); ?></strong>, gần khu vực Trường Đại học Vinh.
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
                <h4><?php echo (int) $m['area']; ?> m2<br><span>Diện tích phòng</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-02.png" alt="" style="max-width: 52px;">
                <h4><?php echo $price_fmt; ?> đ<br><span>Giá thuê / tháng</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-03.png" alt="" style="max-width: 52px;">
                <h4><?php echo htmlspecialchars($m['district_name']); ?><br><span>Khu vực</span></h4>
              </li>
              <li>
                <img src="assets/images/info-icon-04.png" alt="" style="max-width: 52px;">
                <h4><?php echo htmlspecialchars($category_name); ?><br><span>Loại hình</span></h4>
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