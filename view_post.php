<?php
require 'config.php';
session_start();

$post_id = (int)$_GET['id'];

// Fetch post details
$stmt = $pdo->prepare("
    SELECT fp.*, u.username, u.profile_picture,
           (SELECT COUNT(*) FROM votes 
            WHERE content_type = 'post' AND content_id = fp.id AND value = 1) AS likes,
           (SELECT COUNT(*) FROM votes 
            WHERE content_type = 'post' AND content_id = fp.id AND value = -1) AS dislikes,
           (SELECT value FROM votes 
            WHERE user_id = ? AND content_type = 'post' AND content_id = fp.id) AS user_vote
    FROM forum_posts fp
    JOIN users u ON fp.user_id = u.id
    WHERE fp.id = ?
");
$stmt->execute([$_SESSION['user_id'], $post_id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found");
}

// Fetch comments
$stmt = $pdo->prepare("
    SELECT fc.*, u.username, u.profile_picture,
           (SELECT COUNT(*) FROM votes 
            WHERE content_type = 'comment' AND content_id = fc.id AND value = 1) AS likes,
           (SELECT COUNT(*) FROM votes 
            WHERE content_type = 'comment' AND content_id = fc.id AND value = -1) AS dislikes,
           (SELECT value FROM votes 
            WHERE user_id = ? AND content_type = 'comment' AND content_id = fc.id) AS user_vote
    FROM forum_comments fc
    JOIN users u ON fc.user_id = u.id
    WHERE post_id = ?
    ORDER BY fc.created_at ASC
");
$stmt->execute([$_SESSION['user_id'], $post_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <!-- Post Display -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="<?= $post['profile_picture'] ?: 'assets/avatar.png' ?>" 
                         alt="Avatar" class="rounded-circle me-3" style="width: 60px; height: 60px;">
                    <div>
                        <h4><?= htmlspecialchars($post['username']) ?></h4>
                        <small><?= date('M d, Y', strtotime($post['created_at'])) ?></small>
                    </div>
                </div>
                <h2><?= htmlspecialchars($post['title']) ?></h2>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <?php if ($post['image'] && $post['image'] != 'na'): ?>
                    <img src="<?= htmlspecialchars($post['image']) ?>" class="img-fluid rounded mb-3">
                <?php endif; ?>
                <div class="d-flex gap-3 mb-3">
                    <button class="vote-btn btn btn-outline-success 
                            <?= $post['user_vote'] == 1 ? 'active' : '' ?>"
                            onclick="handleVote('post', <?= $post['id'] ?>, 1)">
                        👍 <span id="post-likes"><?= $post['likes'] ?></span>
                    </button>
                    <button class="vote-btn btn btn-outline-danger 
                            <?= $post['user_vote'] == -1 ? 'active' : '' ?>"
                            onclick="handleVote('post', <?= $post['id'] ?>, -1)">
                        👎 <span id="post-dislikes"><?= $post['dislikes'] ?></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Comment Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h4>Comments</h4>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-card mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <img src="<?= $comment['profile_picture'] ?: 'assets/avatar.png' ?>" 
                                 alt="Avatar" class="rounded-circle me-3" style="width: 40px; height: 40px;">
                            <div>
                                <h6><?= htmlspecialchars($comment['username']) ?></h6>
                                <small><?= date('M d, Y', strtotime($comment['created_at'])) ?></small>
                            </div>
                        </div>
                        <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                        <?php if ($comment['image'] && $comment['image'] != 'na'): ?>
                            <img src="<?= htmlspecialchars($comment['image']) ?>" class="img-fluid rounded mb-2">
                        <?php endif; ?>
                        <div class="d-flex gap-3">
                            <button class="vote-btn btn btn-outline-success btn-sm 
                                    <?= $comment['user_vote'] == 1 ? 'active' : '' ?>"
                                    onclick="handleVote('comment', <?= $comment['id'] ?>, 1)">
                                👍 <span><?= $comment['likes'] ?></span>
                            </button>
                            <button class="vote-btn btn btn-outline-danger btn-sm 
                                    <?= $comment['user_vote'] == -1 ? 'active' : '' ?>"
                                    onclick="handleVote('comment', <?= $comment['id'] ?>, -1)">
                                👎 <span><?= $comment['dislikes'] ?></span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Comment Form -->
                <form id="commentForm" class="mt-4" enctype="multipart/form-data">
                    <input type="hidden" name="post_id" value="<?= $post_id ?>">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Comment</button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Handle comment submission
        $('#commentForm').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: 'comment.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                }
            });
        });

        // Handle voting (same as forums.php)
        function handleVote(contentType, contentId, value) {
            // ... (same implementation as forums.php)
        }
    </script>
</body>
</html>