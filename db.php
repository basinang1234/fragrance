<?php
// db.php - Database Connection File

$host = "localhost"; // Change if your database is hosted elsewhere
$dbname = "fragrance_haven"; // Database name
$username = "root"; // Database username (change for production)
$password = ""; // Database password (change for production)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
