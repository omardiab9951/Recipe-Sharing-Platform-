<?php
// File: api/recipes/create-recipe.php - API endpoint for creating a new recipe (CREATE)

// Include essential files (helper and controller)
require_once '../../helpers/api_helper.php';
require_once '../../controllers/RecipeController.php';

// --- 1. Validate Request Method (Must be POST) ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $data = ['success' => false, 'message' => 'Method not allowed.'];
    sendResponse($data, 405); // 405 Method Not Allowed
}

// --- 2. Read Raw JSON Data ---
// Use the helper function to read and decode the JSON body
$data = getJsonData();

// Check for existence of data
if (empty($data)) {
    $data = ['success' => false, 'message' => 'No data received.'];
    sendResponse($data, 400); // 400 Bad Request
}

// --- 3. Initialize Controller and Call Function ---
$controller = new RecipeController();

// The controller handles validation, database logic, and sets the HTTP status code (201, 400, 503)
$result = $controller->createRecipe($data);

// --- 4. Send Final Response ---
// Retrieve the HTTP status code set by the controller or default to 200
$httpCode = http_response_code() ?? 200; 

sendResponse($result, $httpCode);
?>