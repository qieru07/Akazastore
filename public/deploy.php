<?php
// One-time secure deployer
echo "<h2>Running final Git deploy for Topup & Dashboard user auth...</h2><pre>";

function run($cmd) {
    echo "$ $cmd\n";
    $out = shell_exec($cmd . " 2>&1");
    echo htmlspecialchars($out) . "\n";
}

run("git add -A");
run("git commit -m \"Fix: Top-up files, proses_topup and user dashboard to use cookie-based token auth\"");
run("git push origin main");

echo "</pre><h3 style='color:green'>Done! Vercel is deploying these final fixes.</h3>";
@unlink(__FILE__);
