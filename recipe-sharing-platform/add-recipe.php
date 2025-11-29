<?php
// File: add-recipe.php - Handles the submission of the 'Add Recipe' form (B2 Bridge)

// Assumes B1 files are here for includes and session check
// require_once 'includes/header.php'; 
require_once 'controllers/RecipeController.php';

$message = $errors = [];

// Check if the user submitted data (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Collect form data (using $_POST, different from JSON API calls)
    $data = [
        'title'         => $_POST['title'] ?? '',
        'category'      => $_POST['category'] ?? '',
        'ingredients'   => $_POST['ingredients'] ?? '',
        'instructions'  => $_POST['instructions'] ?? '',
        'image_url'     => $_POST['image_url'] ?? null,
        'user_id'       => 1 // Placeholder: Must be retrieved from B1's session
    ];

    $controller = new RecipeController();
    $response   = $controller->createRecipe($data); // Call the B2 Controller

    if ($response['success']) {
        $message[] = "✅ Recipe added successfully!";
        // Optional: Redirect to the new recipe page using the returned ID
        // header("Location: recipe-detail.php?id=" . $response['id']);
    } else {
        // Save errors to display to the user
        $errors = $response['errors'] ?? [$response['message']];
    }
}

// 2. Render the Page (The HTML structure is primarily F1's focus)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Recipe</title>
</head>
<body>
    <h1>Add New Recipe</h1>

    <?php 
    // Display success or error messages
    if (!empty($message)) echo "<div style='color:green;'>".implode('<br>', $message)."</div>"; 
    
    if (!empty($errors)) {
        echo "<div style='color:red;'>";
        foreach($errors as $err) echo "<li>Error: $err</li>";
        echo "</div>";
    } 
    ?>

    <form method="POST" action="add-recipe.php">
        <label>Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Category:</label><br>
        <input type="text" name="category" required><br><br>

        <label>Ingredients:</label><br>
        <textarea name="ingredients" required></textarea><br><br>

        <label>Instructions:</label><br>
        <textarea name="instructions" required></textarea><br><br>

        <input type="hidden" name="user_id" value="1"> 
        <button type="submit">Add Recipe</button>
    </form>
</body>
</html>