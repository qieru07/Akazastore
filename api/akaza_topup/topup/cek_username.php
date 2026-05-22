<?php
header('Content-Type: application/json');

$id = isset($_POST['id']) ? trim($_POST['id']) : '';
$server = isset($_POST['server']) ? trim($_POST['server']) : '';

if (empty($id) || empty($server)) {
    echo json_encode(['status' => 'error', 'message' => 'ID dan Server tidak boleh kosong']);
    exit;
}

// Simulasi delay jaringan/API (500ms)
usleep(500000); 

// Validasi sederhana
if (strlen($id) < 5 || strlen($server) < 4) {
    echo json_encode(['status' => 'error', 'message' => 'ID atau Server tidak valid (Player Not Found)']);
    exit;
}

// Mock Username berdasarkan ID dan Server
$mockUsernames = ["AkazaPlayer", "FannyDarat", "SavageMaker", "IndoPride", "Tzy.Player", "ProGamer99", "MiyaSantuy", "KaguraPayung"];
$hash = crc32($id . $server);
$username = $mockUsernames[$hash % count($mockUsernames)] . "_" . substr($id, -3);

echo json_encode([
    'status' => 'success',
    'username' => $username
]);
?>
