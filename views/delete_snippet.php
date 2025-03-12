<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $snippet_id = $_POST['snippet_id'];

    // Verify the snippet belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM snippets WHERE id = ? AND user_id = ?");
    $stmt->execute([$snippet_id, $user_id]);
    $snippet = $stmt->fetch();

    if (!$snippet) {
        echo json_encode(["success" => false, "message" => "Snippet not found or unauthorized."]);
        exit();
    }

    // Delete the snippet
    $stmt = $pdo->prepare("DELETE FROM snippets WHERE id = ?");
    if ($stmt->execute([$snippet_id])) {
        echo json_encode(["success" => true, "message" => "Snippet deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete snippet."]);
    }
}
