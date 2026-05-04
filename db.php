<?php
// ডাটাবেস কানেকশন
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "transform_u";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Database তৈরি
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Enrollments টেবিল
$conn->query("CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    course VARCHAR(100) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Admin টেবিল
$conn->query("CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)");

// Default admin insert (admin / admin123)
$check = $conn->query("SELECT * FROM admins WHERE username='admin'");
if ($check->num_rows == 0) {
    $hashed = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO admins (username, password) VALUES ('admin', '$hashed')");
}

// Contact messages টেবিল
$conn->query("CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(200),
    message TEXT,
    sent_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
?>
