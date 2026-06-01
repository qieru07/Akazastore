<?php
$src = __DIR__ . '/akaza_topup/asset/img/';
$dst_banners = __DIR__ . '/images/banners/';

if (!is_dir($dst_banners)) {
    mkdir($dst_banners, 0755, true);
}

// Map the DB expected names to the local filenames we have
$banner_mappings = [
    'store.png' => '1778701555_store.png',
    'startlight.png' => '1778703302_startlight.png',
    'ffbn.png' => 'ff_banner.png',
    'banner.png' => 'banner.png'
];

echo "<h3>Duplicating and mapping banner images...</h3>";
foreach ($banner_mappings as $source_file => $target_file) {
    if (file_exists($src . $source_file)) {
        $ok = copy($src . $source_file, $dst_banners . $target_file);
        echo ($ok ? '✅' : '❌') . " Mapped <b>$source_file</b> to <b>$target_file</b><br>";
    } else {
        echo "⚠️ Source file not found: $source_file<br>";
    }
}

echo "<br><b>✅ Setup complete!</b> <a href='../akaza_topup/'>→ Go to localhost/akaza_topup/</a>";
