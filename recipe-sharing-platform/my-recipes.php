<?php
// File: my-recipes.php - Displays the current user's recipes

require_once 'controllers/RecipeController.php';

// --- User Context ---
$userId = 1; // Placeholder: Must be retrieved from B1's session 
$recipesData = [];

// --- Data Fetching ---
$controller = new RecipeController();
// Note: Assumes getUserRecipes($userId) function exists in the Controller
$response = $controller->getUserRecipes($userId); 

if ($response['success']) {
    $recipesData = $response['data'];
}

// --- Render the View ---
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Recipes</title>
</head>
<body>
    <h1>My Recipes</h1>
    
    <?php if (!empty($recipesData)): ?>
        <?php foreach ($recipesData as $recipe): ?>
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <h3>
                    <a href="recipe-detail.php?id=<?= $recipe['id'] ?>">
                        <?= htmlspecialchars($recipe['title']) ?>
                    </a>
                </h3>
                <p>Category: <?= htmlspecialchars($recipe['category']) ?></p>
                
                <a href="edit-recipe.php?id=<?= $recipe['id'] ?>">Edit</a> | 
                <a href="delete-recipe.php?id=<?= $recipe['id'] ?>" onclick="return confirm('Are you sure you want to delete this recipe?')">Delete</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You have not added any recipes yet.</p>
    <?php endif; ?>
</body>
</html>