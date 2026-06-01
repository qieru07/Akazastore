<?php
require_once __DIR__ . '/config.php';

/**
 * Fungsi untuk menembak API VIP Reseller guna memproses top-up game otomatis.
 * 
 * @param string $layanan Kode produk/layanan dari VIP Reseller (contoh: ML100)
 * @param string $target Nomor tujuan / User ID game (contoh: 12345678(1234))
 * @return array Hasil respon dari API
 */
function kirim_topup_vip($layanan, $target) {
    // Validasi input
    if (empty($layanan) || empty($target)) {
        return [
            'success' => false,
            'message' => 'Layanan atau nomor target tidak boleh kosong.'
        ];
    }

    $api_id = VIP_API_ID;
    $api_key = VIP_API_KEY;
    
    // Validasi kredensial kosong
    if ($api_id === 'PASTE_API_ID_KAMU_DISINI' || $api_key === 'PASTE_API_KEY_KAMU_DISINI') {
        return [
            'success' => false,
            'message' => 'Silakan isi API ID dan API Key VIP Reseller di file config.php terlebih dahulu.'
        ];
    }

    // Formula Signature VIP Reseller: md5(API_ID + API_KEY)
    $sign = md5($api_id . $api_key);

    // Siapkan Payload data pesanan
    $payload = [
        'key' => $api_key,
        'sign' => $sign,
        'type' => 'order',
        'service' => $layanan,
        'data_no' => $target
    ];

    // Mulai request ke API VIP Reseller
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, VIP_API_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Menghindari kendala SSL saat running di localhost

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        return [
            'success' => false,
            'message' => 'Koneksi API Error: ' . $err
        ];
    }

    $result = json_decode($response, true);
    
    // Cek status respon dari VIP Reseller
    if (isset($result['result']) && $result['result'] == true) {
        return [
            'success' => true,
            'trx_id' => $result['data']['trxid'] ?? null,
            'message' => $result['data']['note'] ?? 'Pesanan berhasil diproses oleh VIP Reseller.',
            'raw' => $result
        ];
    } else {
        return [
            'success' => false,
            'message' => $result['data']['message'] ?? 'Gagal memproses pesanan di VIP Reseller.',
            'raw' => $result
        ];
    }
}
?>
