<?php
// Giữ nguyên các hàm cũ...
function smartrent_avatar_url($avatar) {
    if ($avatar === null || $avatar === '') return '';
    $avatar = trim($avatar);
    if (preg_match('#^https?://#i', $avatar)) return $avatar;
    if (strpos($avatar, '/') !== false || strpos($avatar, '\\') !== false) return str_replace('\\', '/', $avatar);
    return 'uploads/avatars/' . $avatar;
}

function smartrent_verify_recaptcha($secret, $response, $remoteIp = null) {
    if ($secret === '' || $response === '') return false;
    if ($remoteIp === null) $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
    $payload = http_build_query(['secret' => $secret, 'response' => $response, 'remoteip' => $remoteIp]);
    $ctx = stream_context_create(['http' => ['method' => 'POST', 'header' => "Content-Type: application/x-www-form-urlencoded\r\n", 'content' => $payload, 'timeout' => 8]]);
    $result = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $ctx);
    if ($result === false) return false;
    $json = json_decode($result, true);
    return is_array($json) && !empty($json['success']);
}

// CẬP NHẬT TRẠNG THÁI MỚI Ở ĐÂY
function smartrent_approve_label($approve)
{
    $approve = (int) $approve;
    if ($approve === 1) return ['Hiển thị', 'bg-success'];
    if ($approve === 2) return ['Đã ẩn', 'bg-secondary'];
    if ($approve === 3) return ['Đã từ chối', 'bg-danger']; // Trạng thái mới
    return ['Chờ duyệt', 'bg-warning'];
}
function smartrent_motel_image($filename) {
    // Nếu có tên file ảnh và file đó tồn tại trong thư mục
    if (!empty($filename) && file_exists(__DIR__ . '/../assets/images/' . $filename)) {
        return 'assets/images/' . $filename;
    }
    // Nếu không có ảnh hoặc lỗi, trả về ảnh mặc định
    return 'assets/images/property-01.jpg'; 
}
function smartrent_category_name($id) {
    $id = (int)$id;
    if ($id === 1) return 'Phòng khép kín';
    if ($id === 2) return 'Nhà nguyên căn';
    if ($id === 3) return 'Chung cư mini';
    return 'Chưa phân loại';
}
function smartrent_map_embed_url($lating) {
    // Làm sạch khoảng trắng
    $lating = trim((string)$lating);
    
    // Nếu chủ trọ không nhập tọa độ, đặt vị trí mặc định là Trường Đại học Vinh
    if ($lating === '') {
        return 'https://maps.google.com/maps?q=18.673321,105.692279&output=embed';
    }
    
    // Nếu có tọa độ, mã hóa tọa độ an toàn và nối vào cấu trúc link của Google Maps
    $lating = urlencode($lating);
    return "https://maps.google.com/maps?q={$lating}&output=embed";}
?>