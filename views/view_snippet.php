<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['snippet_id'])) {
    die("<h2 style='color: red; text-align: center;'>Invalid Access</h2>");
}

$snippet_id = $_POST['snippet_id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM snippets WHERE id = ? AND user_id = ?");
$stmt->execute([$snippet_id, $user_id]);
$snippet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$snippet) {
    die("<h2 style='color: red; text-align: center;'>Snippet not found</h2>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Snippet</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../assets/dashboard.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

<!-- and it's easy to individually load additional languages -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/go.min.js"></script>

<script>hljs.highlightAll();</script>
</head>
<body class="hacker-theme">

<nav class="navbar navbar-dark bg-black px-3">
    <a class="navbar-brand text-neon" href="dashboard.php">⚡ SnipHub</a>
    <div class="d-flex flex-wrap gap-2">
                                
    <a href="dashboard.php" class="btn btn-outline-neon">Back to Dashboard</a>
    <!-- <a href="dashboard.php" class="btn btn-outline-neon">Back to Dashboard</a> -->
    <form action="edit_snippet.php" method="POST">
        <input type="hidden" name="snippet_id" value="<?php echo $snippet_id; ?>">
        <button type="submit" class="btn btn-outline-neon">Edit Snippet</button>
    </form>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-neon">Viewing Snippet: <?php echo htmlspecialchars($snippet['title']); ?></h2>
    <p class="snippet-desc">Description <i class="fa fa-sticky-note fa-2x" aria-hidden="true"></i>:📌 <?php echo htmlspecialchars($snippet['description']); ?></p>
    <p class="snippet-lang">Language <i class="fa fa-code fa-2x" aria-hidden="true"></i>: 📌 <?php echo strtoupper(htmlspecialchars($snippet['language'])); ?></p>

    <p class="snippet-visibility">Visibility<i class="fa fa-search fa-2x" aria-hidden="true"></i>: <?php echo strtoupper($snippet['visibility']); ?></p>
    <p class="snippet-data">Created <i class="fa fa-clock-o fa-2x" aria-hidden="true"></i>: <?php echo $snippet['created_at']; ?></p>
    
    <div class="terminal-code">
    <p> Code: <i class="fa fa-code-fork fa-2x"></i></p>
    <pre><code id="snippetCode" class="language-<?php echo strtolower(htmlspecialchars($snippet['language'])); ?>">
        <?php echo htmlspecialchars_decode(htmlspecialchars($snippet['code'])); ?>
    </code></pre>
</div>


    <!-- Copy Code Button -->
    <button class="btn btn-outline-neon mt-3" onclick="copyCode()">Copy Code</button>

    <!-- Bootstrap Styled Terminal Alert -->
    <div id="copyAlert" class="alert alert-success text-center neon-alert mt-3 d-none">
        ✅ Code copied to clipboard!
    </div>
</div>

<script>
    function copyCode() {
        let codeBlock = document.getElementById("snippetCode").innerText;
        navigator.clipboard.writeText(codeBlock).then(() => {
            let alertBox = document.getElementById("copyAlert");
            alertBox.classList.remove("d-none");  // Show the alert
            setTimeout(() => {
                alertBox.classList.add("d-none"); // Hide after 3 seconds
            }, 3000);
        }).catch(err => {
            console.error("Failed to copy: ", err);
        });
    }



    $(document).ready(function() {
    $("#searchInput").on("keyup", function() {
        let searchText = $(this).val().toLowerCase();

        $(".snippet-card").each(function() {
            let title = $(this).find(".snippet-title").text().toLowerCase();
            let description = $(this).find(".snippet-desc").text().toLowerCase();

            if (title.includes(searchText) || description.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});

</script>

<style>
    .neon-alert {
        background-color: #0a0a0a;
        border: 1px solid #00ff00;
        color: #00ff00;
        text-shadow: 0px 0px 8px #00ff00;
    }
</style>

</body>
</html>
