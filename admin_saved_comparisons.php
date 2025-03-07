<?php
require 'config.php';

// Fetch all perfumes for dropdown
$perfumesStmt = $pdo->query("SELECT id, perfume_name FROM perfumes");
$perfumes = $perfumesStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle comparison submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_comparison'])) {
    $perfume1 = (int)$_POST['perfume1'];
    $perfume2 = (int)$_POST['perfume2'];
    $perfume3 = (int)$_POST['perfume3'];

    $stmt = $pdo->prepare("
        INSERT INTO perfume_comparisons (user_id, perfume1_id, perfume2_id, perfume3_id)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$_SESSION['user_id'], $perfume1, $perfume2, $perfume3]);

    header("Location: admin_saved_comparisons.php");
    exit;
}

// Fetch saved comparisons
$comparisonsStmt = $pdo->prepare("
    SELECT pc.*, p1.perfume_name AS p1_name, p2.perfume_name AS p2_name, p3.perfume_name AS p3_name
    FROM perfume_comparisons pc
    JOIN perfumes p1 ON pc.perfume1_id = p1.id
    JOIN perfumes p2 ON pc.perfume2_id = p2.id
    JOIN perfumes p3 ON pc.perfume3_id = p3.id
    WHERE pc.user_id = ?
");
$comparisonsStmt->execute([$_SESSION['user_id']]);
$comparisons = $comparisonsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Comparisons - Fragrance Haven</title>
    <style>
        .comparison-container {
            max-width: 800px;
            margin: 20px auto;
        }
        .comparison-form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .comparison-list {
            margin-top: 20px;
        }
        .comparison-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="comparison-container">
        <h2>Save Comparison</h2>
        <form class="comparison-form" method="POST">
            <select name="perfume1" required>
                <?php foreach ($perfumes as $perfume): ?>
                    <option value="<?= $perfume['id'] ?>"><?= $perfume['perfume_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <select name="perfume2" required>
                <?php foreach ($perfumes as $perfume): ?>
                    <option value="<?= $perfume['id'] ?>"><?= $perfume['perfume_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <select name="perfume3" required>
                <?php foreach ($perfumes as $perfume): ?>
                    <option value="<?= $perfume['id'] ?>"><?= $perfume['perfume_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="save_comparison">Save Comparison</button>
        </form>

        <h2>Saved Comparisons</h2>
        <div class="comparison-list">
            <?php foreach ($comparisons as $comparison): ?>
                <div class="comparison-item">
                    <p><strong>Comparison ID:</strong> <?= $comparison['id'] ?></p>
                    <p><?= $comparison['p1_name'] ?> vs <?= $comparison['p2_name'] ?> vs <?= $comparison['p3_name'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>