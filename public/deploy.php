<?php
// Secure deploy runner - auto-deletes itself after use
echo "<h2>Deploying to GitHub...</h2><pre>";

function run($cmd) {
    echo "$ $cmd\n";
    $out = shell_exec($cmd . " 2>&1");
    echo htmlspecialchars($out) . "\n";
}

run("git add -A");
run("git commit -m \"Fix: dynamic API URL + cookie-based auth for Vercel serverless\"");
run("git push origin main");

echo "</pre><h3 style='color:green'>Done! Vercel will auto-deploy now.</h3>";
@unlink(__FILE__);
