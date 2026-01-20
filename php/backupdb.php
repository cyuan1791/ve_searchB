<?php
$cwd = getcwd();
echo $cwd . "\n";
$n      = explode("/", $cwd);
$url    = $n[5];
$config = require_once $cwd . '/../../../../src/yii2/advanced/common/config/main-local.php';
//print_r($config);
$db = $config['components']['db'];
//../../../../../database
$dbDir = $cwd . '/../../../../../database/' . $url;
echo $dbDir . "\n";
if (! is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

$servername = "localhost";
$hostname   = 'localhost';
$username   = $db["username"];
$password   = $db["password"];
$dsn        = $db["dsn"];
$a          = explode('=', $dsn);
$database   = $a[array_key_last($a)];

//mysqldump -u [username] -p [database_name] > backup_file_name.sql

$cmd = "mysqldump -h $hostname -u $username -p$password $database > $dbDir/backup.sql";
echo $cmd;
$output = shell_exec($cmd);
echo $output;
echo "Restore completed successfully.\n";
//$conn = new mysqli("$hostname", "$username", "$password", "$database");
//print_r($conn);