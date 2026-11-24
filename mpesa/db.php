<?php

// Database connection for M-Pesa module
// Supports Docker and your partner's local environment

mysqli_report(
    MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT
);

$host = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$database = getenv('DB_NAME') ?: 'pointofsale';

$password = getenv('DB_PASSWORD');

if ($password === false) {
    $password = '';
}

// Establish MySQL connection
$db = new mysqli(
    $host,
    $username,
    $password,
    $database
);

$db->set_charset('utf8mb4');
