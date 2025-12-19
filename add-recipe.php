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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $ingredients = trim($_POST['ingredients']);
    $instructions = trim($_POST['instructions']);
    $category = trim($_POST['category']);
    $prep_time = intval($_POST['prep_time']);
    $cook_time = intval($_POST['cook_time']);
    $servings = intval($_POST['servings']);

    // Validate required fields
    if (empty($title) || empty($ingredients) || empty($instructions) || empty($category)) {
        $error = 'Please fill in all required fields.';
    } else {
        try {
            // Handle image upload
            $image_path = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/recipes/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($file_extension, $allowed_extensions)) {
                    $new_filename = uniqid() . '.' . $file_extension;
                    $target_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                        $image_path = $target_path;
                    }
                }
            }

            // INSERT ONLY - Never delete existing recipes!
            $stmt = $pdo->prepare("
                INSERT INTO recipes (
                    user_id, title, description, ingredients, instructions, 
                    category, prep_time, cook_time, servings, image, created_at
                ) VALUES (
                    :user_id, :title, :description, :ingredients, :instructions, 
                    :category, :prep_time, :cook_time, :servings, :image, NOW()
                )
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
                ':image' => $image_path
            ]);

            if ($result) {
                $success = 'Recipe added successfully!';
                header('Location: index.php?success=1');
                exit;
            } else {
                $error = 'Failed to add recipe. Please try again.';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
            error_log("Recipe insert error: " . $e->getMessage());
        }
    }
}

// Load HTML template
include 'templates/add-recipe.html';
