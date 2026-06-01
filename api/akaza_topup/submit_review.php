<?php
include __DIR__ . "/koneksi.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$transaksi_id = intval($_POST['transaksi_id'] ?? 0);
$username     = trim($_POST['username'] ?? '');
$game         = trim($_POST['game'] ?? '');
$rating       = intval($_POST['rating'] ?? 0);
$komentar     = trim($_POST['komentar'] ?? '');

// Validasi input
if (!$transaksi_id || !$username || !$game || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

if (strlen($komentar) > 500) {
    echo json_encode(['success' => false, 'message' => 'Komentar terlalu panjang (maks 500 karakter)']);
    exit;
}

// Cek apakah transaksi ini sudah pernah review
$cek = mysqli_query($conn, "SELECT id FROM reviews WHERE transaksi_id = $transaksi_id");
if (mysqli_num_rows($cek) > 0) {
    echo json_encode(['success' => false, 'message' => 'Transaksi ini sudah pernah memberikan ulasan']);
    exit;
}

// Buat tabel jika belum ada
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `transaksi_id` INT NOT NULL,
    `username` VARCHAR(100) NOT NULL,
    `game` VARCHAR(100) NOT NULL,
    `rating` TINYINT(1) NOT NULL DEFAULT 5,
    `komentar` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Simpan ke database
$username_esc  = mysqli_real_escape_string($conn, $username);
$game_esc      = mysqli_real_escape_string($conn, $game);
$komentar_esc  = mysqli_real_escape_string($conn, $komentar);

$insert = mysqli_query($conn, "INSERT INTO reviews (transaksi_id, username, game, rating, komentar)
    VALUES ($transaksi_id, '$username_esc', '$game_esc', $rating, '$komentar_esc')");

if ($insert) {
    echo json_encode(['success' => true, 'message' => 'Ulasan berhasil disimpan, terima kasih!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan: ' . mysqli_error($conn)]);
}
exit;
