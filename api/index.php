<?php

// Vercel serverless environment is read-only except for /tmp.
// We dynamically create directories in /tmp for Laravel's compilation, sessions, and cache.
$storagePaths = [
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/testing',
    '/tmp/storage/logs',
];

foreach ($storagePaths as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

// Forward request to Laravel's main index.php
require __DIR__ . '/../public/index.php';
