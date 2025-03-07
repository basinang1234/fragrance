<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = (int)$_POST['post_id'];
    $content = trim($_POST['content']);
    $image = null;

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        $image = $target_file;
    }

    $sql = "INSERT INTO forum_comments (post_id, user_id, content, image, created_at) VALUES (:post_id, :user_id, :content, :image, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':post_id', $post_id);
    $stmt->bindValue(':user_id', $_SESSION['user_id']);
    $stmt->bindValue(':content', $content);
    $stmt->bindValue(':image', $image);
    $stmt->execute();

    header("Location: view_post.php?id=$post_id");
    exit;
}
?>