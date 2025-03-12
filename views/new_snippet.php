<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save_snippet"])) {
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $language = htmlspecialchars(trim($_POST['language']));
    $code = htmlspecialchars(trim($_POST['code']));
    $visibility = htmlspecialchars(trim($_POST['visibility']));
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($language) || empty($code) || empty($visibility)) {
        $_SESSION['error'] = "All fields except description are required!";
        header("Location: new_snippet.php");
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO snippets (user_id, title, description, language, code, visibility, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    if ($stmt->execute([$user_id, $title, $description, $language, $code, $visibility])) {
        $_SESSION["success"] = "Snippet saved successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION["error"] = "Failed to save snippet. Try again!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Snippet | SnipHub</title>
    <!-- <link rel="stylesheet" href="../assets/bootstrap-5.3.3/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="../assets/dashboard.css">  
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <style>
        /* Floating </> Symbols */
        .floating-symbol {
            position: absolute;
            font-size: 2rem;
            color: rgba(0, 255, 0, 0.3);
            animation: float 10s infinite ease-in-out;
        }

        @keyframes float {
            0% { transform: translateY(0px); opacity: 1; }
            50% { transform: translateY(-20px); opacity: 0.7; }
            100% { transform: translateY(0px); opacity: 1; }
        }
    </style>
</head>
<body class="hacker-theme">

    <!-- Floating Symbols -->
    <div id="floating-symbols"></div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark bg-black px-3">
        <a class="navbar-brand text-neon" href="dashboard.php">⚡ SnipHub</a>
        <!-- <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a> -->
        <div class="d-flex">
            <a href="dashboard.php" class="btn btn-outline-neon btn-outline-success ms-2"><i class="fa fa-home"></i> Home</a>
            <a href="logout.php" class="btn btn-danger ms-2"><i class="fa fa-sign-out"></i> Logout</a>
        </div>
    </nav>

    <!-- SNIPPET FORM -->
    <div class="container mt-5">
        <h2 class="text-neon text-center">📝 Add a New Snippet</h2>

        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php } ?>

        <form method="POST" action="new_snippet.php">
            <div class="mb-3">
                <label class="text-neon">Snippet Title</label>
                <input type="text" class="form-control bg-dark text-light" name="title" required>
            </div>

            <div class="mb-3">
                <label class="text-neon">Description (Optional)</label>
                <textarea class="form-control bg-dark text-light" name="description" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label class="text-neon">Language</label>
                <select class="form-control bg-dark text-light" name="language" required>
                    <option value="php">PHP</option>
                    <option value="javascript">JavaScript</option>
                    <option value="python">Python</option>
                    <option value="java">Java</option>
                    <option value="cpp">C++</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="text-neon">Your Code</label>
                <textarea id="codeArea" class="form-control bg-dark text-light" name="code" rows="10" required></textarea>
            </div>

            <div class="mb-3">
                <label class="text-neon">Visibility</label>
                <select class="form-control bg-dark text-light" name="visibility" required>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>

            <button type="submit" name="save_snippet" class="btn btn-outline-neon w-100">💾 Save Snippet</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Prism.highlightAll();

            // Generate Floating Symbols
            const symbolsContainer = document.getElementById("floating-symbols");
            for (let i = 0; i < 30; i++) {
                let symbol = document.createElement("div");
                symbol.className = "floating-symbol";
                symbol.innerHTML = "&lt;/&gt;";
                symbol.style.left = Math.random() * 100 + "vw";
                symbol.style.top = Math.random() * 100 + "vh";
                symbol.style.animationDuration = Math.random() * 5 + 5 + "s";
                symbolsContainer.appendChild(symbol);
            }
        });
    </script>
</body>
</html>
