<?php
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USERNAME') ?: 'root';
$db_pass = getenv('DB_PASSWORD') ?: '';
$db_name = getenv('DB_DATABASE') ?: 'akaza_topup';
$db_port = getenv('DB_PORT') ?: '3306';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Auto-patch: pastikan kolom 'catatan' ada di tabel transaksi untuk log pengiriman otomatis
$check_col = mysqli_query($conn, "SHOW COLUMNS FROM `transaksi` LIKE 'catatan'");
if ($check_col && mysqli_num_rows($check_col) == 0) {
    mysqli_query($conn, "ALTER TABLE `transaksi` ADD `catatan` TEXT NULL");
}
?>