<?php
header('Content-Type: text/plain');

if (!isset($_GET['key']) || $_GET['key'] !== 'Muhamad07') {
    http_response_code(403);
    die("Access Denied.");
}

$cwd = dirname(__DIR__); // Points to c:\xampp\htdocs\akazastore

function run_cmd($cmd) {
    global $cwd;
    echo "========================================\n";
    echo "Executing: $cmd\n";
    echo "========================================\n";
    $output = [];
    $retval = 0;
    // On Windows, use cd /d to change drive & directory
    exec("cd /d " . escapeshellarg($cwd) . " && " . $cmd . " 2>&1", $output, $retval);
    echo implode("\n", $output) . "\n";
    echo "Exit Code: $retval\n\n";
}

run_cmd("git add .");
run_cmd('git commit -m "fix: kalkulator front-end files and navigation"');
run_cmd("git push origin main"); // Or whatever branch is default
