<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config/db.php';

// Function to sanitize user input
function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

// Handle user registration
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['signup'])) {
    $username = cleanInput($_POST['username']);
    $email = cleanInput($_POST['email']);
    $password = cleanInput($_POST['password']);

    // Check if fields are empty
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../views/signup.php");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
        header("Location: ../views/signup.php");
        exit();
    }

    // Check if username is already taken
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Username already exists!";
        header("Location: ../views/signup.php");
        exit();
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Email already exists!";
        header("Location: ../views/signup.php");
        exit();
    }

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user into database
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $hashedPassword])) {
        $_SESSION['success'] = "Account created successfully! Please log in.";
        header("Location: ../views/login.php");
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong! Please try again.";
        header("Location: ../views/signup.php");
        exit();
    }
}
?>
