<?php
// File: api/recipes/get-recipes.php - API endpoint to retrieve all recipes (READ ALL)

// Include essential files (helper and controller)
require_once '../../helpers/api_helper.php';
require_once '../../controllers/RecipeController.php';

// --- 1. Validate Request Method (Must be GET) ---
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $data = ['success' => false, 'message' => 'Method not allowed.'];
    sendResponse($data, 405); // 405 Method Not Allowed
}

// --- 2. Initialize Controller ---
$controller = new RecipeController();

// --- 3. Call Controller Function ---
// The controller handles fetching data, setting the HTTP status code (200 or 404), and forming the result array.
$result = $controller->getAllRecipes();

// --- 4. Send Final Response ---
// Retrieve the HTTP status code set by the controller or default to 200
$httpCode = http_response_code() ?? 200; 

sendResponse($result, $httpCode);
?>