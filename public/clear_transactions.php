<?php
header('Content-Type: text/plain');

if (!isset($_GET['key']) || $_GET['key'] !== 'Muhamad07') {
    http_response_code(403);
    die("Access Denied.");
}

$koneksi_path = dirname(__DIR__) . '/api/akaza_topup/koneksi.php';
if (!file_exists($koneksi_path)) {
    die("Error: koneksi.php tidak ditemukan.");
}

require_once $koneksi_path;

echo "========================================\n";
echo "RESET DATABASE TRANSAKSI & REVIEWS\n";
echo "========================================\n";

// 1. Kosongkan tabel reviews (karena menunjuk ke transaksi_id)
if (mysqli_query($conn, "TRUNCATE TABLE reviews")) {
    echo "✅ Berhasil mengosongkan tabel 'reviews' dan mereset counter ke 1.\n";
} else {
    // Fallback jika TRUNCATE gagal (misalnya karena foreign key constraint)
    mysqli_query($conn, "DELETE FROM reviews");
    mysqli_query($conn, "ALTER TABLE reviews AUTO_INCREMENT = 1");
    echo "⚠️ Tabel 'reviews' dikosongkan menggunakan DELETE & ALTER.\n";
}

// 2. Kosongkan tabel transaksi
if (mysqli_query($conn, "TRUNCATE TABLE transaksi")) {
    echo "✅ Berhasil mengosongkan tabel 'transaksi' dan mereset counter ke 1.\n";
} else {
    mysqli_query($conn, "DELETE FROM transaksi");
    mysqli_query($conn, "ALTER TABLE transaksi AUTO_INCREMENT = 1");
    echo "⚠️ Tabel 'transaksi' dikosongkan menggunakan DELETE & ALTER.\n";
}

echo "\n✨ Database Transaksi berhasil dibersihkan dari 1 lagi!\n";
?>
