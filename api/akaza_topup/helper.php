<?php
if (!function_exists('get_api_base_url')) {
    function get_api_base_url() {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        
        // Cek jika berada di localhost (XAMPP)
        if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
            if (strpos($uri, '/akazastore/') !== false) {
                return "http://" . $host . "/akazastore/public/api";
            }
            return "http://" . $host . "/public/api";
        }
        
        // Di Vercel (Production)
        $protocol = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' || !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $host . "/api";
    }
}

if (!function_exists('get_image_base_url')) {
    function get_image_base_url() {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        
        // Cek jika berada di localhost (XAMPP)
        // Gambar selalu disimpan oleh admin Laravel ke akazastore/public/images
        if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
            return "http://" . $host . "/akazastore/public/images";
        }
        
        // Di Vercel (Production)
        $protocol = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' || !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $host . "/images";
    }
}

if (!function_exists('get_video_base_url')) {
    function get_video_base_url() {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        
        // Cek jika berada di localhost (XAMPP)
        if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
            if (strpos($uri, '/akazastore/') !== false) {
                return "http://" . $host . "/akazastore/public/videos";
            }
            return "http://" . $host . "/akaza_topup/videos";
        }
        
        // Di Vercel (Production)
        $protocol = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' || !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $host . "/videos";
    }
}

if (!function_exists('get_game_details')) {
    function get_game_details($game_id, $default_name, $default_img) {
        // Import connection
        require_once __DIR__ . '/koneksi.php';
        global $conn;

        if (!$game_id || !$conn) {
            return ['name' => $default_name, 'image' => $default_img];
        }
        
        $game_id_clean = mysqli_real_escape_string($conn, $game_id);
        $query = mysqli_query($conn, "SELECT * FROM games WHERE id='$game_id_clean' LIMIT 1");
        if ($query && $row = mysqli_fetch_assoc($query)) {
            // Dynamic correction for Roblox (broad match)
            $name_lower = strtolower($row['name'] ?? '');
            $slug_lower = strtolower($row['slug'] ?? '');
            if (strpos($name_lower, 'roblox') !== false || strpos($slug_lower, 'roblox') !== false || $slug_lower === 'rb') {
                $row['thumbnail'] = 'Roblox.png';
                @mysqli_query($conn, "UPDATE games SET thumbnail = 'Roblox.png' WHERE id = " . intval($row['id']));
            }
            
            $thumbnail = $row['thumbnail'] ?? '';
            if (str_starts_with($thumbnail, 'http')) {
                $game_img = $thumbnail;
            } else {
                $game_img = get_image_base_url() . '/games/' . $thumbnail;
            }
            return [
                'name' => $row['name'],
                'image' => $game_img
            ];
        }
        
        return ['name' => $default_name, 'image' => $default_img];
    }
}
?>
