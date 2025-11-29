<?php
// File: api/recipes/delete-recipe.php - API endpoint for deleting a recipe (DELETE)

// Include helper functions and controller
require_once dirname(dirname(__DIR__)) . '/helpers/api_helper.php';
require_once dirname(dirname(__DIR__)) . '/controllers/RecipeController.php';

// --- 1. Validate Request Method (Must be DELETE) ---
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    $data = ['success' => false, 'message' => 'Method not allowed.'];
    sendResponse($data, 405); // 405 Method Not Allowed
}

// --- 2. Read Raw JSON Data ---
// Use the helper function to read and decode the JSON body (DELETE usually uses body for security/ownership)
$data = getJsonData();

// Check for essential data (ID and User ID)
if (empty($data['id']) || empty($data['user_id'])) {
    $data = ['success' => false, 'message' => 'Missing Recipe ID or User ID for deletion.'];
    sendResponse($data, 400); // 400 Bad Request
}

// --- 3. Initialize Controller and Execute Deletion ---
$controller = new RecipeController();

// The controller handles ownership check, database logic, and sets the HTTP status code (200, 400, 401, 404)
$result = $controller->deleteRecipe($data);

// --- 4. Send Final Response ---
// Retrieve the HTTP status code set by the controller or default to 200
$httpCode = http_response_code() ?? 200; 

sendResponse($result, $httpCode);
?>