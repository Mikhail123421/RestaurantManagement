<?php
// Database connection
$host = 'localhost'; // Database host
$dbname = 'restaurant'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 

catch (PDOException $e) {
    die(json_encode(['message' => 'Database connection failed: ' . $e->getMessage()]));
}