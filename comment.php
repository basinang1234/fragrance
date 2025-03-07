<?php
require 'config.php';
session_start();

// Return JSON headers
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit;
}

$post_id = (int)$_POST['post_id'];
$content = trim($_POST['content']);
$image = null;

// Validate inputs
if (empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Comment cannot be empty']);
    exit;
}

// Handle image upload
if (!empty($_FILES['image']['name'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $image = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image);
}

try {
    // Insert comment
    $stmt = $pdo->prepare("
        INSERT INTO forum_comments (post_id, user_id, content, image, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$post_id, $_SESSION['user_id'], $content, $image]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>