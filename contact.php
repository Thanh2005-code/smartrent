<?php
// 1. Khởi động phiên làm việc Session để lấy thông tin đăng nhập
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Nhúng file kết nối Cơ sở dữ liệu qlpt
include 'connect.php'; 

// 3. KIỂM TRA BẢO MẬT: Bắt buộc phải đăng nhập mới được vào trang liên hệ
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// 4. Lấy thông tin cá nhân cố định từ Session (được lưu từ file login.php mới)
$session_fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Chưa cập nhật tên';
$session_email    = isset($_SESSION['email']) ? $_SESSION['email'] : 'Chưa cập nhật Email'; 

// 5. Xử lý logic khi người dùng nhấn nút "Gửi lời nhắn"
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_send'])) {
    
    // Lấy dữ liệu Tiêu đề và Nội dung từ Form
    $subject  = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message  = isset($_POST['message']) ? trim($_POST['message']) : '';
    $user_id  = $_SESSION['user_id'];

    // Kiểm tra ràng buộc dữ liệu bắt buộc phía Server
    if (empty($message)) {
        $error = "Vui lòng nhập nội dung tin nhắn bạn cần hỗ trợ hoặc góp ý!";
    } else {
        // Làm sạch dữ liệu đầu vào chống tấn công SQL Injection phá hoại DB
        $fullname_esc = mysqli_real_escape_string($conn, $session_fullname);
        $email_esc    = mysqli_real_escape_string($conn, $session_email);
        $subject_esc  = mysqli_real_escape_string($conn, $subject);
        $message_esc  = mysqli_real_escape_string($conn, $message);

        // Câu lệnh SQL INSERT lưu thông tin liên hệ bám sát cấu trúc bảng contacts
        $sql = "INSERT INTO contacts (fullname, email, subject, message, user_id) 
                VALUES ('$fullname_esc', '$email_esc', '$subject_esc', '$message_esc', $user_id)";

        if ($conn->query($sql) === TRUE) {
            $success = "Gửi lời nhắn liên hệ thành công! Đội ngũ Smartrent sẽ phản hồi bạn trong thời gian sớm nhất.";
            // Xóa nội dung ô nhập để tránh người dùng bấm gửi trùng lặp nhiều lần
            $subject = $message = ''; 
        } else {
            $error = "Đã xảy ra lỗi hệ thống khi lưu dữ liệu: " . $conn->error;
        }
    }
}
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
                      <li><a href="profile.php"><i class="fa fa-user"></i> <?php echo htmlspecialchars($session_fullname); ?></a></li>
                      <li><a href="logout.php" style="background-color: #f35525; color: #fff; border-radius: 25px; padding: 8px 20px !important;">Đăng xuất</a></li>
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
          <form id="contact-form" action="contact.php" method="POST">
            <div class="row">
              
              <div class="col-lg-12 mb-3">
                <?php 
                if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; 
                if(!empty($success)) echo "<div class='alert alert-success'>$success</div>"; 
                ?>
              </div>

              <div class="col-lg-12">
                <fieldset>
                  <label for="name">Họ và tên tài khoản</label>
                  <input type="text" name="name" id="name" 
                         value="<?php echo htmlspecialchars($session_fullname); ?>" 
                         readonly 
                         style="background-color: #f4f4f4; color: #a0a0a0; border: 1px solid #e0e0e0; cursor: not-allowed;">
                </fieldset>
              </div>

              <div class="col-lg-12">
                <fieldset>
                  <label for="email">Địa chỉ Email</label>
                  <input type="email" name="email" id="email" 
                         value="<?php echo htmlspecialchars($session_email); ?>" 
                         readonly 
                         style="background-color: #f4f4f4; color: #a0a0a0; border: 1px solid #e0e0e0; cursor: not-allowed;">
                </fieldset>
              </div>

              <div class="col-lg-12">
                <fieldset>
                  <label for="subject">Tiêu đề</label>
                  <input type="text" name="subject" id="subject" placeholder="Tiêu đề lời nhắn..." value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>">
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="message">Nội dung tin nhắn</label>
                  <textarea name="message" id="message" placeholder="Nhập nội dung bạn cần hỗ trợ hoặc góp ý vào đây..." required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <button type="submit" name="btn_send" id="form-submit" class="orange-button">Gửi lời nhắn</button>
                </fieldset>
              </div>
            </div>
          </form>
        </div>

        <div class="col-lg-12 mt-5">
  <div id="map">
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3780.126048126788!2d105.69316507593163!3d18.659053382463774!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3139cddef3f20f23%3A0x86154b56a284fa6d!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBWaW5o!5e0!3m2!1svi!2s!4v1716075600000!5m2!1svi!2s" 
      width="100%" 
      height="450px" 
      frameborder="0" 
      style="border:0; border-radius: 10px; box-shadow: 0px 0px 15px rgba(0,0,0,0.15);" 
      allowfullscreen="" 
      loading="lazy" 
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
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