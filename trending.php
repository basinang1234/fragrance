<?php
require 'config.php'; // Include centralized configuration

// Fetch top 10 trending perfumes with perfumer details
$stmt = $pdo->query("
    SELECT 
        p.id AS perfume_id,
        p.brand_name,
        p.perfume_name,
        p.description,
        p.accords,
        p.notes,
        p.image AS perfume_image,
        pf.name AS perfumer_name,
        pf.expertise AS perfumer_expertise,
        pf.image AS perfumer_image,
        tp.score
    FROM perfumes p
    INNER JOIN trending_perfumes tp ON p.id = tp.perfume_id
    LEFT JOIN perfumers pf ON p.perfumer_id = pf.id
    ORDER BY tp.score DESC
    LIMIT 10;
");

$trendingPerfumes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trending Perfumes - Fragrance Haven</title>
    <style>
        :root {
            --primary-color: #2A2A2A;
            --accent-color: #E63946;
            --text-light: #F8F9FA;
            --background-light: #FFFFFF;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

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

        main {
            padding: 2rem;
        }

        .trending-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .trending-card {
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .trending-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .trending-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .trending-card .content {
            padding: 1rem;
        }

        .trending-card h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--accent-color);
        }

        .trending-card p {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            color: #555;
        }

        .trending-card .perfumer {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .trending-card .perfumer img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }

        .trending-card .stats {
            display: flex;
            gap: 1rem;
            font-size: 0.8rem;
            color: #777;
        }

        .trending-card .stats span {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        @media (max-width: 768px) {
            .trending-card img {
                height: auto;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="trending-container">
            <?php if (!empty($trendingPerfumes)): ?>
                <?php foreach ($trendingPerfumes as $perfume): ?>
                    <div class="trending-card">
                        <!-- Handle missing or invalid images -->
                        <?php
                        $perfumeImagePath = !empty($perfume['perfume_image']) && $perfume['perfume_image'] !== 'na'
                            ? htmlspecialchars($perfume['perfume_image'])
                            : 'assets/perfume-placeholder.png';
                        ?>
                        <img src="<?= $perfumeImagePath ?>" alt="<?= htmlspecialchars($perfume['perfume_name']) ?>">
                        <div class="content">
                            <h3><?= htmlspecialchars($perfume['perfume_name']) ?></h3>
                            <p><strong>Brand:</strong> <?= htmlspecialchars($perfume['brand_name']) ?></p>
                            <div class="perfumer">
                                <?php if (!empty($perfume['perfumer_image']) && $perfume['perfumer_image'] !== 'na'): ?>
                                    <img src="<?= htmlspecialchars($perfume['perfumer_image']) ?>" alt="<?= htmlspecialchars($perfume['perfumer_name']) ?>">
                                <?php endif; ?>
                                <p><strong>Perfumer:</strong> <?= htmlspecialchars($perfume['perfumer_name'] ?? 'Unknown') ?></p>
                            </div>
                            <p><strong>Expertise:</strong> <?= htmlspecialchars($perfume['perfumer_expertise'] ?? 'N/A') ?></p>
                            <p><strong>Notes:</strong> <?= htmlspecialchars($perfume['notes'] ?? 'N/A') ?></p>
                            <div class="stats">
                                <span><i>⭐</i> Score: <?= htmlspecialchars($perfume['score']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center;">No trending perfumes found.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>