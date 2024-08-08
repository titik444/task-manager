<?php
// config.php

$host = 'localhost'; // or your database host
$dbname = 'task_manager';
$username = 'root'; // your database username
$password = ''; // your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>