<?php
$src = __DIR__ . '/akaza_topup/asset/img/';
$dst_games   = __DIR__ . '/images/games/';
$dst_banners = __DIR__ . '/images/banners/';

// Buat folder
if (!is_dir($dst_games))   mkdir($dst_games, 0755, true);
if (!is_dir($dst_banners)) mkdir($dst_banners, 0755, true);

// Game thumbnails
$game_files = [
    'freefire.jpg', 'Mobile Legend.jpg', 'pubg.jpeg', 'mcgogo.jpeg',
    'Roblox.png', '1778699951_img.jpg', '1778699962_img.jpg', '1778699976_img.jpg',
    'MCGG.jpg', 'pubg2.jpeg',
];

// Banner images
$banner_files = [
    'akazabn.png', 'ffbn.png', 'mlbn.png', 'mcbn.png',
    'banner.png', 'promo.png', 'startlight.png', 'store.png',
];

echo "<h3>Menyalin gambar game ke images/games/...</h3>";
foreach ($game_files as $file) {
    if (file_exists($src . $file)) {
        $ok = copy($src . $file, $dst_games . $file);
        echo ($ok ? '✅' : '❌') . " $file<br>";
    } else {
        echo "⚠️ Tidak ditemukan: $file<br>";
    }
}

echo "<h3>Menyalin gambar banner ke images/banners/...</h3>";
foreach ($banner_files as $file) {
    if (file_exists($src . $file)) {
        $ok = copy($src . $file, $dst_banners . $file);
        echo ($ok ? '✅' : '❌') . " $file<br>";
    } else {
        echo "⚠️ Tidak ditemukan: $file<br>";
    }
}

echo "<br><b>✅ Selesai!</b> <a href='akaza_topup/index.php'>→ Buka Beranda</a> &nbsp;|&nbsp; <a href='../akaza_topup/'>→ localhost/akaza_topup/</a>";
