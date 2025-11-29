<?php
// File: helpers/api_helper.php
// Contains helper functions for all B2 API endpoints

/**
 * Sends a standardized JSON response back to the client.
 *
 * @param array $data The data array to be encoded and sent.
 * @param int $httpCode The HTTP status code to set (default is 200).
 */
function sendResponse(array $data, int $httpCode = 200): void {
    
    // Set CORS and Content-Type headers
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Max-Age: 3600");
    
    // Set the HTTP status code
    http_response_code($httpCode); 

    // Send data as JSON, using JSON_UNESCAPED_UNICODE for proper UTF-8/Arabic support
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

/**
 * Reads JSON input from the request body (used for POST, PUT requests).
 *
 * @return array|null Returns the decoded data array or null on failure.
 */
function getJsonData(): ?array {
    $data = file_get_contents("php://input");
    return json_decode($data, true);
}