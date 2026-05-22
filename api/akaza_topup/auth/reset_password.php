<?php
session_start();
include __DIR__ . "/../koneksi.php";

$message = '';
$token = $_GET['token'] ?? '';

if (!$token) {
    $message = 'Token reset tidak ditemukan.';
}

if (isset($_POST['reset_password']) && $token) {
    $password = trim($_POST['password'] ?? '');

    if ($password === '') {
        $message = 'Password baru wajib diisi.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT id, reset_expires FROM user WHERE reset_token = ?');
        mysqli_stmt_bind_param($stmt, 's', $token);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $userId, $expires);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if (!$userId || !$expires || strtotime($expires) < time()) {
            $message = 'Token reset tidak valid atau sudah kadaluwarsa.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $update = mysqli_prepare($conn, 'UPDATE user SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?');
            mysqli_stmt_bind_param($update, 'si', $passwordHash, $userId);
            mysqli_stmt_execute($update);
            mysqli_stmt_close($update);

            header('Location: login.php?reset=success');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="auth-box">
    <h2>Ganti Password</h2>

    <?php if ($message !== ''): ?>
        <div class="error-message"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($token && !$message || isset($_POST['reset_password']) && $message): ?>
        <form method="POST">
            <input type="password" name="password" placeholder="Password baru" required>
            <button type="submit" name="reset_password">Ubah Password</button>
        </form>
    <?php endif; ?>

    <p>Kembali ke <a href="login.php">Login</a></p>
</div>
</body>
</html>
