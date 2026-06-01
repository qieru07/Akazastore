<?php
/**
 * Cookie-based authentication helper untuk Vercel (serverless-safe)
 * Menyimpan token di tabel user_sessions di database
 */

if (!function_exists('ensure_sessions_table_exists')) {
    function ensure_sessions_table_exists($conn) {
        mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `user_sessions` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(100) NOT NULL,
            `token` VARCHAR(128) NOT NULL,
            `expires_at` DATETIME NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY `token` (`token`),
            KEY `username` (`username`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
}

if (!function_exists('auth_login')) {
    function auth_login($username) {
        global $conn;
        ensure_sessions_table_exists($conn);
        
        $token = bin2hex(random_bytes(32)); // 64-char random token
        $expires_at = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60)); // 30 hari

        // Hapus session lama user ini
        mysqli_query($conn, "DELETE FROM user_sessions WHERE username='" . mysqli_real_escape_string($conn, $username) . "'");

        // Simpan token baru ke database
        mysqli_query($conn, "INSERT INTO user_sessions (username, token, expires_at) VALUES (
            '" . mysqli_real_escape_string($conn, $username) . "',
            '$token',
            '$expires_at'
        )");

        // Set cookie
        $cookieDomain = '';
        $secure = !empty($_SERVER['HTTPS']) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
        setcookie('akaza_auth', $token, [
            'expires'  => time() + (30 * 24 * 60 * 60),
            'path'     => '/',
            'domain'   => $cookieDomain,
            'secure'   => $secure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        return $token;
    }
}

if (!function_exists('auth_check')) {
    function auth_check() {
        global $conn;

        // Coba dari cookie
        $token = $_COOKIE['akaza_auth'] ?? null;

        // Fallback ke session (localhost)
        if (!$token && isset($_SESSION['username'])) {
            return $_SESSION['username'];
        }

        if (!$token) return null;

        ensure_sessions_table_exists($conn);

        $token_esc = mysqli_real_escape_string($conn, $token);
        $res = mysqli_query($conn, "SELECT username FROM user_sessions WHERE token='$token_esc' AND expires_at > NOW() LIMIT 1");
        if ($res && $row = mysqli_fetch_assoc($res)) {
            return $row['username'];
        }

        return null;
    }
}

if (!function_exists('auth_logout')) {
    function auth_logout() {
        global $conn;
        ensure_sessions_table_exists($conn);
        
        $token = $_COOKIE['akaza_auth'] ?? null;
        if ($token) {
            $token_esc = mysqli_real_escape_string($conn, $token);
            mysqli_query($conn, "DELETE FROM user_sessions WHERE token='$token_esc'");
        }
        // Hapus cookie
        setcookie('akaza_auth', '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        // Juga hapus session lama
        if (session_status() === PHP_SESSION_ACTIVE) {
            unset($_SESSION['username']);
            session_destroy();
        }
    }
}

