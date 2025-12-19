<?php
session_start();
require_once 'config/database.php';

// Fetch all recipes
try {
  $stmt = $pdo->query("
        SELECT recipes.*, users.name as author_name 
        FROM recipes 
        JOIN users ON recipes.user_id = users.id 
        ORDER BY recipes.created_at DESC
    ");
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

// Set page title
$page_title = 'Home - Recipe Sharing Platform';

// Load HTML template
include 'templates/home.html';
