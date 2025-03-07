<?php
require 'config.php';
?>

<?php
// Fetch all perfumes for dropdown
$perfumesStmt = $pdo->query("SELECT id, perfume_name FROM perfumes");
$perfumes = $perfumesStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_review'])) {
    $perfumeId = (int)$_POST['perfume_id'];
    $rating = (int)$_POST['rating'];
    $reviewText = trim($_POST['review_text']);
    $scentImpression = trim($_POST['scent_impression']);

    $stmt = $pdo->prepare("
        INSERT INTO reviews (user_id, perfume_id, rating, review_text, scent_impression)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$_SESSION['user_id'], $perfumeId, $rating, $reviewText, $scentImpression]);

    header("Location: admin_my_reviews.php");
    exit;
}

// Fetch admin's reviews
$reviewsStmt = $pdo->prepare("
    SELECT r.*, p.perfume_name 
    FROM reviews r
    JOIN perfumes p ON r.perfume_id = p.id
    WHERE r.user_id = ?
");
$reviewsStmt->execute([$_SESSION['user_id']]);
$reviews = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews - Fragrance Haven</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        /* Form Styling */
        .review-form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .review-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .review-form input,
        .review-form select,
        .review-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .review-form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .review-form button:hover {
            background-color: #0056b3;
        }

        /* Review List Styling */
        .review-list {
            margin-top: 30px;
        }

        .review-item {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .review-item:hover {
            transform: translateY(-5px);
        }

        .review-item h3 {
            margin-bottom: 10px;
            color: #007bff;
        }

        .review-item p {
            margin-bottom: 5px;
        }

        .review-item strong {
            color: #555;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .review-form input,
            .review-form select,
            .review-form textarea {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h2>Add Review</h2>
        <form class="review-form" method="POST">
            <label for="perfume_id">Select Perfume:</label>
            <select name="perfume_id" id="perfume_id" required>
                <?php foreach ($perfumes as $perfume): ?>
                    <option value="<?= $perfume['id'] ?>"><?= htmlspecialchars($perfume['perfume_name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="rating">Rating (1-5):</label>
            <input type="number" name="rating" id="rating" min="1" max="5" placeholder="Enter rating (1-5)" required>

            <label for="review_text">Review Text:</label>
            <textarea name="review_text" id="review_text" rows="4" placeholder="Write your review here..."></textarea>

            <label for="scent_impression">Scent Impression:</label>
            <textarea name="scent_impression" id="scent_impression" rows="4" placeholder="Describe the scent impression..."></textarea>

            <button type="submit" name="add_review">Submit Review</button>
        </form>

        <h2>Your Reviews</h2>
        <div class="review-list">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <h3><?= htmlspecialchars($review['perfume_name']) ?></h3>
                        <p><strong>Rating:</strong> <?= $review['rating'] ?>/5</p>
                        <p><strong>Review:</strong> <?= htmlspecialchars($review['review_text']) ?></p>
                        <p><strong>Scent Impression:</strong> <?= htmlspecialchars($review['scent_impression']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You have not added any reviews yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>