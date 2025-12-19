<?php

/**
 * File: search.php
 * Purpose: Search and browse recipes
 * Author: B2 Team Member
 * Date: December 2025
 */

require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Recipe.php';

// Get search parameters
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Get recipes
$conn = getDBConnection();
$recipeModel = new Recipe($conn);

if (!empty($search_term)) {
    $recipes = $recipeModel->searchRecipes($search_term);
    $page_title = "Search Results for: " . htmlspecialchars($search_term);
} elseif (!empty($category)) {
    $recipes = $recipeModel->getRecipesByCategory($category);
    $page_title = "Category: " . htmlspecialchars($category);
} else {
    $recipes = $recipeModel->getAllRecipes();
    $page_title = "All Recipes";
}

closeDBConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Recipe Sharing Platform</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/cards.css">
</head>

<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="search-page">
        <div class="container">
            <h1><?php echo $page_title; ?></h1>

            <!-- Search Bar -->
            <div class="search-section">
                <?php include __DIR__ . '/components/search-bar.php'; ?>
            </div>

            <!-- Category Filter -->
            <div class="category-filter">
                <h3>Filter by Category:</h3>
                <div class="category-buttons">
                    <a href="search.php" class="category-btn <?php echo empty($category) ? 'active' : ''; ?>">All</a>
                    <a href="search.php?category=Breakfast" class="category-btn <?php echo $category === 'Breakfast' ? 'active' : ''; ?>">Breakfast</a>
                    <a href="search.php?category=Lunch" class="category-btn <?php echo $category === 'Lunch' ? 'active' : ''; ?>">Lunch</a>
                    <a href="search.php?category=Dinner" class="category-btn <?php echo $category === 'Dinner' ? 'active' : ''; ?>">Dinner</a>
                    <a href="search.php?category=Dessert" class="category-btn <?php echo $category === 'Dessert' ? 'active' : ''; ?>">Dessert</a>
                    <a href="search.php?category=Snacks" class="category-btn <?php echo $category === 'Snacks' ? 'active' : ''; ?>">Snacks</a>
                    <a href="search.php?category=Beverages" class="category-btn <?php echo $category === 'Beverages' ? 'active' : ''; ?>">Beverages</a>
                </div>
            </div>

            <!-- Results Count -->
            <p class="results-count">
                Found <?php echo count($recipes); ?> recipe<?php echo count($recipes) !== 1 ? 's' : ''; ?>
            </p>

            <!-- Recipes Grid -->
            <?php if (empty($recipes)): ?>
                <div class="no-recipes">
                    <p>No recipes found matching your criteria.</p>
                    <a href="search.php" class="btn btn-primary">View All Recipes</a>
                </div>
            <?php else: ?>
                <div class="recipe-grid">
                    <?php foreach ($recipes as $recipe): ?>
                        <?php
                        // Pass recipe data to the recipe card component
                        $recipe_id = $recipe['id'];
                        $recipe_title = $recipe['title'];
                        $recipe_category = $recipe['category'];
                        $recipe_image = $recipe['image'];
                        $recipe_author = $recipe['author_name'];
                        $recipe_date = $recipe['created_at'];

                        // Get short description from ingredients
                        $recipe_description = substr($recipe['ingredients'], 0, 100) . '...';

                        // Include recipe card if file exists
                        if (file_exists(__DIR__ . '/components/recipe-card.php')) {
                            include __DIR__ . '/components/recipe-card.php';
                        }
                        ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="assets/js/search.js"></script>
</body>

</html>