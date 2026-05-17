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
