<?php
require_once 'config/database.php';

echo "<h2>Fixing Image Paths...</h2>";

// Get all recipes
$stmt = $pdo->query("SELECT id, title, image FROM recipes");
$recipes = $stmt->fetchAll();

$fixed = 0;

foreach ($recipes as $recipe) {
  if (!empty($recipe['image'])) {
    // Get just the filename (like "spaghetti.jpg")
    $filename = basename($recipe['image']);

    // New path should be "uploads/spaghetti.jpg"
    $new_path = 'uploads/' . $filename;

    // Update database
    $stmt = $pdo->prepare("UPDATE recipes SET image = ? WHERE id = ?");
    $stmt->execute([$new_path, $recipe['id']]);

    echo "✅ Fixed: " . htmlspecialchars($recipe['title']) . "<br>";
    $fixed++;
  }
}

echo "<br><strong>✅ Done! Fixed $fixed recipes</strong>";
echo '<br><br><a href="index.php" style="background: #d97706; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px;">Go to Homepage</a>';
