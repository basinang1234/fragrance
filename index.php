<?php
require 'config.php'; // Include centralized configuration
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fragrance Haven</title>
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

        /* Header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
            text-decoration: none;
        }

        .logo img {
            height: 40px;
            vertical-align: middle;
            margin-right: 10px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #007bff;
        }

        .search-bar {
            position: relative;
            width: 250px;
        }

        .search-bar input {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
        }

        .search-bar button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #007bff;
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .auth-buttons a {
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .auth-buttons a:hover {
            background-color: #0056b3;
        }

        /* Trending Section */
        .trending-section {
            padding: 40px 20px;
            text-align: center;
        }

        .trending-section h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #007bff;
        }

        .trending-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .trending-item {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 200px;
            text-align: center;
        }

        .trending-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .trending-item h3 {
            font-size: 1.2rem;
            margin: 10px 0;
        }

        /* Footer */
        footer {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        footer a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 10px;
            }

            .nav-links {
                flex-direction: column;
                gap: 10px;
            }

            .search-bar {
                width: 100%;
            }

            .auth-buttons {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Trending Section -->
    <section class="trending-section">
        <h2>Trending Perfumes</h2>
        <div class="trending-list">
            <?php
            // Fetch trending perfumes from the database
            $stmt = $pdo->prepare("SELECT perfume_name, image FROM perfumes ORDER BY created_at DESC LIMIT 6");
            $stmt->execute();
            $perfumes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($perfumes as $perfume) {
                echo '<div class="trending-item">';
                echo '<img src="' . htmlspecialchars($perfume['image']) . '" alt="' . htmlspecialchars($perfume['perfume_name']) . '">';
                echo '<h3>' . htmlspecialchars($perfume['perfume_name']) . '</h3>';
                echo '</div>';
            }
            ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>