<?php
session_start();
require_once 'config/database.php';

// Get category filter
$category_filter = isset($_GET['category']) ? trim($_GET['category']) : '';

// Fetch recipes
try {
  if (!empty($category_filter)) {
    $stmt = $pdo->prepare("
            SELECT recipes.*, users.name as author_name 
            FROM recipes 
            JOIN users ON recipes.user_id = users.id 
            WHERE recipes.category = ?
            ORDER BY recipes.created_at DESC
        ");
    $stmt->execute([$category_filter]);
  } else {
    $stmt = $pdo->query("
            SELECT recipes.*, users.name as author_name 
            FROM recipes 
            JOIN users ON recipes.user_id = users.id 
            ORDER BY recipes.created_at DESC
        ");
  }
  $recipes = $stmt->fetchAll();
} catch (PDOException $e) {
  $recipes = [];
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

// Categories list
$categories = ['Breakfast', 'Lunch', 'Dinner', 'Dessert', 'Snack', 'Beverage'];

// Set page title
$page_title = 'Browse Recipes - Recipe Sharing Platform';

// Load HTML template
include 'templates/browse.html';
