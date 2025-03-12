<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username, bio, profile_picture, github_link, website FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['username']); ?>'s Profile</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/dashboard.css">

    <style>
        body {
            background-color: #0a0a0a;
            color: #00ff00;
            font-family: 'Courier New', Courier, monospace;
        }
        .profile-container {
            max-width: 600px;
            margin: auto;
            text-align: center;
            padding: 20px;
            border: 2px solid #00ff00;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.8);
            box-shadow: 0px 0px 10px #00ff00;
        }
        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 2px solid #00ff00;
            box-shadow: 0px 0px 8px #00ff00;
            object-fit: cover;
        }
        .neon-btn {
            border: 1px solid #00ff00;
            color: #00ff00;
            text-shadow: 0px 0px 8px #00ff00;
            background: transparent;
            transition: 0.3s;
        }
        .neon-btn:hover {
            background: #00ff00;
            color: black;
            box-shadow: 0px 0px 15px #00ff00;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="profile-container">
            <img src="<?php echo $user['profile_picture'] ?: 'default.png'; ?>" alt="Profile Picture" class="profile-pic">
            <h1 class="mt-3"><?php echo htmlspecialchars($user['username']); ?></h1>
            <p class="fst-italic">"<?php echo htmlspecialchars($user['bio']) ?: 'No bio yet...'; ?>"</p>

            <div class="mt-3">
                <?php if ($user['github_link']): ?>
                    <a href="<?php echo htmlspecialchars($user['github_link']); ?>" class="btn neon-btn">
                        <i class="fa fa-github"></i> GitHub
                    </a>
                <?php endif; ?>
                <?php if ($user['website']): ?>
                    <a href="<?php echo htmlspecialchars($user['website']); ?>" class="btn neon-btn">
                        <i class="fa fa-globe"></i> Website
                    </a>
                <?php endif; ?>
            </div>

            <div class="mt-4">
                <a href="dashboard.php" class="btn neon-btn"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
            </div>
        </div>
    </div>

</body>
</html>
