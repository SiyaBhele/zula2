<?php
// Database Configuration - Works locally and on Render
if (!empty(getenv('DATABASE_URL'))) {
    // Production environment (Render)
    $url = parse_url(getenv('DATABASE_URL'));
    $host = $url['host'] ?? 'localhost';
    $db = ltrim($url['path'] ?? '', '/');
    $user = $url['user'] ?? 'root';
    $pass = $url['pass'] ?? '';
    $port = $url['port'] ?? 3306;
} else {
    // Local development
    $host = 'localhost';
    $db = 'zula_db';
    $user = 'root';
    $pass = '';
    $port = 3306;
}

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>