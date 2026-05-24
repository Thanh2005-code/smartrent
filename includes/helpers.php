<?php
function smartrent_build_motel_search($district, $price, $area, $utility) {
    $where = ["approve = 1"];
    $bind_params = [];
    $types = "";

    if (!empty($district)) {
        $where[] = "district_id = ?";
        $bind_params[] = (int)$district;
        $types .= "i";
    }
    if (!empty($price)) {
        $where[] = "price <= ?";
        $bind_params[] = (int)$price;
        $types .= "i";
    }
    if (!empty($area)) {
        if ($area == 1) { 
            $where[] = "area < 20"; 
        } elseif ($area == 2) { 
            $where[] = "area BETWEEN 20 AND 30"; 
        } elseif ($area == 3) { 
            $where[] = "area > 30"; 
        }
    }
    if (!empty($utility)) {
        $where[] = "utilities LIKE ?";
        $bind_params[] = "%" . $utility . "%";
        $types .= "s";
    }

    $where_clause = implode(" AND ", $where);
            
    return ['where' => $where_clause, 'types' => $types, 'params' => $bind_params];
}
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

function smartrent_approve_label($approve)
{
    $approve = (int) $approve;
    if ($approve === 1) return ['Hiển thị', 'bg-success'];
    if ($approve === 2) return ['Đã ẩn', 'bg-secondary'];
    if ($approve === 3) return ['Đã từ chối', 'bg-danger']; 
    return ['Chờ duyệt', 'bg-warning'];
}
function smartrent_motel_image($filename) {
    $filename = trim((string)$filename); 

    $path = __DIR__ . '/../assets/images/' . $filename;
    
    if (!empty($filename) && file_exists($path)) {
        return $filename; 
    }
    return 'property-01.jpg'; 
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
    // HÀM GÁN CLASS CSS CHO BỘ LỌC
function smartrent_category_filter_class($id) {
    $id = (int)$id;
    // Cần khớp chính xác với data-filter trong file properties.php
    if ($id === 1) return 'adv'; // Phòng khép kín khớp với data-filter=".adv"
    if ($id === 2) return 'str'; // Nhà nguyên căn khớp với data-filter=".str"
    if ($id === 3) return 'rac'; // Chung cư mini khớp với data-filter=".rac"
    
    return 'all'; // Mặc định
}

?>