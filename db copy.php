<?php
$host = 'localhost';
$db   = 'kounadis_golf_gps';
$user = 'kounadis_golf_user';
$pass = 'GolfAppSecure2025!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>