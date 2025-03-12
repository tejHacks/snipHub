<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config/db.php';

// Function to sanitize user input
function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

if (isset($_POST['login'])) {
    $email = cleanInput($_POST['email']);
    $password = cleanInput($_POST['password']);

    // Check if fields are empty
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../views/login.php");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
        header("Location: ../views/login.php");
        exit();
    }

    // Prepare & execute query to check for user
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user exists, verify password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['success'] = "Welcome back, {$user['username']}!";
        header("Location: ../views/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password!";
        header("Location: ../views/login.php");
        exit();
    }
}
?>
