<?php
// config/database.php

$host = 'localhost';
$db   = 'shelton_hire';
$user = 'root';
$pass = ''; // Default XAMPP/WAMP password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
// $options = [
//     PDO::ATTR_ERR_MODE            => PDO::ERR_MODE_EXCEPTION,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//     PDO::ATTR_EMULATE_PREPARES   => false,
// ];

try {
     $pdo = new PDO($dsn, $user, $pass);
} catch (\PDOException $e) {
     // In a real app, log this error and show a generic message
     die("Database connection failed: " . $e->getMessage());
}
?>
