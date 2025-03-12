<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up - SnipHub</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="purple-bg">
    <div class="auth-container">
        <h2><i class="bx bx-code-block"></i> SnipHub Sign Up</h2>
        <?php if(isset($_SESSION['error'])) { echo "<p class='text-danger'>" . $_SESSION['error'] . "</p>"; unset($_SESSION['error']); } ?>
        <?php if(isset($_SESSION['success'])) { echo "<p class='text-success'>" . $_SESSION['success'] . "</p>"; unset($_SESSION['success']); } ?>

        <form action="../controllers/authController.php" method="POST">
            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" name="signup" class="btn btn-primary w-100">Sign Up</button>
        </form>
        <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
    </div>
    <script src="../assets/script.js"></script>
</body>
</html>
