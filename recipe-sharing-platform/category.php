<?php
// File: category.php - Displays recipes based on a specific category ID

require_once 'controllers/SearchController.php';
require_once 'controllers/CategoryController.php'; // Included, though not fully used here

$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$categoryName = 'All Recipes';
$recipesInCategory = [];

if ($categoryId) {
    
    // --- Data Fetching: Retrieve recipes using SearchController ---
    $searchController = new SearchController();
    // Search with only category ID (no keyword)
    $response = $searchController->searchRecipes(null, $categoryId); 

    if ($response['success']) {
        $recipesInCategory = $response['data'];
        
        // Try to derive the category name from the first recipe in the results
        if (!empty($recipesInCategory)) {
            $categoryName = $recipesInCategory[0]['category'] ?? "Unknown Category";
        }
    }
}

// --- Render the View ---
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($categoryName) ?></title>
</head>
<body>
    <h1>Recipes in Category: <?= htmlspecialchars($categoryName) ?></h1>

    <?php if (!empty($recipesInCategory)): ?>
        <?php foreach ($recipesInCategory as $recipe): ?>
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <h3>
                    <a href="recipe-detail.php?id=<?= $recipe['id'] ?>">
                        <?= htmlspecialchars($recipe['title']) ?>
                    </a>
                </h3>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>There are currently no recipes in this category.</p>
    <?php endif; ?>
</body>
</html>