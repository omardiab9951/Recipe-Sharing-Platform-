<?php
// File: api/comments/delete-comment.php - API endpoint for deleting a comment (DELETE)

require_once '../../helpers/api_helper.php';
require_once '../../controllers/CommentController.php';

// Validate Request Method (Must be DELETE)
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    $data = ['success' => false, 'message' => 'Method not allowed.'];
    sendResponse($data, 405); // 405 Method Not Allowed
}

// Read JSON Data from Request Body (DELETE request uses php://input)
$data = getJsonData();

// Check for data existence
if (empty($data)) {
    $data = ['success' => false, 'message' => 'No data received for deletion.'];
    sendResponse($data, 400); // 400 Bad Request
}

// Initialize Controller and execute deletion
$controller = new CommentController();
$result = $controller->deleteComment($data);

// Send Final Response (The Controller sets the HTTP status code)
$httpCode = http_response_code() ?? 200; 
sendResponse($result, $httpCode);
?>