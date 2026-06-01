<?php
require_once __DIR__ . '/config.php';

/**
 * Fungsi untuk meminta Snap Token dari Midtrans.
 * 
 * @param string $order_id ID Transaksi dari database kita
 * @param int $amount Total nominal pembayaran (termasuk kode unik)
 * @param string $item_name Nama produk/layanan yang dibeli
 * @param array $customer Data pelanggan (username, email, whatsapp)
 * @return string|null Token Snap Midtrans jika sukses, atau null jika gagal
 */
function dapatkan_snap_token($order_id, $amount, $item_name, $customer) {
    $server_key = MIDTRANS_SERVER_KEY;
    
    // Proteksi jika server key belum diisi
    if ($server_key === 'PASTE_SERVER_KEY_KAMU_DISINI' || empty($server_key)) {
        return null;
    }

    $is_production = MIDTRANS_IS_PRODUCTION;
    
    // Tentukan URL API Midtrans Snap berdasarkan mode (Sandbox vs Production)
    $url = $is_production 
        ? 'https://app.midtrans.com/snap/v1/transactions' 
        : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

    // Buat Payload transaksi sesuai panduan Midtrans Snap API
    $payload = [
        'transaction_details' => [
            'order_id' => 'AKZ-' . $order_id . '-' . rand(10, 99), // Menambah suffix random agar order_id selalu unik saat ditrial ulang
            'gross_amount' => (int) $amount
        ],
        'item_details' => [
            [
                'id' => 'PROD-' . $order_id,
                'price' => (int) $amount,
                'quantity' => 1,
                'name' => substr($item_name, 0, 50)
            ]
        ],
        'customer_details' => [
            'first_name' => $customer['username'] ?? 'Customer',
            'email' => $customer['email'] ?? 'customer@example.com',
            'phone' => $customer['whatsapp'] ?? ''
        ]
    ];

    // Request ke API Midtrans
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass SSL verification untuk server lokal XAMPP
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Basic ' . base64_encode($server_key . ':')
    ]);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        return null;
    }

    $result = json_decode($response, true);
    return $result['token'] ?? null;
}
?>
