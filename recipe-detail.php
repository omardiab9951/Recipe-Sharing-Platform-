<?php
session_start();
require_once 'config/database.php';

// Get recipe ID from URL
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($recipe_id <= 0) {
    header('Location: index.php');
    exit;
}

// Initialize variables
$error = '';
$success = '';

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    if (!isset($_SESSION['user_id'])) {
        $error = 'You must be logged in to comment.';
    } else {
        $comment = trim($_POST['comment']);

        if (empty($comment)) {
            $error = 'Comment cannot be empty.';
        } else {
            try {
                // Insert comment into database
                $stmt = $pdo->prepare("
                    INSERT INTO comments (recipe_id, user_id, comment, created_at) 
                    VALUES (:recipe_id, :user_id, :comment, NOW())
                ");

                $result = $stmt->execute([
                    ':recipe_id' => $recipe_id,
                    ':user_id' => $_SESSION['user_id'],
                    ':comment' => $comment
                ]);

                if ($result) {
                    $success = 'Comment posted successfully!';
                    // Redirect to prevent form resubmission
                    header("Location: recipe-detail.php?id=" . $recipe_id . "&success=1");
                    exit;
                } else {
                    $error = 'Failed to post comment. Please try again.';
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
                error_log("Comment insert error: " . $e->getMessage());
            }
        }
    }
}

// Check for success message from redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = 'Comment posted successfully!';
}

// Fetch recipe details with author name
try {
    $stmt = $pdo->prepare("
        SELECT r.*, u.name as author_name 
        FROM recipes r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.id = :id
    ");
    $stmt->execute([':id' => $recipe_id]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recipe) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error fetching recipe: " . $e->getMessage());
}

// Fetch comments for this recipe
try {
    $stmt = $pdo->prepare("
        SELECT c.*, u.name as user_name 
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.recipe_id = :recipe_id 
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([':recipe_id' => $recipe_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $comments = [];
    error_log("Error fetching comments: " . $e->getMessage());
}

// Get category emoji function
function getCategoryEmoji($category)
{
    $emojis = [
        'breakfast' => '🍳',
        'lunch' => '🍱',
        'dinner' => '🍽️',
        'dessert' => '🍰',
        'snack' => '🍿',
        'beverage' => '🥤',
        'appetizer' => '🥗',
        'salad' => '🥗',
        'soup' => '🍲',
        'main' => '🍽️',
        'side' => '🍚',
        'other' => '🍴'
    ];
    return isset($emojis[strtolower($category)]) ? $emojis[strtolower($category)] : '🍴';
}

// Set page title
$page_title = htmlspecialchars($recipe['title']) . ' - Recipe Sharing Platform';

// Load HTML template
include 'templates/recipe-detail.html';
