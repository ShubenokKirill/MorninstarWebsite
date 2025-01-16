<?php
$ini = parse_ini_file('config.ini');
try {
    $conn = new PDO("mysql:host=" . $ini['host'] . ";dbname=" . $ini['db_name'], $ini['db_user'], $ini['db_password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
