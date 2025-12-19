<?php

/**
 * File: edit-recipe.php
 * Purpose: Edit recipe page
 * Author: B2 Team Member
 * Date: December 2025
 */

require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/controllers/RecipeController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Recipe.php';

// Require login
requireLogin();

// Get recipe ID
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($recipe_id <= 0) {
    header("Location: index.php");
    exit();
}

// Get recipe data
$conn = getDBConnection();
$recipeModel = new Recipe($conn);
$recipe = $recipeModel->getRecipeById($recipe_id);

// Check if recipe exists and user owns it
if (!$recipe || !$recipeModel->isOwner($recipe_id, $_SESSION['user_id'])) {
    closeDBConnection($conn);
    header("Location: index.php");
    exit();
}

closeDBConnection($conn);

$recipeController = new RecipeController();
$authController = new AuthController();
$errors = [];
$success_message = '';

// Form values
$title = $recipe['title'];
$category = $recipe['category'];
$ingredients = $recipe['ingredients'];
$instructions = $recipe['instructions'];

// Generate CSRF token
$csrf_token = $authController->generateCSRFToken();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !$authController->verifyCSRFToken($_POST['csrf_token'])) {
        $errors['general'] = 'Invalid request. Please try again.';
    } else {
        $title = $_POST['title'] ?? '';
        $category = $_POST['category'] ?? '';
        $ingredients = $_POST['ingredients'] ?? '';
        $instructions = $_POST['instructions'] ?? '';
        $image = $_FILES['image'] ?? null;

        $result = $recipeController->updateRecipe(
            $recipe_id,
            $_SESSION['user_id'],
            $title,
            $category,
            $ingredients,
            $instructions,
            $image
        );

        if ($result['success']) {
            $success_message = $result['message'];
            // Redirect to recipe detail page
            header("Location: recipe-detail.php?id=" . $recipe_id);
            exit();
        } else {
            $errors = $result['errors'];
        }
    }
}

// Categories list
$categories = ['Breakfast', 'Lunch', 'Dinner', 'Dessert', 'Snacks', 'Beverages'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe - Recipe Sharing Platform</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/forms.css">
</head>

<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="edit-recipe-container">
        <div class="container">
            <h1>Edit Recipe</h1>

            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($errors['general']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="edit-recipe.php?id=<?php echo $recipe_id; ?>" enctype="multipart/form-data" class="recipe-form">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <div class="form-group">
                    <label for="title">Recipe Title *</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="<?php echo htmlspecialchars($title); ?>"
                        required>
                    <?php if (isset($errors['title'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['title']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="category">Category *</label>
                    <select id="category" name="category" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat; ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                                <?php echo $cat; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['category'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['category']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="ingredients">Ingredients *</label>
                    <textarea
                        id="ingredients"
                        name="ingredients"
                        rows="6"
                        required><?php echo htmlspecialchars($ingredients); ?></textarea>
                    <?php if (isset($errors['ingredients'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['ingredients']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="instructions">Instructions *</label>
                    <textarea
                        id="instructions"
                        name="instructions"
                        rows="8"
                        required><?php echo htmlspecialchars($instructions); ?></textarea>
                    <?php if (isset($errors['instructions'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['instructions']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="image">Update Recipe Image (Optional)</label>
                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept="image/jpeg,image/jpg,image/png,image/gif">
                    <small>Current image: <?php echo htmlspecialchars($recipe['image']); ?></small>
                    <?php if (isset($errors['image'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['image']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Recipe</button>
                    <a href="recipe-detail.php?id=<?php echo $recipe_id; ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>

</html>