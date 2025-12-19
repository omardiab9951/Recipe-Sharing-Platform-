<?php

/**
 * File: delete-recipe.php
 * Purpose: Delete recipe handler
 * Author: B2 Team Member
 * Date: December 2025
 */

require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/controllers/RecipeController.php';

// Require login
requireLogin();

// Get recipe ID
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($recipe_id <= 0) {
    header("Location: index.php");
    exit();
}

// Delete recipe
$recipeController = new RecipeController();
$result = $recipeController->deleteRecipe($recipe_id, $_SESSION['user_id']);

if ($result['success']) {
    $_SESSION['success_message'] = $result['message'];
} else {
    $_SESSION['error_message'] = $result['errors']['general'] ?? 'Failed to delete recipe';
}

// Redirect to homepage
header("Location: index.php");
exit();
