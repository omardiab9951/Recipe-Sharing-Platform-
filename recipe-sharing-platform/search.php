<?php
// File: search.php - Handles search and filtering results

require_once 'controllers/SearchController.php';

// --- Input Collection ---
// Retrieve keyword 'q' and category 'category' from GET request
$keyword     = isset($_GET['q']) ? trim($_GET['q']) : ''; 
$categoryId  = isset($_GET['category']) ? (int)$_GET['category'] : null; 
$searchResults = [];

// --- Search Execution ---
if (!empty($keyword) || !empty($categoryId)) {
    $controller = new SearchController();
    $response   = $controller->searchRecipes($keyword, $categoryId);

    if ($response['success']) {
        $searchResults = $response['data'];
    }
}

// --- Output/View ---
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
</head>
<body>
    <h1>Search Results for "<?= htmlspecialchars($keyword) ?>"</h1>

    <?php if (!empty($searchResults)): ?>
        <?php foreach ($searchResults as $recipe): ?>
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <h3>
                    <a href="recipe-detail.php?id=<?= $recipe['id'] ?>">
                        <?= htmlspecialchars($recipe['title']) ?>
                    </a>
                </h3>
                <p>Category: <?= htmlspecialchars($recipe['category'] ?? 'Uncategorized') ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No results found matching your search criteria.</p>
    <?php endif; ?>
</body>
</html>