<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch user snippets from the database
$stmt = $pdo->prepare("SELECT id, title, description, created_at FROM snippets WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$snippets = $stmt->fetchAll(PDO::FETCH_ASSOC);


// allow the user search

// Search Feature
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

if ($search) {
    $stmt = $pdo->prepare("SELECT id, title, description, created_at FROM snippets WHERE user_id = ? AND (title LIKE ? OR description LIKE ?) ORDER BY created_at DESC");
    $stmt->execute([$user_id, "%$search%", "%$search%"]);
} else {
    $stmt = $pdo->prepare("SELECT id, title, description, created_at FROM snippets WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
}

$snippets = $stmt->fetchAll(PDO::FETCH_ASSOC)
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
  </style>
  <style>
    /* Ensure elements don't overflow */
    .container-fluid {
        max-width: 100%;
        overflow-x: hidden;
    }

    .terminal {
        white-space: pre-wrap;
        word-wrap: break-word;
        overflow-x: auto;
    }

    .snippet-card {
        max-width: 100%;
        overflow-wrap: break-word;
        word-wrap: break-word;
        word-break: break-word;
    }

    /* Smooth fade-in for snippets */
    .snippet-card {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

</head>
<body class="hacker-theme">

    <nav class="navbar navbar-dark bg-black px-3">
        <a class="navbar-brand text-neon" href="#">⚡ SnipHub</a>
        <div class="d-flex">
            <a href="new_snippet.php" class="btn btn-outline-neon"><i class="fa fa-plus"></i> New Snippet</a>
            <a href="profile.php" class="btn btn-outline-neon ms-2"><i class="fa fa-user"></i>Profile</a>
            <a href="logout.php" class="btn btn-danger btn-outline-danger text-light ms-2"><i class="fa fa-sign-out"></i> Logout</a>
            <a href="search.php" class="btn btn-outline-neon ms-2"><i class="fa fa-search"></i> Search</a>
        </div>
    </nav>

    <div class="container-fluid mt-5 border border-radius border-2 py-2 border-success">
    <div class="d-flex justify-content-start gap-2 mb-3">
    <div class="circle bg-danger"></div>
    <div class="circle bg-warning"></div>
    <div class="circle bg-success"></div>
</div>

        <h1 class="text-neon">Welcome, <span id="username" class="username"></span>! 👨‍💻</h1>
        <p class="glitch-text">SnipHub - Your Code Vault!</p>

        <div class="terminal">
            <p class="terminal-text">Last login: <?php echo date("Y-m-d H:i:s"); ?> from localhost</p>
            <p class="terminal-text">> Accessing your snippets...</p>
            <p class="terminal-text">> Loading awesome features...</p>
        </div>



        <!-- Snippets List -->
        <div class="row mt-5">
            <div class="col-md-8">
                <h2 class="text-neon">Your Snippets</h2>
                <div class="snippets-list">
                    <?php if (empty($snippets)): ?>
                        <p class="terminal-text">> No snippets found. Create your first snippet!</p>
                    <?php else: ?>
                        <?php foreach ($snippets as $snippet): ?>
                            <div class="snippet-card boder border-2 container-fluid  py-4 mt-3">
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

    <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this snippet? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>


   
    <script>
        let username = "<?php echo $username; ?>";
        let usernameElement = document.getElementById("username");
        function typeEffect(text, element, speed) {
            let i = 0;
            function typing() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(typing, speed);
                }
            }
            typing();
        }
        document.addEventListener("DOMContentLoaded", function() {
            typeEffect(username, usernameElement, 100);
        });
    </script>
    <script src="../assets/jquery.min.js"></script>
    <script src="../assets/bootstrap-5.3.3/dist/js/bootstrap.bundle.js"></script>
    <script src="../assets/bootstrap-5.3.3/dist/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    let snippetIdToDelete = null;

    // When delete button is clicked, open modal
    $(".delete-btn").click(function() {
        snippetIdToDelete = $(this).data("id");
        $("#deleteModal").modal("show");
    });

    // When confirm delete is clicked
    $("#confirmDelete").click(function() {
        if (!snippetIdToDelete) return;

        $.ajax({
            url: "delete_snippet.php",
            type: "POST",
            data: { snippet_id: snippetIdToDelete },
            success: function(response) {
                if (response.success) {
                    $("button[data-id='" + snippetIdToDelete + "']").closest(".snippet-card").fadeOut(300, function() {
                        $(this).remove();
                    });
                    $("#deleteModal").modal("hide");
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("An error occurred. Please try again.");
            }
        });
    });
});
</script>

</body>
</html>
