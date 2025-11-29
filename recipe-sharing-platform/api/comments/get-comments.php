<?php
// File: api/comments/get-comments.php - API endpoint to retrieve comments for a specific recipe (READ)

require_once '../../helpers/api_helper.php';
require_once '../../controllers/CommentController.php';

// --- 1. Validate Request Method (Must be GET) ---
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $data = ['success' => false, 'message' => 'Method not allowed.'];
    sendResponse($data, 405); // 405 Method Not Allowed
}

// --- 2. Get Recipe ID from Query Parameters ---
// Sanitize input: ensure it's an integer if present
$recipeId = isset($_GET['recipe_id']) && is_numeric($_GET['recipe_id']) ? (int)$_GET['recipe_id'] : null;

// Check for missing or invalid ID early
if ($recipeId === null) {
    $data = ['success' => false, 'message' => 'Missing or invalid recipe ID parameter.'];
    sendResponse($data, 400); // 400 Bad Request
}

// --- 3. Initialize Controller and Call Function ---
$controller = new CommentController();

// The controller handles fetching data, validation, and sets the HTTP status code (200, 404, 400)
$result = $controller->getCommentsByRecipe($recipeId);

// --- 4. Send Final Response ---
// Retrieve the HTTP status code set by the controller or default to 200
$httpCode = http_response_code() ?? 200; 

sendResponse($result, $httpCode);
?>