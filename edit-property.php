<?php
session_start(); 
include 'connect.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$motel_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; 

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $title = trim($_POST['title']); 
    $price = (int)$_POST['price'];
    $description = trim($_POST['description']);
    $address = trim($_POST['address']);
    $area = (float)$_POST['area'];
    $district_id = (int)$_POST['district_id'];
    $category_id = (int)$_POST['category_id'];
    $lating = trim($_POST['lating']); 
    $utilities = trim($_POST['utilities']);
    $phone = trim($_POST['phone']);

    // Xử lý upload ảnh vào thư mục assets/images/
    $image_name = $_POST['current_image']; // Mặc định giữ ảnh cũ
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "assets/images/" . $image_name);
    }

    // Câu lệnh SQL update khớp với cấu trúc bảng motel[cite: 7]
    $sql_update = "UPDATE motel SET title=?, price=?, description=?, address=?, area=?, district_id=?, category_id=?, lating=?, utilities=?, phone=?, images=? WHERE ID=? AND user_id=?";
    $stmt = $conn->prepare($sql_update); 
    $stmt->bind_param('sissdiissssii', $title, $price, $description, $address, $area, $district_id, $category_id, $lating, $utilities, $phone, $image_name, $motel_id, $user_id); 

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
    $stmt->close();
}

// 2. LẤY DỮ LIỆU CŨ HIỂN THỊ LÊN FORM
$stmt = $conn->prepare("SELECT * FROM motel WHERE ID = ? AND user_id = ?");
$stmt->bind_param('ii', $motel_id, $user_id);
$stmt->execute();
$motel_data = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <title>Sửa phòng trọ — Smartrent</title>
</head>
<body style="background-color: #f7f7f7;">

<div class="container mt-5">
    <div class="card p-4 mx-auto" style="max-width: 800px;">
        <h3 class="mb-4">Chỉnh sửa chi tiết phòng trọ</h3>
        <form action="edit-property.php?id=<?php echo $motel_id; ?>" method="POST" enctype="multipart/form-data">
            
            <!-- Hidden field để giữ tên ảnh cũ nếu không thay đổi -->
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($motel_data['images'] ?? ''); ?>">

            <div class="mb-3">
                <label class="fw-bold">Tiêu đề</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($motel_data['title'] ?? ''); ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Giá (VNĐ)</label>
                    <input type="number" name="price" class="form-control" value="<?php echo htmlspecialchars($motel_data['price'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Diện tích (m2)</label>
                    <input type="number" name="area" class="form-control" value="<?php echo htmlspecialchars($motel_data['area'] ?? ''); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Địa chỉ</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($motel_data['address'] ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label class="fw-bold">Tọa độ (lating)</label>
                <input type="text" name="lating" class="form-control" value="<?php echo htmlspecialchars($motel_data['lating'] ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label class="fw-bold">Tiện ích</label>
                <textarea name="utilities" class="form-control"><?php echo htmlspecialchars($motel_data['utilities'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Mô tả</label>
                <textarea name="description" class="form-control"><?php echo htmlspecialchars($motel_data['description'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Ảnh hiện tại: <?php echo $motel_data['images']; ?></label><br>
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            <a href="profile.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>

</body>
</html>