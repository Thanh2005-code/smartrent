<?php
function smartrent_avatar_url($avatar)
{
    if ($avatar === null || $avatar === '') {
        return '';
    }
    $avatar = trim($avatar);
    if (preg_match('#^https?://#i', $avatar)) {
        return $avatar;
    }
    if (strpos($avatar, '/') !== false || strpos($avatar, '\\') !== false) {
        return str_replace('\\', '/', $avatar);
    }
    return 'uploads/avatars/' . $avatar;
}


function smartrent_verify_recaptcha($secret, $response, $remoteIp = null)
{
    if ($secret === '' || $response === '') {
        return false;
    }
    if ($remoteIp === null) {
        $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
    }
    $payload = http_build_query([
        'secret' => $secret,
        'response' => $response,
        'remoteip' => $remoteIp,
    ]);
    $ctx = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $payload,
            'timeout' => 8,
        ],
    ]);
    $result = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $ctx);
    if ($result === false) {
        return false;
    }
    $json = json_decode($result, true);
    return is_array($json) && !empty($json['success']);
}

function smartrent_category_name($category_id)
{
    $map = [1 => 'Phòng khép kín', 2 => 'Chung cư mini', 3 => 'Nhà nguyên căn'];
    return $map[(int) $category_id] ?? 'Phòng trọ';
}

function smartrent_category_filter_class($category_id)
{
    $map = [1 => 'adv', 2 => 'rac', 3 => 'str'];
    return $map[(int) $category_id] ?? 'adv';
}

function smartrent_motel_image($images)
{
    if ($images !== null && $images !== '') {
        $path = 'assets/images/' . $images;
        if (file_exists($path)) {
            return $images;
        }
    }
    return 'property-01.jpg';
}

function smartrent_approve_label($approve)
{
    $approve = (int) $approve;
    if ($approve === 1) {
        return ['Hiển thị', 'bg-success'];
    }
    if ($approve === 2) {
        return ['Đã ẩn', 'bg-secondary'];
    }
    return ['Chờ duyệt', 'bg-warning'];
}

function smartrent_build_motel_search($district = '', $price = '', $area = '', $utility = '') 
{
    $where = ['motel.approve = 1'];
    $types = '';
    $params = [];

    if ($district !== '') {
        $where[] = 'motel.district_id = ?';
        $types .= 'i';
        $params[] = (int) $district;
    }

    if ($price === '1') {
        $where[] = 'motel.price < 1500000';
    } elseif ($price === '2') {
        $where[] = 'motel.price >= 1500000 AND motel.price <= 3000000';
    } elseif ($price === '3') {
        $where[] = 'motel.price > 3000000';
    }

    if ($area !== '') {
        if ($area === '1') {
            $where[] = " motel.area < 20";
        } elseif ($area === '2') {
            $where[] = " motel.area BETWEEN 20 AND 30";
        } elseif ($area === '3') {
           $where[] = " motel.area > 30";
        }
    }

    if ($utility === 'wifi') {
        $where[] = "(motel.utilities LIKE '%Wifi%' OR motel.utilities LIKE '%wifi%')";
    } elseif ($utility === 'ac') {
        $where[] = "(motel.utilities LIKE '%Điều hòa%' OR motel.utilities LIKE '%điều hòa%')";
    } elseif ($utility === 'parking') {
        $where[] = "(motel.utilities LIKE '%xe%' OR motel.utilities LIKE '%Xe%' OR motel.utilities LIKE '%để xe%')";
    }

    return [
        'sql' => implode(' AND ', $where),
        'types' => $types,
        'params' => $params,
    ];
}

function smartrent_map_embed_url($lating, $address)
{
    if ($lating !== null && $lating !== '') {
        $parts = array_map('trim', explode(',', $lating));
        if (count($parts) === 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
            return 'https://maps.google.com/maps?q=' . rawurlencode($parts[0] . ',' . $parts[1]) . '&z=15&output=embed';
        }
    }
    if ($address !== null && $address !== '') {
        return 'https://maps.google.com/maps?q=' . rawurlencode($address . ', Vinh, Nghe An') . '&z=15&output=embed';
    }
    return 'https://maps.google.com/maps?q=18.6736,105.6923&z=14&output=embed';
}
