<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
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
    $prep_time = isset($_POST['prep_time']) && !empty($_POST['prep_time']) ? intval($_POST['prep_time']) : 0;
    $cook_time = isset($_POST['cook_time']) && !empty($_POST['cook_time']) ? intval($_POST['cook_time']) : 0;
    $servings = isset($_POST['servings']) && !empty($_POST['servings']) ? intval($_POST['servings']) : 0;

    // Validation
    if (empty($title) || empty($ingredients) || empty($instructions) || empty($category)) {
        $error = 'Please fill in all required fields (Title, Ingredients, Instructions, Category)';
    } else {
        // Handle image upload (optional)
        $image = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'assets/uploads/';

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_extension, $allowed_extensions)) {
                // Check file size (max 5MB)
                if ($_FILES['image']['size'] <= 5242880) {
                    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
                    $upload_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                        $image = $upload_path;
                    } else {
                        $error = 'Failed to upload image. Check folder permissions.';
                    }
                } else {
                    $error = 'Image is too large. Maximum size is 5MB.';
                }
            } else {
                $error = 'Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.';
            }
        }

        // Insert recipe into database
        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO recipes (user_id, title, description, ingredients, instructions, category, prep_time, cook_time, servings, image, created_at) 
                    VALUES (:user_id, :title, :description, :ingredients, :instructions, :category, :prep_time, :cook_time, :servings, :image, NOW())
                ");

                $result = $stmt->execute([
                    ':user_id' => $_SESSION['user_id'],
                    ':title' => $title,
                    ':description' => $description,
                    ':ingredients' => $ingredients,
                    ':instructions' => $instructions,
                    ':category' => $category,
                    ':prep_time' => $prep_time,
                    ':cook_time' => $cook_time,
                    ':servings' => $servings,
                    ':image' => $image
                ]);

                if ($result) {
                    $recipe_id = $pdo->lastInsertId();
                    $_SESSION['success'] = 'Recipe added successfully!';
                    header("Location: recipe-detail.php?id=" . $recipe_id);
                    exit;
                } else {
                    $error = 'Failed to add recipe. Please try again.';
                }
            } catch (PDOException $e) {
                // Show specific error for debugging
                $error = 'Database error: ' . $e->getMessage();
                // Log the error (optional)
                error_log("Recipe insert error: " . $e->getMessage());
            }
        }
    }
}

// Set page title
$page_title = 'Add New Recipe - Recipe Sharing Platform';

// Load HTML template
include 'templates/add-recipe.html';
