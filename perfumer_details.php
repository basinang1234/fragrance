<?php
require 'config.php'; // Include centralized configuration

// Get the selected perfumer ID from the query string
if (!isset($_GET['perfumer_id'])) {
    header("Location: brands.php");
    exit;
}

$perfumerId = (int)$_GET['perfumer_id'];

// Fetch perfumer details
$stmt = $pdo->prepare("
    SELECT id, name, tagline, expertise, most_loved_perfume_id, image 
    FROM perfumers 
    WHERE id = ?
");
$stmt->execute([$perfumerId]);
$perfumer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$perfumer) {
    header("Location: brands.php");
    exit;
}

// Fetch perfumes created by the perfumer
$stmt = $pdo->prepare("
    SELECT id, perfume_name, description, image 
    FROM perfumes 
    WHERE perfumer_id = ? 
    ORDER BY perfume_name ASC
");
$stmt->execute([$perfumerId]);
$perfumes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch most loved perfume details
$mostLovedPerfume = null;
if ($perfumer['most_loved_perfume_id']) {
    $stmt = $pdo->prepare("
        SELECT id, perfume_name, description, image 
        FROM perfumes 
        WHERE id = ?
    ");
    $stmt->execute([$perfumer['most_loved_perfume_id']]);
    $mostLovedPerfume = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($perfumer['name']) ?> - Fragrance Haven</title>
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

        .perfumer-header {
            text-align: center;
            margin: 2rem 0;
        }

        .perfumer-header img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 1rem;
        }

        .perfumer-info {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .perfumer-info h1 {
            font-size: 2.5rem;
            color: var(--accent-color);
        }

        .perfumer-info p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .perfumes-section {
            margin-top: 2rem;
        }

        .perfume-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: white;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 1rem;
        }

        .perfume-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .perfumer-header img {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="perfumer-header">
        <img src="<?= htmlspecialchars($perfumer['image'] ?? 'assets/perfumer-placeholder.png') ?>" alt="<?= htmlspecialchars($perfumer['name']) ?>">
        <h1><?= htmlspecialchars($perfumer['name']) ?></h1>
        <p><em><?= htmlspecialchars($perfumer['tagline']) ?></em></p>
    </div>

    <div class="perfumer-info">
        <h2>About</h2>
        <p><strong>Expertise:</strong> <?= htmlspecialchars($perfumer['expertise']) ?></p>
        <?php if ($mostLovedPerfume): ?>
            <p><strong>Most Loved Perfume:</strong> <?= htmlspecialchars($mostLovedPerfume['perfume_name']) ?></p>
        <?php endif; ?>
    </div>

    <div class="perfumes-section">
        <h2>Perfumes Created</h2>
        <?php if (!empty($perfumes)): ?>
            <?php foreach ($perfumes as $perfume): ?>
                <div class="perfume-card">
                    <img src="<?= htmlspecialchars($perfume['image'] ?? 'assets/perfume-placeholder.png') ?>" alt="<?= htmlspecialchars($perfume['perfume_name']) ?>">
                    <div>
                        <h3><?= htmlspecialchars($perfume['perfume_name']) ?></h3>
                        <p><?= htmlspecialchars($perfume['description']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No perfumes created by this perfumer.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>