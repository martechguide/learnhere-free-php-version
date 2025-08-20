<?php
// Database configuration
$db_host = 'localhost';
$db_name = 'learnherefree';
$db_user = 'root';
$db_pass = '';

// You can also use environment variables for production
if (getenv('DB_HOST')) {
    $db_host = getenv('DB_HOST');
    $db_name = getenv('DB_NAME');
    $db_user = getenv('DB_USER');
    $db_pass = getenv('DB_PASS');
}

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // If database connection fails, create demo data
    $pdo = null;
    error_log("Database connection failed: " . $e->getMessage());
}

// Include demo data functions
require_once __DIR__ . '/demo-data.php';
?>
