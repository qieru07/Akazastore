<?php
/**
 * Midtrans HTTP Notification Webhook Handler
 * 
 * Endpoint ini digunakan untuk menerima pembaruan status pembayaran otomatis dari Midtrans.
 * Jika status pembayaran Lunas, sistem akan otomatis melakukan pengiriman (top-up) game
 * ke akun pembeli melalui API VIP Reseller.
 */

header('Content-Type: application/json');

require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vip_helper.php';

// 1. Ambil data JSON dari Midtrans
$rawPayload = file_get_contents('php://input');
$notification = json_decode($rawPayload, true);

if (!$notification) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Payload tidak valid']);
    exit;
}

// 2. Ambil parameter penting untuk verifikasi signature
$order_id = $notification['order_id'] ?? '';
$status_code = $notification['status_code'] ?? '';
$gross_amount = $notification['gross_amount'] ?? '';
$signature_key_from_midtrans = $notification['signature_key'] ?? '';

// 3. Verifikasi Signature Key Midtrans untuk aspek keamanan
// Rumus: sha512(order_id + status_code + gross_amount + server_key)
$server_key = MIDTRANS_SERVER_KEY;
$local_signature = hash('sha512', $order_id . $status_code . $gross_amount . $server_key);

if ($local_signature !== $signature_key_from_midtrans) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Tanda tangan (Signature) tidak sah']);
    exit;
}

// 4. Ekstrak database ID dari order_id (Format kita: AKZ-[id_transaksi]-[suffix])
$order_parts = explode('-', $order_id);
if (count($order_parts) < 2) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Format Order ID tidak dikenal']);
    exit;
}
$transaksi_id = intval($order_parts[1]);

// 5. Cari data transaksi di database kita
$trx_query = mysqli_query($conn, "SELECT * FROM transaksi WHERE id = '$transaksi_id' LIMIT 1");
$trx = mysqli_fetch_assoc($trx_query);

if (!$trx) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan di database']);
    exit;
}

// 6. Evaluasi status transaksi dari Midtrans
$transaction_status = $notification['transaction_status'] ?? '';
$payment_type = $notification['payment_type'] ?? '';
$fraud_status = $notification['fraud_status'] ?? '';

$is_payment_success = false;

if ($transaction_status == 'capture') {
    if ($fraud_status == 'challenge') {
        // Pembayaran terindikasi fraud, butuh review manual di dashboard Midtrans
        $is_payment_success = false;
    } else if ($fraud_status == 'accept') {
        // Pembayaran kartu kredit sukses
        $is_payment_success = true;
    }
} else if ($transaction_status == 'settlement') {
    // Pembayaran e-wallet (GoPay/ShopeePay) / QRIS / Bank Transfer sukses
    $is_payment_success = true;
} else if ($transaction_status == 'pending') {
    // Menunggu pembayaran
    mysqli_query($conn, "UPDATE transaksi SET status = 'PENDING' WHERE id = '$transaksi_id'");
} else if (in_array($transaction_status, ['deny', 'expire', 'cancel'])) {
    // Pembayaran gagal / kedaluwarsa / dibatalkan
    mysqli_query($conn, "UPDATE transaksi SET status = 'Gagal' WHERE id = '$transaksi_id'");
}

// 7. Jika pembayaran SUKSES dan status transaksi di web kita belum Lunas, proses top-up!
if ($is_payment_success && $trx['status'] !== 'Lunas') {
    // a. Update status pembayaran menjadi Lunas di database kita
    mysqli_query($conn, "UPDATE transaksi SET status = 'Lunas' WHERE id = '$transaksi_id'");

    // b. Cari kode produk (provider_code) untuk item game ini di database Laravel
    $game_name_clean = mysqli_real_escape_string($conn, $trx['game']);
    $item_name_clean = mysqli_real_escape_string($conn, $trx['item']);

    // Query untuk mengambil data produk berdasarkan relasi name di tabel games & products
    $product_query = mysqli_query($conn, "
        SELECT products.provider_code 
        FROM products 
        JOIN games ON products.game_id = games.id 
        WHERE games.name = '$game_name_clean' AND products.name = '$item_name_clean' 
        LIMIT 1
    ");

    $provider_code = null;
    if ($product_query && mysqli_num_rows($product_query) > 0) {
        $product = mysqli_fetch_assoc($product_query);
        $provider_code = $product['provider_code'];
    }

    $topup_status_log = "";

    // c. Jika Kode Provider diisi, langsung tembak API VIP Reseller!
    if (!empty($provider_code)) {
        // Panggil fungsi kirim topup otomatis
        $res = kirim_topup_vip($provider_code, $trx['user_id']);

        if ($res['success']) {
            // Sukses top-up otomatis! Catat TRX ID dari provider ke kolom catatan/log jika diperlukan
            $vip_trx_id = $res['trx_id'];
            $topup_status_log = "Pembayaran sukses & Top-up otomatis sukses via VIP Reseller (TrxID: $vip_trx_id)";
            
            // Catat log sukses ke db
            mysqli_query($conn, "UPDATE transaksi SET catatan = '$topup_status_log' WHERE id = '$transaksi_id'");
        } else {
            // Gagal top-up otomatis (misal saldo VIP habis / salah kode)
            $error_msg = mysqli_real_escape_string($conn, $res['message']);
            $topup_status_log = "Pembayaran Lunas, tapi Top-up otomatis GAGAL: $error_msg. Butuh tindakan admin manual!";
            
            // Catat log error ke db agar admin bisa review
            mysqli_query($conn, "UPDATE transaksi SET catatan = '$topup_status_log' WHERE id = '$transaksi_id'");
        }
    } else {
        // Tidak ada kode provider (Produk manual)
        $topup_status_log = "Pembayaran sukses. Produk ini disetting manual (tidak ada Kode Provider).";
        mysqli_query($conn, "UPDATE transaksi SET catatan = '$topup_status_log' WHERE id = '$transaksi_id'");
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Status pembayaran berhasil diperbarui',
        'log' => $topup_status_log
    ]);
    exit;
}

echo json_encode([
    'status' => 'success',
    'message' => 'Status transaksi diproses: ' . $transaction_status
]);
exit;
?>
