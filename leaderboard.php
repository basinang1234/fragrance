<?php
require 'config.php'; // Include centralized configuration

// Fetch top-rated perfumes with 4-5 star ratings
$stmt = $pdo->prepare("
    SELECT p.id, p.perfume_name, p.brand_name, p.image, AVG(r.rating) AS avg_rating, COUNT(r.id) AS review_count
    FROM perfumes p
    LEFT JOIN reviews r ON p.id = r.perfume_id
    WHERE r.rating >= 4
    GROUP BY p.id
    ORDER BY avg_rating DESC, review_count DESC
    LIMIT 10
");
$stmt->execute();
$topPerfumes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Fragrance Haven</title>
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
        .leaderboard-header {
            text-align: center;
            margin: 2rem 0;
        }

        .leaderboard-header h1 {
            font-size: 2.5rem;
            color: var(--accent-color);
        }

        .leaderboard-header p {
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        /* Leaderboard Container */
        .leaderboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Perfume Card */
        .perfume-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: white;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 1rem;
            transition: var(--transition);
        }

        .perfume-card:hover {
            transform: translateY(-5px);
        }

        .perfume-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }

        .perfume-details {
            flex-grow: 1;
        }

        .perfume-details h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--accent-color);
        }

        .perfume-details p {
            font-size: 1rem;
            color: var(--primary-color);
        }

        .perfume-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .perfume-rating span {
            color: #FFD700; /* Gold for stars */
            font-size: 1.2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .perfume-card {
                flex-direction: column;
                text-align: center;
            }

            .perfume-card img {
                width: 150px;
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="leaderboard-header">
        <h1>Leaderboard</h1>
        <p>Top-rated fragrances loved by our community</p>
    </div>

    <div class="leaderboard-container">
        <?php if (!empty($topPerfumes)): ?>
            <?php foreach ($topPerfumes as $index => $perfume): ?>
                <div class="perfume-card">
                    <img src="<?= htmlspecialchars($perfume['image']) ?>" alt="<?= htmlspecialchars($perfume['perfume_name']) ?>">
                    <div class="perfume-details">
                        <h3><?= htmlspecialchars($perfume['perfume_name']) ?></h3>
                        <p><strong>Brand:</strong> <?= htmlspecialchars($perfume['brand_name']) ?></p>
                        <div class="perfume-rating">
                            <span>★</span>
                            <span><?= number_format($perfume['avg_rating'], 1) ?></span>
                            <span>(<?= $perfume['review_count'] ?> reviews)</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No perfumes found in the leaderboard.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>