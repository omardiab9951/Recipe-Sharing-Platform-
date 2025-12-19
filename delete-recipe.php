<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get recipe ID
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($recipe_id <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch recipe to check ownership
try {
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
    $stmt->execute([$recipe_id]);
    $recipe = $stmt->fetch();

    if (!$recipe) {
        header('Location: index.php');
        exit;
    }

    // Check if user owns this recipe
    if ($recipe['user_id'] != $_SESSION['user_id']) {
        header('Location: index.php');
        exit;
    }

    // Delete recipe image if exists
    if (!empty($recipe['image']) && file_exists($recipe['image'])) {
        unlink($recipe['image']);
    }

    // Delete comments first (foreign key constraint)
    $stmt = $pdo->prepare("DELETE FROM comments WHERE recipe_id = ?");
    $stmt->execute([$recipe_id]);

    // Delete recipe
    $stmt = $pdo->prepare("DELETE FROM recipes WHERE id = ?");
    $stmt->execute([$recipe_id]);

    // Redirect to home
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    die("Error deleting recipe: " . $e->getMessage());
}
