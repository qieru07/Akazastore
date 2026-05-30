<?php
// Secure Key Check
if (!isset($_GET['key']) || $_GET['key'] !== 'Muhamad07') {
    http_response_code(403);
    die("Access denied. Please provide the correct key.");
}

require_once __DIR__ . '/koneksi.php';

// Set headers for download
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="akazastore_production_backup_' . date('Y-m-d') . '.sql"');

// Get all tables
$tables = [];
$result = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_row($result)) {
    $tables[] = $row[0];
}

$sql_dump = "-- AkazaStore Database Dump\n";
$sql_dump .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
$sql_dump .= "-- Host: " . getenv('DB_HOST') . "\n\n";
$sql_dump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

foreach ($tables as $table) {
    // Exclude users table to avoid overwriting localhost credentials, or dump it if needed
    // We can dump all tables, but set structure and insert
    
    // Get table creation statement
    $create_res = mysqli_query($conn, "SHOW CREATE TABLE `$table`");
    $create_row = mysqli_fetch_row($create_res);
    
    $sql_dump .= "DROP TABLE IF EXISTS `$table`;\n";
    $sql_dump .= $create_row[1] . ";\n\n";
    
    // Get table records
    $data_res = mysqli_query($conn, "SELECT * FROM `$table`");
    $num_fields = mysqli_num_fields($data_res);
    
    while ($row = mysqli_fetch_row($data_res)) {
        $sql_dump .= "INSERT INTO `$table` VALUES(";
        for ($i = 0; $i < $num_fields; $i++) {
            if (isset($row[$i])) {
                // Escape string values
                $escaped = mysqli_real_escape_string($conn, $row[$i]);
                $sql_dump .= "'" . $escaped . "'";
            } else {
                $sql_dump .= "NULL";
            }
            if ($i < ($num_fields - 1)) {
                $sql_dump .= ",";
            }
        }
        $sql_dump .= ");\n";
    }
    $sql_dump .= "\n\n";
}

$sql_dump .= "SET FOREIGN_KEY_CHECKS=1;\n";

echo $sql_dump;
exit;
