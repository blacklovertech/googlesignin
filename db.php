<?php
// Database connection settings (adjust according to your database configuration)
$host = 'localhost';
$dbname = 'demo';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Database connection failed: ' . $e->getMessage();
}
?>
