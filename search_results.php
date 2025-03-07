<?php
require 'config.php'; // Include centralized configuration

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$resultsPerPage = 6; // Number of results per page
$offset = ($page - 1) * $resultsPerPage;

if (!empty($query)) {
    // Fetch paginated results
    $stmt = $pdo->prepare("
        SELECT * FROM perfumes 
        WHERE LOWER(perfume_name) LIKE :query 
           OR LOWER(notes) LIKE :query 
           OR LOWER(perfumer) LIKE :query
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':query', "%" . strtolower($query) . "%", PDO::PARAM_STR);
    $stmt->bindValue(':limit', $resultsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch total results for pagination
    $totalStmt = $pdo->prepare("
        SELECT COUNT(*) AS total FROM perfumes 
        WHERE LOWER(perfume_name) LIKE :query 
           OR LOWER(notes) LIKE :query 
           OR LOWER(perfumer) LIKE :query
    ");
    $totalStmt->execute(['query' => "%" . strtolower($query) . "%"]);
    $totalResults = $totalStmt->fetchColumn();
    $totalPages = ceil($totalResults / $resultsPerPage);
} else {
    $results = [];
    $totalPages = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Fragrance Haven</title>
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

        /* Header Styling */
        header {
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Main Content */
        main {
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: #007bff;
        }

        /* Search Results */
        .results-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .result-card {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .result-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .result-card h3 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .result-card p {
            font-size: 0.9rem;
            color: #555;
        }

        .result-card strong {
            color: #007bff;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pagination a {
            text-decoration: none;
            color: #007bff;
            padding: 8px 12px;
            border: 1px solid #007bff;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: #fff;
        }

        .pagination a.active {
            background-color: #007bff;
            color: #fff;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h2 {
                font-size: 1.5rem;
            }

            .pagination {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="search-results">
        <h2>Search Results</h2>
        <?php if (!empty($query)): ?>
            <?php if (!empty($results)): ?>
                <div class="results-container">
                    <?php foreach ($results as $perfume): ?>
                        <div class="result-card">
                            <img src="<?php echo htmlspecialchars($perfume['image']); ?>" alt="<?php echo htmlspecialchars($perfume['perfume_name']); ?>">
                            <h3><?php echo highlight(htmlspecialchars($perfume['perfume_name']), $query); ?></h3>
                            <p><strong>Perfumer:</strong> <?php echo highlight(htmlspecialchars($perfume['perfumer']), $query); ?></p>
                            <p><strong>Notes:</strong> <?php echo highlight(htmlspecialchars($perfume['notes']), $query); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?query=<?php echo urlencode($query); ?>&page=<?php echo $i; ?>" 
                               class="<?php echo $i === $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <p>No results found for "<?php echo htmlspecialchars($query); ?>".</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Please enter a search term.</p>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>

<?php
function highlight($text, $query) {
    return preg_replace("/(" . preg_quote($query, '/') . ")/i", "<strong>$1</strong>", $text);
}
?>