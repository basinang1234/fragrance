<?php
require 'config.php';
session_start();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$perPage = 6;
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("
    SELECT fp.*, u.username, u.profile_picture,
           SUM(CASE WHEN v.value = 1 THEN 1 ELSE 0 END) AS total_likes,
           SUM(CASE WHEN v.value = -1 THEN 1 ELSE 0 END) AS total_dislikes,
           (SELECT value FROM votes 
            WHERE user_id = :user_id 
            AND content_id = fp.id 
            AND content_type = 'post') AS user_vote
    FROM forum_posts fp
    JOIN users u ON fp.user_id = u.id
    LEFT JOIN votes v ON fp.id = v.content_id AND v.content_type = 'post'
    WHERE fp.title LIKE :search OR fp.content LIKE :search
    GROUP BY fp.id
    ORDER BY fp.created_at DESC
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post): ?>
    <div class="post-card card mb-4">
        <!-- Same post structure as in forums.php -->
    </div>
<?php endforeach; ?>