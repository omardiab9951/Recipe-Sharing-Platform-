<?php
// File: api/favorites/remove-favorite.php - API endpoint to remove a recipe from favorites

// Include essential files (helper, config, and controller)
require_once dirname(__DIR__, 2) . '/helpers/api_helper.php'; 
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/controllers/FavoriteController.php';

// --- 1. Validate Request Method (Allow DELETE or POST for compatibility) ---
$method = $_SERVER['REQUEST_METHOD'];
$allowedMethods = ['DELETE', 'POST'];

if (!in_array($method, $allowedMethods)) {
    $data = ['success' => false, 'message' => 'Method not allowed. Use DELETE or POST.'];
    sendResponse($data, 405); // 405 Method Not Allowed
}

// --- 2. Read JSON Data from Request Body (Applicable to DELETE and POST) ---
// Using getJsonData() abstracts reading the raw input regardless of the method (DELETE/PUT)
$data = getJsonData();

// --- 3. Check for Data Existence ---
if (empty($data)) {
    $data = ['success' => false, 'message' => 'Missing data. user_id and recipe_id are required.'];
    sendResponse($data, 400); // 400 Bad Request
}

// --- 4. Initialize Controller and Call Function ---
$controller = new FavoriteController();

// The controller handles validation, database logic, and sets the HTTP status code (200, 400, 503)
$result = $controller->removeFavorite($data);

// --- 5. Send Final Response ---
// Retrieve the HTTP status code set by the controller or default to 200
$httpCode = http_response_code() ?? 200; 

sendResponse($result, $httpCode);
?>