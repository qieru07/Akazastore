<?php
// Secure Key Check
if (!isset($_GET['key']) || $_GET['key'] !== 'Muhamad07') {
    http_response_code(403);
    die("Access denied.");
}

header('Content-Type: text/plain');

echo "--- VERCEL LIVE DATABASE CREDENTIALS ---\n\n";
echo "DB_HOST     : " . (getenv('DB_HOST') ?: 'Not Set') . "\n";
echo "DB_DATABASE : " . (getenv('DB_DATABASE') ?: 'Not Set') . "\n";
echo "DB_USERNAME : " . (getenv('DB_USERNAME') ?: 'Not Set') . "\n";
echo "DB_PASSWORD : " . (getenv('DB_PASSWORD') ?: 'Not Set') . "\n";
echo "DB_PORT     : " . (getenv('DB_PORT') ?: 'Not Set') . "\n";
echo "\n----------------------------------------\n";
exit;
