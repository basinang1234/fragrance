<?php foreach ($posts as $post): ?>
    <div class="post-card card mb-4">
        <div class="card-body">
            <!-- User Info -->
            <div class="d-flex align-items-center mb-3">
                <img src="<?= $post['profile_picture'] ?: 'assets/avatar.png' ?>" 
                     alt="Avatar" class="rounded-circle me-3" style="width: 50px; height: 50px;">
                <div>
                    <h5><?= htmlspecialchars($post['username']) ?></h5>
                    <small><?= date('M d, Y', strtotime($post['created_at'])) ?></small>
                </div>
            </div>

            <!-- Post Content -->
            <h3><?= htmlspecialchars($post['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            <?php if ($post['image'] && $post['image'] != 'na'): ?>
                <img src="<?= htmlspecialchars($post['image']) ?>" class="img-fluid rounded mb-3">
            <?php endif; ?>

            <!-- Voting Section -->
            <div class="d-flex gap-3 mb-3">
                <form class="vote-form d-inline" method="POST" action="vote.php">
                    <input type="hidden" name="content_type" value="post">
                    <input type="hidden" name="content_id" value="<?= $post['id'] ?>">
                    <input type="hidden" name="value" value="1">
                    <button type="submit" class="vote-btn btn btn-outline-success 
                            <?= $post['user_vote'] == 1 ? 'upvoted active' : '' ?>">
                        👍 <?= $post['total_likes'] ?>
                    </button>
                </form>
                <form class="vote-form d-inline" method="POST" action="vote.php">
                    <input type="hidden" name="content_type" value="post">
                    <input type="hidden" name="content_id" value="<?= $post['id'] ?>">
                    <input type="hidden" name="value" value="-1">
                    <button type="submit" class="vote-btn btn btn-outline-danger 
                            <?= $post['user_vote'] == -1 ? 'downvoted active' : '' ?>">
                        👎 <?= $post['total_dislikes'] ?>
                    </button>
                </form>
            </div>

            <!-- Comment Preview -->
            <div class="comments-preview">
                <?= getCommentCount($pdo, $post['id']) ?> comments
                <button class="btn btn-sm btn-link" onclick="toggleComments(<?= $post['id'] ?>)">
                    View Comments
                </button>
            </div>
        </div>
    </div>
<?php endforeach; ?>