<?php
session_start();
include __DIR__ . "/koneksi.php";

$username = $_SESSION['username'] ?? 'Guest';
$user_id = $_POST['user_id'];
$whatsapp = $_POST['whatsapp'];
$email = $_POST['email'];
$server = isset($_POST['server']) ? $_POST['server'] : '';
$game = $_POST['game'];
$item = $_POST['item'];
$nominal = $_POST['nominal'];
$metode = $_POST['metode'];
$kode_unik = rand(100, 999);
$status = "PENDING"; 

$uid_full = $server ? "$user_id-$server" : $user_id;

mysqli_query($conn, "INSERT INTO transaksi (user_id, whatsapp, email, game, item, nominal, kode_unik, metode, status, username)
VALUES ('$uid_full', '$whatsapp', '$email', '$game', '$item', '$nominal', '$kode_unik', '$metode', '$status', '$username')");

$id = mysqli_insert_id($conn);

header("Location: struk.php?id=$id");
?>