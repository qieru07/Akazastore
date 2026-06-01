<?php
/**
 * Script untuk memvalidasi Game ID dan mengambil Nickname Player secara real-time.
 * 100% Menggunakan API VIP Reseller tanpa Mock/Tiruan.
 * Jika ID/Server salah atau tidak ditemukan, otomatis menampilkan error Player Not Found.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';

$id = isset($_POST['id']) ? trim($_POST['id']) : '';
$server = isset($_POST['server']) ? trim($_POST['server']) : '';

if (empty($id)) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak boleh kosong']);
    exit;
}

$api_id = VIP_API_ID;
$api_key = VIP_API_KEY;

// Proteksi jika API Key belum dikonfigurasi
if ($api_id === 'PASTE_API_ID_KAMU_DISINI' || $api_key === 'PASTE_API_KEY_KAMU_DISINI' || empty($api_id) || empty($api_key)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'API VIP Reseller belum dikonfigurasi di config.php'
    ]);
    exit;
}

/**
 * Fungsi pembantu untuk menembak API VIP Reseller
 */
function check_nickname_vip($api_id, $api_key, $id, $server, $code) {
    $sign = md5($api_id . $api_key);
    $payload = [
        'key' => $api_key,
        'sign' => $sign,
        'type' => 'get-nickname',
        'code' => $code,
        // Kirimkan semua variasi parameter agar kompatibel dengan versi API lama maupun baru
        'target' => $id,
        'target2' => $server,
        'data_no' => $id,
        'data_zone' => $server,
        'additional_target' => $server
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://vip-reseller.co.id/api/game-feature');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass SSL untuk local dev
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); // Paksa gunakan IPv4 agar lebih mudah di-whitelist
    
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    return [
        'success' => !$err,
        'error' => $err,
        'response' => $response
    ];
}

// 1. Coba request pertama menggunakan kode 'mobile-legends' (plural)
$res = check_nickname_vip($api_id, $api_key, $id, $server, 'mobile-legends');

$success = false;
$final_response = '';
$result = null;

if ($res['success']) {
    $final_response = $res['response'];
    $result = json_decode($final_response, true);
    if (isset($result['result']) && $result['result'] == true) {
        $success = true;
    }
}

// 2. Jika gagal, coba request kedua (self-healing fallback) menggunakan kode 'mobile-legend' (singular)
if (!$success) {
    $res_fallback = check_nickname_vip($api_id, $api_key, $id, $server, 'mobile-legend');
    if ($res_fallback['success']) {
        $result_fallback = json_decode($res_fallback['response'], true);
        if (isset($result_fallback['result']) && $result_fallback['result'] == true) {
            $success = true;
            $final_response = $res_fallback['response'];
            $result = $result_fallback;
        } elseif (!$result) {
            // Jika kedua-duanya gagal, rekam respon yang gagal
            $final_response = $res_fallback['response'];
            $result = $result_fallback;
        }
    }
}

// Logging hasil API untuk keperluan debug admin
$log_msg = "[" . date('Y-m-d H:i:s') . "] ID: $id | Server: $server | Response: " . $final_response . "\n";
file_put_contents(__DIR__ . '/debug_check.log', $log_msg, FILE_APPEND);

// Evaluasi respon sukses akhir
if ($success && isset($result['result']) && $result['result'] == true) {
    // Ekstrak nickname secara aman
    $nickname = '';
    if (isset($result['data'])) {
        if (is_array($result['data'])) {
            $nickname = $result['data']['nickname'] ?? ($result['data']['name'] ?? '');
        } else {
            $nickname = $result['data'];
        }
    }
    
    // Pastikan nickname yang didapat bukan indikasi "not found"
    if (!empty($nickname) && strtolower($nickname) !== 'player not found' && strtolower($nickname) !== 'not_found') {
        echo json_encode([
            'status' => 'success',
            'username' => $nickname
        ]);
        exit;
    }
}

// Jika gagal atau tidak ditemukan di server VIP Reseller
$error_response_msg = $result['data']['message'] ?? ($result['message'] ?? 'Player Not Found / Username tidak ditemukan');
echo json_encode([
    'status' => 'error',
    'message' => $error_response_msg
]);
exit;
?>
