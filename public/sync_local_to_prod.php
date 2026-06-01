<?php
// Script to sync local akaza_topup changes into the akazastore production dirs (Vercel prep)
$source_topup = 'c:/xampp/htdocs/akaza_topup';
$dest_store = 'c:/xampp/htdocs/akazastore';

$files_to_sync = [
    // PHP files
    "$source_topup/helper.php" => "$dest_store/api/akaza_topup/helper.php",
    "$source_topup/struk.php" => "$dest_store/api/akaza_topup/struk.php",
    "$source_topup/topup/ml.php" => "$dest_store/api/akaza_topup/topup/ml.php",
    "$source_topup/topup/ff.php" => "$dest_store/api/akaza_topup/topup/ff.php",
    "$source_topup/topup/pubg.php" => "$dest_store/api/akaza_topup/topup/pubg.php",
    "$source_topup/topup/mc.php" => "$dest_store/api/akaza_topup/topup/mc.php",
    
    // JS files
    "$source_topup/topup/ml.js" => "$dest_store/public/akaza_topup/topup/ml.js",
    "$source_topup/topup/ff.js" => "$dest_store/public/akaza_topup/topup/ff.js",
    "$source_topup/topup/pubg.js" => "$dest_store/public/akaza_topup/topup/pubg.js",
    "$source_topup/topup/mc.js" => "$dest_store/public/akaza_topup/topup/mc.js",
];

echo "<h2>Syncing files...</h2><pre>";

foreach ($files_to_sync as $src => $dst) {
    if (!file_exists($src)) {
        echo "[ERROR] Source file not found: $src\n";
        continue;
    }
    
    // Ensure destination directory exists
    $dst_dir = dirname($dst);
    if (!is_dir($dst_dir)) {
        mkdir($dst_dir, 0777, true);
    }
    
    if (copy($src, $dst)) {
        echo "[SUCCESS] Copied:\n  Src: $src\n  Dst: $dst\n\n";
    } else {
        echo "[FAILED] Copy failed:\n  Src: $src\n  Dst: $dst\n\n";
    }
}

echo "</pre><h3>Sync complete!</h3>";
