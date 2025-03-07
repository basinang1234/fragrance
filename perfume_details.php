<?php
require 'config.php'; // Include centralized configuration

$perfumeId = (int)$_GET['perfume_id'];

// Fetch perfume details
$stmt = $pdo->prepare("
    SELECT id, brand_name, perfume_name, description, accords, notes, perfumer, fashion_styles, image 
    FROM perfumes 
    WHERE id = ?
");
$stmt->execute([$perfumeId]);
$perfume = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$perfume) {
    header("Location: brands.php");
    exit;
}

// Fetch reviews for the perfume
$stmt = $pdo->prepare("
    SELECT r.id, r.rating, r.review_text, r.scent_impression, r.created_at, u.username 
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.perfume_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$perfumeId]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($perfume['perfume_name']) ?> - Fragrance Haven</title>
    <style>
        :root {
            --primary-color: #2A2A2A;
            --accent-color: #E63946;
            --text-light: #F8F9FA;
            --background-light: #FFFFFF;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        /* Base Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: var(--background-light);
            color: var(--primary-color);
            line-height: 1.6;
        }

        /* Header */
        .perfume-header {
            text-align: center;
            margin: 2rem 0;
        }

        .perfume-header h1 {
            font-size: 2.5rem;
            color: var(--accent-color);
        }

        .perfume-header p {
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        /* Perfume Details */
        .perfume-details {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            display: flex;
            gap: 2rem;
        }

        .perfume-image img {
            width: 300px;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }

        .perfume-info {
            flex-grow: 1;
        }

        .perfume-info h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--accent-color);
        }

        .perfume-info p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        /* Reviews Section */
        .reviews-section {
            margin-top: 2rem;
        }

        .review-card {
            background: white;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 1rem;
        }

        .review-card h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--accent-color);
        }

        .review-card p {
            font-size: 1rem;
            color: var(--primary-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .perfume-details {
                flex-direction: column;
            }

            .perfume-image img {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="perfume-header">
        <h1><?= htmlspecialchars($perfume['perfume_name']) ?></h1>
        <p>Details and reviews for <?= htmlspecialchars($perfume['perfume_name']) ?></p>
    </div>

    <div class="perfume-details">
        <!-- Perfume Image -->
        <div class="perfume-image">
            <img src="<?= htmlspecialchars($perfume['image'] ?? 'assets/perfume-placeholder.png') ?>" alt="<?= htmlspecialchars($perfume['perfume_name']) ?>">
        </div>

        <!-- Perfume Info -->
        <div class="perfume-info">
            <h2><?= htmlspecialchars($perfume['perfume_name']) ?></h2>
            <p><strong>Brand:</strong> <?= htmlspecialchars($perfume['brand_name']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($perfume['description']) ?></p>
            <p><strong>Accords:</strong> <?= htmlspecialchars($perfume['accords']) ?></p>
            <p><strong>Notes:</strong> <?= htmlspecialchars($perfume['notes']) ?></p>
            <p><strong>Perfumer:</strong> <?= htmlspecialchars($perfume['perfumer']) ?></p>
            <p><strong>Fashion Styles:</strong> <?= htmlspecialchars($perfume['fashion_styles']) ?></p>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
        <h2>Reviews</h2>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <h3><?= htmlspecialchars($review['username']) ?></h3>
                    <p><strong>Rating:</strong> <?= htmlspecialchars($review['rating']) ?>/5</p>
                    <p><strong>Review:</strong> <?= htmlspecialchars($review['review_text']) ?></p>
                    <p><strong>Scent Impression:</strong> <?= htmlspecialchars($review['scent_impression']) ?></p>
                    <p><em>Posted on: <?= htmlspecialchars($review['created_at']) ?></em></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No reviews available for this perfume.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>