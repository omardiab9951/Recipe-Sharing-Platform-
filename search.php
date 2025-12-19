<?php
session_start();
require_once 'config/database.php';

// Get search query
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

$recipes = [];

// Search recipes if query exists
if (!empty($search_query)) {
    try {
        $search_term = '%' . $search_query . '%';
        $stmt = $pdo->prepare("
            SELECT recipes.*, users.name as author_name 
            FROM recipes 
            JOIN users ON recipes.user_id = users.id 
            WHERE recipes.title LIKE ? 
               OR recipes.description LIKE ? 
               OR recipes.ingredients LIKE ? 
               OR recipes.category LIKE ?
            ORDER BY recipes.created_at DESC
        ");
        $stmt->execute([$search_term, $search_term, $search_term, $search_term]);
        $recipes = $stmt->fetchAll();
    } catch (PDOException $e) {
        $recipes = [];
    }
}

// Get emoji based on category
function getCategoryEmoji($category)
{
    $emojis = [
        'Breakfast' => '🥞',
        'Lunch' => '🥗',
        'Dinner' => '🍽️',
        'Dessert' => '🍰',
        'Snack' => '🍿',
        'Beverage' => '🥤'
    ];
    return isset($emojis[$category]) ? $emojis[$category] : '🍴';
}

// Set page title
$page_title = 'Search Recipes - Recipe Sharing Platform';

// Load HTML template
include 'templates/search.html';
