<?php
// Script to run Git commit and push changes to GitHub (Vercel deploy)
echo "<h2>Deploying changes to GitHub...</h2><pre>";

function run_cmd($cmd) {
    echo "$ $cmd\n";
    $output = shell_exec($cmd . " 2>&1");
    echo htmlspecialchars($output) . "\n";
    return $output;
}

// Stage all changes
run_cmd("git add -A");

// Reset our temporary deployment scripts so they are not committed
run_cmd("git reset public/git_push.php");
run_cmd("git reset public/sync_local_to_prod.php");

// Commit and Push
run_cmd("git commit -m \"Fix payment, rating, and nominal visibility on direct access\"");
run_cmd("git push origin main");

echo "</pre><h3>Deployment attempt completed!</h3>";
