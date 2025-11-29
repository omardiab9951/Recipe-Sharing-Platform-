<?php
// File: api/recipes/update-recipe.php - API endpoint for updating a recipe (UPDATE)

// Include helper functions and controller
require_once dirname(dirname(__DIR__)) . '/helpers/api_helper.php';
require_once dirname(dirname(__DIR__)) . '/controllers/RecipeController.php';

// --- 1. Validate Request Method (Must be PUT) ---
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    $data = ['success' => false, 'message' => 'Method not allowed.'];
    sendResponse($data, 405); // 405 Method Not Allowed
}

// --- 2. Read Raw JSON Data ---
// Use the helper function to read and decode the JSON body
$data = getJsonData();

// Check for existence of data
if (empty($data)) {
    $data = ['success' => false, 'message' => 'No data provided for update.'];
    sendResponse($data, 400); // 400 Bad Request
}

// --- 3. Initialize Controller and Execute Update ---
$controller = new RecipeController();

// The controller handles validation, database logic, and sets the HTTP status code (200, 400, 401)
$result = $controller->updateRecipe($data);

// --- 4. Send Final Response ---
// Retrieve the HTTP status code set by the controller or default to 200
$httpCode = http_response_code() ?? 200; 

sendResponse($result, $httpCode);
?>