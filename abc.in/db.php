<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "wedding";  // <-- नया DB name

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection Failed: " . $e->getMessage());
}
?>
<!-- CREATE DATABASE IF NOT EXISTS wedding;
USE wedding;

CREATE TABLE gallery_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page VARCHAR(100) NOT NULL,      -- किस page पे show होगी (like 'about')
    year INT NOT NULL,
    month VARCHAR(20) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    title VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); -->



<!-- CREATE TABLE IF NOT EXISTS images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) DEFAULT NULL,
    filename VARCHAR(255) NOT NULL,
    page_slug VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    month TINYINT NOT NULL,
    is_visible TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); -->
