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
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $host . "/api";
    }
}

if (!function_exists('get_image_base_url')) {
    function get_image_base_url() {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        
        // Cek jika berada di localhost (XAMPP)
        if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
            if (strpos($uri, '/akazastore/') !== false) {
                return "http://" . $host . "/akazastore/public/images";
            }
            return "http://" . $host . "/public/images";
        }
        
        // Di Vercel (Production)
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . $host . "/images";
    }
}
?>
