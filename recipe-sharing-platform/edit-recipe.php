<?php
// File: edit-recipe.php - Handles displaying the edit form and processing the update (POST)

require_once 'controllers/RecipeController.php';

$recipeId   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$recipeData = null;
$message    = $errors = [];

$controller = new RecipeController();

// 1. Process Update Request (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id'            => $_POST['id'] ?? $recipeId, // Confirm ID
        'title'         => $_POST['title'] ?? '',
        'category'      => $_POST['category'] ?? '',
        'ingredients'   => $_POST['ingredients'] ?? '',
        'instructions'  => $_POST['instructions'] ?? '',
        'user_id'       => 1 // Placeholder: Must be retrieved from B1's session
    ];

    $response = $controller->updateRecipe($data); // Call the update function

    if ($response['success']) {
        $message[] = "✅ Recipe updated successfully!";
    } else {
        $errors = $response['errors'] ?? [$response['message']];
    }
}

// 2. Fetch Recipe Data for Form Display (GET)
if ($recipeId > 0) {
    // If a POST request failed, $recipeData remains null, so we re-fetch the latest data.
    $getResponse = $controller->getOneRecipe($recipeId);
    
    if ($getResponse['success']) {
        $recipeData = $getResponse['data'];
    }
}

// 3. Render the View
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Recipe</title>
</head>
<body>
    <h1>Edit Recipe: <?= htmlspecialchars($recipeData['title'] ?? 'Not Found') ?></h1>

    <?php if (!empty($message)) echo "<div style='color:green;'>".implode('<br>', $message)."</div>"; ?>
    
    <?php if (!empty($errors)) {
        echo "<div style='color:red;'>";
        foreach($errors as $err) echo "<li>Error: $err</li>";
        echo "</div>";
    } ?>

    <?php if ($recipeData): ?>
    <form method="POST" action="edit-recipe.php?id=<?= $recipeData['id'] ?>">
        <input type="hidden" name="id" value="<?= $recipeData['id'] ?>">
        
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($recipeData['title']) ?>" required><br><br>
        
        <button type="submit">Save Changes</button>
    </form>
    <?php else: ?>
        <p>The requested recipe was not found or you do not have permission to edit it.</p>
    <?php endif; ?>
</body>
</html>