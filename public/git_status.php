<?php
header('Content-Type: text/plain');

$cwd = dirname(__DIR__);

function run_cmd($cmd) {
    global $cwd;
    echo "========================================\n";
    echo "Executing: $cmd\n";
    echo "========================================\n";
    $output = [];
    $retval = 0;
    exec("cd /d " . escapeshellarg($cwd) . " && " . $cmd . " 2>&1", $output, $retval);
    echo implode("\n", $output) . "\n";
    echo "Exit Code: $retval\n\n";
}

run_cmd("git status");
run_cmd("git log -n 3");
run_cmd("git remote -v");
