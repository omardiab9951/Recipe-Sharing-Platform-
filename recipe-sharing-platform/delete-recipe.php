<?php
// File: delete-recipe.php - Handles the request to delete a recipe (usually follows a confirmation)

require_once 'controllers/RecipeController.php';

// --- Input & Context ---
$recipeId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$userId   = 1; // Placeholder: Must be retrieved from B1's session

if ($recipeId) {
    // Data passed to the controller for deletion and ownership check
    $data = ['id' => $recipeId, 'user_id' => $userId];
    $controller = new RecipeController();
    $response   = $controller->deleteRecipe($data);

    if ($response['success']) {
        // Success: Redirect to the user's recipes page with a success message
        header('Location: my-recipes.php?message=Recipe deleted successfully');
        exit;
    } else {
        // Failure: Redirect with an encoded error message
        header('Location: my-recipes.php?error=' . urlencode($response['message']));
        exit;
    }
} else {
    // If no ID is provided, redirect with an error message
    header('Location: my-recipes.php?error=Missing Recipe ID');
    exit;
}