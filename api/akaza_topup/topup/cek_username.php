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

// Hitung MD5 Signature sesuai standar VIP Reseller: md5(API_ID + API_KEY)
$sign = md5($api_id . $api_key);

// Siapkan Payload data untuk cek nickname
$payload = [
    'key' => $api_key,
    'sign' => $sign,
    'type' => 'get-nickname',
    'code' => 'mobile-legends', // Kode game MLBB di API VIP Reseller
    'target' => $id,
    'target2' => $server // Zone ID
];

// Request via cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://vip-reseller.co.id/api/game-feature');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass SSL untuk local dev
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); // Paksa gunakan IPv4 agar lebih mudah di-whitelist di VIP Reseller

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

// Logging hasil API untuk keperluan debug admin
$log_msg = "[" . date('Y-m-d H:i:s') . "] ID: $id | Server: $server | cURL Error: " . ($err ?: 'None') . " | Response: " . $response . "\n";
file_put_contents(__DIR__ . '/debug_check.log', $log_msg, FILE_APPEND);

if ($err) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal terhubung ke server provider: ' . $err
    ]);
    exit;
}

$result = json_decode($response, true);

// Evaluasi respon dari VIP Reseller
if (isset($result['result']) && $result['result'] == true) {
    
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
