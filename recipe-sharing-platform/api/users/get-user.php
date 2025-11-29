<?php
// File: api/users/get-user.php - API endpoint to retrieve a single user's details

// Include the Controller (Go up two directories: api/ -> project_root/)
require_once dirname(__DIR__, 2) . '/controllers/UserController.php';
// Include the helper to standardize responses (Assuming it's available)
require_once dirname(__DIR__, 2) . '/helpers/api_helper.php';

// --- 1. Validate Request Method (Must be GET) ---
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $data = ['success' => false, 'message' => 'Method not allowed.'];
    sendResponse($data, 405); // 405 Method Not Allowed
}

// --- 2. Initialize Controller ---
$controller = new UserController();

// --- 3. Get User ID from Query Parameters ---
// Sanitize input (ensure it's an integer if present)
$userId = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ? (int)$_GET['user_id'] : null;

// Handle missing ID early
if ($userId === null) {
    $data = ['success' => false, 'message' => 'Missing or invalid user_id parameter.'];
    sendResponse($data, 400); // 400 Bad Request
}

// --- 4. Call Controller Function ---
// The controller handles internal validation, database logic, and sets the HTTP status code (200, 404, 400)
$result = $controller->readUser($userId);

// --- 5. Send Final Response ---
// Retrieve the HTTP status code set by the controller or default to 200
$httpCode = http_response_code() ?? 200; 

sendResponse($result, $httpCode);
?>