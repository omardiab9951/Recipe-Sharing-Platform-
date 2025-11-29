<?php
// File: recipe-detail.php - Fetches recipe and comments data (B2 Responsibility)

// require_once 'includes/header.php'; // B1 includes (Commented out for B2 Focus)
require_once 'controllers/RecipeController.php';
require_once 'controllers/CommentController.php';

$recipeId    = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$recipeData  = null;
$commentsData = null;

// Check for a valid recipe ID in the URL
if ($recipeId > 0) {

    // 1. Fetch Recipe Data
    $recipeController = new RecipeController();
    $recipeResponse   = $recipeController->getOneRecipe($recipeId);

    if ($recipeResponse['success']) {
        $recipeData = $recipeResponse['data'];

        // 2. Fetch Comments Data
        $commentController = new CommentController();
        $commentsResponse  = $commentController->getCommentsByRecipe($recipeId);
        
        if ($commentsResponse['success']) {
            $commentsData = $commentsResponse['data'];
        }
    }
}

// 3. Render the View
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recipe Details</title>
</head>
<body>
    <main>
        <?php if ($recipeData): ?>
            <article>
                <h1><?= htmlspecialchars($recipeData['title']) ?></h1>
                <p><strong>By:</strong> <?= htmlspecialchars($recipeData['user_name']) ?></p>
                <hr>

                <h3>Ingredients</h3>
                <pre><?= htmlspecialchars($recipeData['ingredients']) ?></pre>

                <h3>Instructions</h3>
                <pre><?= htmlspecialchars($recipeData['instructions']) ?></pre>

                <hr>

                <h3>Comments (<?= count($commentsData ?? []) ?>)</h3>
                <section class="comments-section">
                    <?php if ($commentsData): ?>
                        <?php foreach ($commentsData as $comment): ?>
                            <div class="comment-item" style="border: 1px dashed #ccc; padding: 10px; margin-bottom: 5px;">
                                <p><strong><?= htmlspecialchars($comment['user_name']) ?>:</strong></p>
                                <p><?= htmlspecialchars($comment['comment_text']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Be the first to comment on this recipe.</p>
                    <?php endif; ?>
                </section>
            </article>
        <?php else: ?>
            <h2>Recipe Not Found.</h2>
        <?php endif; ?>
    </main>
</body>
</html>