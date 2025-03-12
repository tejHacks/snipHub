<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = false;

// Fetch existing snippet data if snippet_id is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['snippet_id'])) {
    $snippet_id = $_POST['snippet_id'];

    if (isset($_POST['title'], $_POST['language'], $_POST['description'], $_POST['code'], $_POST['visibility'])) {
        $title = trim(htmlspecialchars($_POST['title']));
        $language = trim(htmlspecialchars($_POST['language']));
        $description = trim(htmlspecialchars($_POST['description']));
        $code = trim(htmlspecialchars($_POST['code']));
        $visibility = trim(htmlspecialchars($_POST['visibility']));

        if (!empty($title) && !empty($code) && !empty($language) && !empty($visibility)) {
            $stmt = $pdo->prepare("UPDATE snippets SET title = ?, description = ?, code = ?, language = ?, visibility = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$title, $description, $code, $language, $visibility, $snippet_id, $user_id]);

            if ($stmt->rowCount() > 0) {
                $_SESSION["success"] = "Snippet updated successfully!";
                header("Location: dashboard.php"); // Redirect to dashboard or back to edit
                exit();
            } else {
                $_SESSION["error"] = "No changes were made.";
            }
        } else {
            $_SESSION["error"] = "All fields are required!";
        }
    }

    // Fetch snippet details if editing
    $stmt = $pdo->prepare("SELECT id, title, language, description, code, visibility FROM snippets WHERE id = ? AND user_id = ?");
    $stmt->execute([$snippet_id, $user_id]);
    $snippet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$snippet) {
        die("<h2 style='color: red; text-align: center;'>Snippet not found</h2>");
    }
} else {
    die("<h2 style='color: red; text-align: center;'>Invalid Access</h2>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Snippet</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>
<body class="hacker-theme">

<nav class="navbar navbar-dark bg-black px-3">
    <a class="navbar-brand text-neon" href="dashboard.php">⚡ SnipHub</a>
    <a href="dashboard.php" class="btn btn-outline-neon">Back to Dashboard</a>
</nav>

<div class="container mt-5">
    <h2 class="text-neon">Editing Snippet: <?php echo htmlspecialchars($snippet['title'] ?? ''); ?></h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success terminal-alert">
            ✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            ❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="edit_snippet.php" method="POST">
        <input type="hidden" name="snippet_id" value="<?php echo $snippet_id; ?>">

        <div class="mb-3">
            <label class="form-label text-neon">Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($snippet['title'] ?? ''); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label text-neon">Programming Language</label>
            <select name="language" class="form-control">
                <?php 
                $languages = ["PHP", "Python", "JavaScript", "Java", "C++", "C#", "Ruby", "Swift", "Go", "Rust"];
                foreach ($languages as $lang) {
                    $selected = ($snippet['language'] ?? '') === $lang ? 'selected' : '';
                    echo "<option value='$lang' $selected>$lang</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label text-neon">Description</label>
            <textarea name="description" class="form-control"><?php echo htmlspecialchars($snippet['description'] ?? ''); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label text-neon">Code</label>
            <textarea name="code" class="form-control" rows="6" required><?php echo htmlspecialchars_decode(htmlspecialchars($snippet['code'] ?? '')); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label text-neon">Visibility</label>
            <select name="visibility" class="form-control">
                <option value="public" <?php echo ($snippet['visibility'] ?? '') === "public" ? "selected" : ""; ?>>Public</option>
                <option value="private" <?php echo ($snippet['visibility'] ?? '') === "private" ? "selected" : ""; ?>>Private</option>
            </select>
        </div>

        <button type="submit" name="update_snippet" class="btn btn-outline-neon">Update Snippet</button>
    </form>
</div>

</body>
</html>
