<?php
session_start();
include __DIR__ . "/../koneksi.php";

$message = '';
$usernameValue = '';
$emailValue = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $usernameValue = htmlspecialchars($username);
    $emailValue = htmlspecialchars($email);

    if ($username === '' || $password === '') {
        $message = 'Username dan password wajib diisi.';
    } else {
        $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '" . mysqli_real_escape_string($conn, $username) . "'");

        if ($result && mysqli_num_rows($result) > 0) {
            $message = 'Username sudah terpakai, silakan pilih yang lain.';
        } else {
            $columnsRes = mysqli_query($conn, "SHOW COLUMNS FROM user");
            $hasEmail = false;
            while ($col = mysqli_fetch_assoc($columnsRes)) {
                if (strtolower($col['Field']) === 'email') {
                    $hasEmail = true;
                    break;
                }
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, $hasEmail
                ? 'INSERT INTO user (username, email, password) VALUES (?, ?, ?)'
                : 'INSERT INTO user (username, password) VALUES (?, ?)');

            if ($stmt) {
                if ($hasEmail) {
                    mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $passwordHash);
                } else {
                    mysqli_stmt_bind_param($stmt, 'ss', $username, $passwordHash);
                }

                if (mysqli_stmt_execute($stmt)) {
                    header('Location: login.php');
                    exit;
                }
                $message = 'Gagal membuat akun. Coba lagi.';
            } else {
                $message = 'Terjadi kesalahan pada database.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <link rel="stylesheet" href="registrasi.css">
</head>
<body>

<div class="auth-box">
    <h2>Daftar Akun</h2>

    <?php if ($message !== ''): ?>
        <div class="error-message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="nama">
            <input type="text" name="username" placeholder="Username" required value="<?= $usernameValue ?>">
        </div>
        <div class="email">
            <input type="email" name="email" placeholder="Email" required value="<?= $emailValue ?>">
        </div>
        <div class="password">
            <input type="password" name="password" placeholder="Password" required>

        </div>
        <button type="submit" name="register">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login</a></p>
</div>

</body>
</html>
