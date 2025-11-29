<?php
// File: api/search.php - API endpoint for recipe search and filtering

// Include required files
require_once '../helpers/api_helper.php';
require_once '../controllers/SearchController.php';

// --- 1. Validate Request Method (Must be GET) ---
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $data = ['success' => false, 'message' => 'Method not allowed.'];
    sendResponse($data, 405); // 405 Method Not Allowed
}

// --- 2. Extract Search Criteria from Query Parameters ---
// 'q' for query term, 'cat' for category ID
// Note: The SearchController will handle sanitization and further validation.
$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : ''; 
// Ensure category ID is treated as an integer or null
$categoryId = isset($_GET['cat']) && is_numeric($_GET['cat']) ? (int)trim($_GET['cat']) : null; 

// --- 3. Check for Minimum Criteria ---
// Must provide at least a search term OR a category ID
if (empty($searchTerm) && empty($categoryId)) {
    $data = ['success' => false, 'message' => 'Please provide a search term or select a category.'];
    sendResponse($data, 400); // 400 Bad Request
}

// --- 4. Initialize Controller and Execute Search ---
$controller = new SearchController();

// The controller handles setting the HTTP status code (200, 404, 500) and returns the result array.
$result = $controller->searchRecipes($searchTerm, $categoryId);

// --- 5. Send Final Response ---
// The sendResponse helper reads the HTTP status code set by the controller and uses the result data.
// Note: Since the controller already sets the HTTP status code, we pass the result directly.
$httpCode = http_response_code();
sendResponse($result, $httpCode);

?>