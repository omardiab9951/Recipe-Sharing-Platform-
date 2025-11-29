<?php
// File: api/comments/update-comment.php - API endpoint for updating a comment (UPDATE)

require_once '../../helpers/api_helper.php';
require_once '../../controllers/CommentController.php';

// Validate Request Method (Must be PUT)
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    $data = ['success' => false, 'message' => 'Method not allowed.'];
    sendResponse($data, 405); // 405 Method Not Allowed
}

// Read JSON Data from Request Body
$data = getJsonData();

// Check for data existence
if (empty($data)) {
    $data = ['success' => false, 'message' => 'No data received for update.'];
    sendResponse($data, 400); // 400 Bad Request
}

// Initialize Controller and execute update
$controller = new CommentController();
$result = $controller->updateComment($data);

// Send Final Response (The Controller sets the HTTP status code)
$httpCode = http_response_code() ?? 200; 
sendResponse($result, $httpCode);
?>