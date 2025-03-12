<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$stmt = $pdo->prepare("SELECT id, title, description, created_at FROM snippets WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$snippets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnipHub Dashboard</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../assets/dashboard.css">

    <style>
    .circle {
        width: 15px;
        height: 15px;
        border-radius: 50%;
    }
    .snippets-list {
        max-height: 500px;
        overflow-y: auto;
    }
    </style>
</head>
<body class="hacker-theme">
    <nav class="navbar navbar-dark bg-black px-3">
        <a class="navbar-brand text-neon" href="#">⚡ SnipHub</a>
        <div class="d-flex">
        <a href="dashboard.php" class="btn btn-outline-neon btn-outline-success ms-2"><i class="fa fa-home"></i> Home</a>
           
            <a href="new_snippet.php" class="btn btn-outline-neon ms-2"><i class="fa fa-plus"></i> New Snippet</a>
            <a href="profile.php" class="btn btn-outline-neon"><i class="fa fa-user"></i> Profile</a>
            <a href="logout.php" class="btn btn-danger ms-2"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>

    <div class="container-fluid mt-5 border border-success border-2 py-2">
        <div class="d-flex justify-content-start gap-2 mb-3">
            <div class="circle bg-danger"></div>
            <div class="circle bg-warning"></div>
            <div class="circle bg-success"></div>
        </div>

         <p class="glitch-text">SnipHub - Your Code Vault!</p>

        <div class="terminal">
            <p class="terminal-text">Last login: <?php echo date("Y-m-d H:i:s"); ?> from localhost</p>
            <p class="terminal-text">> Accessing your snippets...</p>
            <p class="terminal-text">> Loading awesome features...</p>
        </div>

        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search snippets...">
        </div>

        <div class="row mt-5">
            <div class="col-md-8">
                <h2 class="text-neon">Your Snippets</h2>
                <div class="snippets-list" id="snippetContainer">
                    <?php if (empty($snippets)): ?>
                        <p class="terminal-text">> No snippets found. Create your first snippet!</p>
                    <?php else: ?>
                        <?php foreach ($snippets as $snippet): ?>
                            <div class="snippet-card border border-success container-fluid py-4 mt-3">
                                <h3 class="snippet-title">&lt;/&gt; <?php echo htmlspecialchars($snippet['title']); ?></h3>
                                <p class="snippet-desc">📌 <?php echo nl2br(htmlspecialchars($snippet['description'])); ?></p>
                                <p class="snippet-date">🕒 Created: <?php echo $snippet['created_at']; ?></p>
                                
                                <div class="d-flex flex-wrap gap-2">
                                    <form action="view_snippet.php" method="POST">
                                        <input type="hidden" name="snippet_id" value="<?php echo $snippet['id']; ?>">
                                        <button type="submit" class="btn btn-outline-neon">View</button>
                                    </form>
                                    <form action="edit_snippet.php" method="POST">
                                        <input type="hidden" name="snippet_id" value="<?php echo $snippet['id']; ?>">
                                        <button type="submit" class="btn btn-outline-neon">Edit</button>
                                    </form>
                                    <button class="btn btn-outline-neon delete-btn" data-id="<?php echo $snippet['id']; ?>">Delete</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            let query = $(this).val().toLowerCase();
            $('.snippet-card').each(function() {
                let title = $(this).find('.snippet-title').text().toLowerCase();
                let desc = $(this).find('.snippet-desc').text().toLowerCase();
                $(this).toggle(title.includes(query) || desc.includes(query));
            });
        });
    });
    </script>

</body>
</html>
