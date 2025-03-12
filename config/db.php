<?php
// config/db.php
$host = 'localhost';
$dbname = 'code_snippets';
$username = 'root';
$password = ''; // Change this if using a password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password,
     [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
