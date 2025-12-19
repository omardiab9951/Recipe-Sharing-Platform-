<?php

/**
 * File: recipe-card.php
 * Purpose: Reusable recipe card component
 * Author: B2 Team Member (used by F1 for styling)
 * Date: December 2025
 */

// This component expects these variables to be set:
// $recipe_id, $recipe_title, $recipe_category, $recipe_image, $recipe_author, $recipe_date, $recipe_description
?>

<div class="recipe-card">
  <a href="recipe-detail.php?id=<?php echo $recipe_id; ?>" class="recipe-card-link">
    <div class="recipe-card-image">
      <img src="assets/uploads/recipes/<?php echo htmlspecialchars($recipe_image); ?>"
        alt="<?php echo htmlspecialchars($recipe_title); ?>"
        onerror="this.src='assets/images/default-recipe.jpg'">
      <span class="recipe-category-badge"><?php echo htmlspecialchars($recipe_category); ?></span>
    </div>

    <div class="recipe-card-content">
      <h3 class="recipe-card-title"><?php echo htmlspecialchars($recipe_title); ?></h3>
      <p class="recipe-card-description"><?php echo htmlspecialchars($recipe_description); ?></p>

      <div class="recipe-card-meta">
        <span class="recipe-author">By <?php echo htmlspecialchars($recipe_author); ?></span>
        <span class="recipe-date"><?php echo date('M j, Y', strtotime($recipe_date)); ?></span>
      </div>
    </div>
  </a>
</div>