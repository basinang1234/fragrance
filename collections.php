<?php
require 'config.php';

// Fetch all perfumes with perfumer and trending details
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
        pf.expertise,
        tp.score AS trending_score
    FROM perfumes p
    LEFT JOIN perfumers pf ON p.perfumer_id = pf.id
    LEFT JOIN trending_perfumes tp ON p.id = tp.perfume_id
    ORDER BY tp.score DESC, p.brand_name ASC
");

$perfumes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfume Collections - Fragrance Haven</title>
    <style>
        :root {
            --primary: #2c3e50;
            --accent: #e74c3c;
            --light: #f8f9fa;
            --shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light);
            margin: 0;
            padding: 2rem;
        }

        .collection-header {
            background: var(--primary);
            color: white;
            padding: 2rem;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .perfume-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .perfume-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .perfume-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .perfume-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .perfume-name {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .brand-name {
            color: #7f8c8d;
            margin-bottom: 1rem;
        }

        .perfume-details {
            margin-bottom: 1rem;
        }

        .perfume-details span {
            background: #f1f1f1;
            padding: 0.3rem 0.6rem;
            border-radius: 15px;
            margin: 0.2rem;
            display: inline-block;
        }

        .trending-score {
            font-weight: bold;
            color: var(--accent);
        }

        @media (max-width: 768px) {
            .perfume-grid {
                grid-template-columns: 1fr;
            }
            
            .perfume-image {
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="collection-header">
        <h1>Perfume Collections</h1>
        <p>Explore our curated selection of fragrances</p>
    </div>

    <div class="perfume-grid">
        <?php foreach ($perfumes as $perfume): ?>
            <div class="perfume-card">
                <img src="<?= $perfume['perfume_image'] !== 'na' ? 
                    htmlspecialchars($perfume['perfume_image']) : 
                    'assets/perfume-placeholder.png' ?>" 
                    alt="<?= htmlspecialchars($perfume['perfume_name']) ?>" 
                    class="perfume-image">
                
                <div class="perfume-name"><?= htmlspecialchars($perfume['perfume_name']) ?></div>
                <div class="brand-name"><?= htmlspecialchars($perfume['brand_name']) ?></div>
                
                <div class="perfume-details">
                    <h3>Details</h3>
                    <p><?= htmlspecialchars($perfume['description']) ?></p>
                </div>
                
                <div class="perfume-details">
                    <h3>Accords</h3>
                    <?= !empty($perfume['accords']) ? 
                        '<span>' . implode('</span><span>', array_map('htmlspecialchars', explode(',', $perfume['accords']))) . '</span>' : 
                        '<span>N/A</span>' ?>
                </div>
                
                <div class="perfume-details">
                    <h3>Notes</h3>
                    <?= !empty($perfume['notes']) ? 
                        '<span>' . implode('</span><span>', array_map('htmlspecialchars', explode(',', $perfume['notes']))) . '</span>' : 
                        '<span>N/A</span>' ?>
                </div>
                
                <div class="perfume-details">
                    <h3>Perfumer</h3>
                    <span><?= htmlspecialchars($perfume['perfumer_name'] ?? 'Unknown') ?></span>
                </div>
                
                <?php if (!empty($perfume['trending_score'])): ?>
                    <div class="trending-score">
                        Trending Score: <?= htmlspecialchars($perfume['trending_score']) ?>/10
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>