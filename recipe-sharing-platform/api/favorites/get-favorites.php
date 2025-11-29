<?php
// File: api/favorites/get-favorites.php - API endpoint to retrieve a user's favorite recipes

// Include essential files (helper and controller)
require_once '../../helpers/api_helper.php';
require_once '../../controllers/FavoriteController.php';

// --- 1. Validate Request Method (Must be GET) ---
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $data = ['success' => false, 'message' => 'Method not allowed.'];
    sendResponse($data, 405); // 405 Method Not Allowed
}

// --- 2. Get User ID from Query Parameters ---
// Sanitize input: ensure it's an integer if present
$userId = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ? (int)$_GET['user_id'] : null;

// Check for missing or invalid ID early
if ($userId === null) {
    $data = ['success' => false, 'message' => 'Missing or invalid User ID parameter.'];
    sendResponse($data, 400); // 400 Bad Request
}

// --- 3. Initialize Controller and Call Function ---
$controller = new FavoriteController();

// The controller handles fetching data, validation, and sets the HTTP status code (200, 404, 400)
$result = $controller->getFavorites($userId);

// --- 4. Send Final Response ---
// Retrieve the HTTP status code set by the controller or default to 200
$httpCode = http_response_code() ?? 200; 

sendResponse($result, $httpCode);
?>