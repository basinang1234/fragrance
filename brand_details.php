<?php
require 'config.php'; // Include centralized configuration

// Get the selected brand from the query string
if (!isset($_GET['brand'])) {
    header("Location: brands.php");
    exit;
}

$brandName = urldecode($_GET['brand']);

// Fetch all perfumes for the selected brand
$stmt = $pdo->prepare("
    SELECT p.id, p.brand_name, p.perfume_name, p.description, p.image, pf.name AS perfumer_name, pf.id AS perfumer_id
    FROM perfumes p
    LEFT JOIN perfumers pf ON p.perfumer_id = pf.id
    WHERE p.brand_name = ? 
    ORDER BY p.perfume_name ASC
");
$stmt->execute([$brandName]);
$perfumes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($brandName) ?> - Fragrance Haven</title>
    <style>
        /* Add styles here */
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="brand-header">
        <h1><?= htmlspecialchars($brandName) ?></h1>
        <p>Explore all fragrances by <?= htmlspecialchars($brandName) ?></p>
    </div>

    <div class="perfumes-container">
        <?php if (!empty($perfumes)): ?>
            <?php foreach ($perfumes as $perfume): ?>
                <div class="perfume-card">
                    <img src="<?= htmlspecialchars($perfume['image'] ?? 'assets/perfume-placeholder.png') ?>" alt="<?= htmlspecialchars($perfume['perfume_name']) ?>">
                    <h3><?= htmlspecialchars($perfume['perfume_name']) ?></h3>
                    <p><?= htmlspecialchars($perfume['description']) ?></p>
                    <?php if ($perfume['perfumer_id']): ?>
                        <a href="perfumer_details.php?perfumer_id=<?= $perfume['perfumer_id'] ?>">View Perfumer: <?= htmlspecialchars($perfume['perfumer_name']) ?></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No perfumes found for this brand.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>