<?php
// File: api/categories.php - API endpoint for Category CRUD operations (GET, POST, PUT, DELETE)

require_once '../helpers/api_helper.php';
require_once '../controllers/CategoryController.php';

// Initialize Controller
$controller = new CategoryController();

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Assume data comes in the request body for POST, PUT, DELETE
$data = getJsonData() ?? []; 

$result = [];

// --- 1. Handle OPTIONS (CORS Preflight) ---
if ($method === 'OPTIONS') {
    // Send a successful response with CORS headers (handled by sendResponse)
    sendResponse(['success' => true, 'message' => 'CORS check successful.'], 200);
}

// --- 2. Route Request to Controller Method ---
switch ($method) {
    case 'GET':
        $result = $controller->getAllCategories();
        break;

    case 'POST':
        $result = $controller->createCategory($data); 
        break;

    case 'PUT':
        $result = $controller->updateCategory($data);
        break;

    case 'DELETE':
        $result = $controller->deleteCategory($data);
        break;

    default:
        // Method Not Allowed
        $result = ['success' => false, 'message' => 'Method not allowed.'];
        http_response_code(405);
        break;
}

// --- 3. Send Final Response ---
// Retrieve the HTTP status code set by the controller or default to 200
$httpCode = http_response_code() ?? 200; 
// Note: The CategoryController now sets the HTTP status code directly.

sendResponse($result, $httpCode);
?>