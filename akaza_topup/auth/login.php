<?php
session_start();
include "../koneksi.php";

$infoMessage = '';
if (isset($_GET['reset']) && $_GET['reset'] === 'success') {
    $infoMessage = 'Password berhasil direset. Silakan login.';
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_prepare($conn, 'SELECT password FROM user WHERE username = ?');
    mysqli_stmt_bind_param($query, 's', $username);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $storedPassword);
    mysqli_stmt_fetch($query);
    mysqli_stmt_close($query);

    if ($storedPassword && (password_verify($password, $storedPassword) || $password === $storedPassword)) {
        $_SESSION['username'] = $username;
        header("Location: ../index.php");
        exit;
    } else {
        echo "<script>alert('Login gagal! Username atau password salah.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Akaza</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="auth-box">
    <h2>Login</h2>

    <?php if ($infoMessage !== ''): ?>
        <div class="error-message"><?= $infoMessage ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <div class="lupa">
            <a href="forgot_password.php">Lupa Password?</a>
            </div>
        <button type="submit" name="login">Login</button>
        <div class="Regist">
            <p class="regist-text">Belum punya akun?
            <a href="registrasi.php" class="register-link">Daftar</a></p>
        </div>
    </form>
</div>
</body>
</html>