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

// Fetch recipe details
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
} catch (PDOException $e) {
    die("Error fetching recipe: " . $e->getMessage());
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $ingredients = isset($_POST['ingredients']) ? trim($_POST['ingredients']) : '';
    $instructions = isset($_POST['instructions']) ? trim($_POST['instructions']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $prep_time = isset($_POST['prep_time']) ? intval($_POST['prep_time']) : 0;
    $cook_time = isset($_POST['cook_time']) ? intval($_POST['cook_time']) : 0;
    $servings = isset($_POST['servings']) ? intval($_POST['servings']) : 0;

    // Validation
    if (empty($title) || empty($ingredients) || empty($instructions) || empty($category)) {
        $error = 'Please fill in all required fields';
    } else {
        // Handle image upload (optional)
        $image = $recipe['image']; // Keep existing image by default

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'assets/uploads/';

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // Delete old image if exists
                    if (!empty($recipe['image']) && file_exists($recipe['image'])) {
                        unlink($recipe['image']);
                    }
                    $image = $upload_path;
                } else {
                    $error = 'Failed to upload image';
                }
            } else {
                $error = 'Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed';
            }
        }

        // Update recipe in database
        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("
                    UPDATE recipes 
                    SET title = ?, description = ?, ingredients = ?, instructions = ?, category = ?, 
                        prep_time = ?, cook_time = ?, servings = ?, image = ?
                    WHERE id = ?
                ");

                if ($stmt->execute([$title, $description, $ingredients, $instructions, $category, $prep_time, $cook_time, $servings, $image, $recipe_id])) {
                    header("Location: recipe-detail.php?id=" . $recipe_id);
                    exit;
                } else {
                    $error = 'Failed to update recipe. Please try again.';
                }
            } catch (PDOException $e) {
                $error = 'Database error. Please try again later.';
            }
        }
    }
}

// Set page title
$page_title = 'Edit Recipe - Recipe Sharing Platform';

// Load HTML template
include 'templates/edit-recipe.html';
