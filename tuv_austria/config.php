<?php
// Database configuration
$host = 'pdb1053.awardspace.net';
$dbname = '4628195_austria';
$user = '4628195_austria';
$pass = 'Tawfik112'; // Set your MySQL root password here

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
