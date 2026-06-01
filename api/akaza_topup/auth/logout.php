<?php
include __DIR__ . "/../koneksi.php";
require_once __DIR__ . "/../auth_helper.php";

auth_logout();

header('Location: login.php');
exit;
