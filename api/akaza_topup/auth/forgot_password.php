<?php
session_start();
include __DIR__ . "/../koneksi.php";

$message = '';
$usernameValue = '';
$emailValue = '';

if (isset($_POST['reset_request'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $usernameValue = htmlspecialchars($username);
    $emailValue = htmlspecialchars($email);

    if ($username === '' || $email === '') {
        $message = 'Username dan email wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Email tidak valid.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT id FROM user WHERE username = ? AND email = ?');
        mysqli_stmt_bind_param($stmt, 'ss', $username, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $userId);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if (!$userId) {
            $message = 'Username atau email tidak ditemukan.';
        } else {
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', time() + 3600);

            $update = mysqli_prepare($conn, 'UPDATE user SET reset_token = ?, reset_expires = ? WHERE id = ?');
            mysqli_stmt_bind_param($update, 'ssi', $token, $expires, $userId);
            mysqli_stmt_execute($update);
            mysqli_stmt_close($update);

            $host = $_SERVER['HTTP_HOST'];
            $path = dirname($_SERVER['PHP_SELF']);
            $resetUrl = "http://$host$path/reset_password.php?token=$token";

            $subject = 'Reset Password AkazaStore';
            $body = "Halo $username,\n\nSilakan klik tautan berikut untuk mereset password Anda:\n$resetUrl\n\nTautan ini berlaku selama 1 jam.\n\nJika Anda tidak meminta reset password, abaikan pesan ini.";
            $headers = "From: noreply@akazastore.local\r\n";

            // Suppress mail warnings and always show reset link for development
            $mailSent = @mail($email, $subject, $body, $headers);
            $message = '<div class="reset-link-title">Link reset password Anda</div>' .
                '<a class="reset-link-url" href="' . $resetUrl . '" target="_blank">' . $resetUrl . '</a>' .
                '<div class="reset-link-note">Klik tautan di atas untuk mereset password. Tautan berlaku 1 jam.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="auth-box">
    <h2>Lupa Password</h2>

    <?php if ($message !== ''): ?>
        <div class="reset-link-box"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required value="<?= $usernameValue ?>">
        <input type="email" name="email" placeholder="Email" required value="<?= $emailValue ?>">
        <button type="submit" name="reset_request">Kirim Link Reset</button>
    </form>

    <p>Kembali ke <a href="login.php">Login</a></p>
</div>
</body>
</html>
