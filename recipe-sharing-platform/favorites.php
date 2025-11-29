<?php
// File: favorites.php - Displays the current user's favorite recipes

require_once 'controllers/FavoriteController.php';

// --- User Context ---
$userId = 1; // Placeholder: Must be retrieved from B1's session
$favoritesData = [];

// --- Data Fetching ---
$controller = new FavoriteController();
// Call the controller function to retrieve favorite recipes
$response = $controller->getFavorites($userId); 

if ($response['success']) {
    $favoritesData = $response['data'];
}

// --- Render the View ---
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Favorites</title>
</head>
<body>
    <h1>Favorite Recipes</h1>
    
    <?php if (!empty($favoritesData)): ?>
        <?php foreach ($favoritesData as $recipe): ?>
            <div style="border: 1px solid gold; padding: 10px; margin-bottom: 10px;">
                <h3>
                    <a href="recipe-detail.php?id=<?= $recipe['id'] ?>">
                        <?= htmlspecialchars($recipe['title']) ?>
                    </a>
                </h3>
                <p>Category: <?= htmlspecialchars($recipe['category']) ?></p>
                <p>By: <?= htmlspecialchars($recipe['user_name']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You have not added any recipes to your favorites yet.</p>
    <?php endif; ?>
</body>
</html>