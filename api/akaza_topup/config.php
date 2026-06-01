<?php
// ==========================================
// KODE KONFIGURASI AKAZASTORE (MIDTRANS & VIP RESELLER)
// ==========================================

// Helper function untuk meload file .env secara manual di lingkungan vanilla PHP
if (!function_exists('loadEnvManual')) {
    function loadEnvManual($path) {
        if (!file_exists($path)) {
            return;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Abaikan komentar
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            // Cari kecocokan key=value
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                // Hapus tanda kutip jika ada
                if (preg_match('/^"(.*)"$/', $value, $matches)) {
                    $value = $matches[1];
                } elseif (preg_match('/^\'(.*)\'$/', $value, $matches)) {
                    $value = $matches[1];
                }
                
                // Set ke environment variable jika belum diset
                if (!getenv($name)) {
                    putenv("$name=$value");
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }
}

// Load file .env dari root directory
loadEnvManual(__DIR__ . '/../../.env');

// --- KONFIGURASI MIDTRANS ---
define('MIDTRANS_SERVER_KEY', getenv('MIDTRANS_SERVER_KEY') ?: '');
define('MIDTRANS_CLIENT_KEY', getenv('MIDTRANS_CLIENT_KEY') ?: '');
define('MIDTRANS_IS_PRODUCTION', filter_var(getenv('MIDTRANS_IS_PRODUCTION') ?: false, FILTER_VALIDATE_BOOLEAN));

// --- KONFIGURASI VIP RESELLER ---
define('VIP_API_ID', getenv('VIP_API_ID') ?: '');
define('VIP_API_KEY', getenv('VIP_API_KEY') ?: '');

// --- URL ENDPOINT ---
define('VIP_API_URL', 'https://vip-reseller.co.id/api/prepaid');
?>
