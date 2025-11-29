<?php
// File: api/upload-image.php - Handles image file uploads for recipes

// Include helper functions
require_once '../helpers/api_helper.php';

// Configuration
// IMPORTANT: Ensure this directory exists and is writable (permissions 775 or 777 in development)
$targetDir = "../assets/images/uploads/"; 
$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
$maxFileSize = 5 * 1024 * 1024; // 5MB limit (optional check, but good practice)

// --- 1. Validate Request Method (Must be POST) ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $data = ['success' => false, 'message' => 'Method not allowed.'];
    sendResponse($data, 405); // 405 Method Not Allowed
}

// --- 2. Check for File Upload Errors ---
if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    // If no file was uploaded or an error occurred during upload
    $message = 'No image file uploaded or an upload error occurred.';
    
    // Add specific error detail if available (optional)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_INI_SIZE) {
        $message = 'Uploaded file exceeds the maximum size limit.';
    }
    
    $data = ['success' => false, 'message' => $message];
    sendResponse($data, 400); // 400 Bad Request
}

$fileInfo = $_FILES['image'];

// --- 3. File Type Validation ---
if (!in_array($fileInfo['type'], $allowedMimeTypes)) {
    $data = ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF allowed.'];
    sendResponse($data, 400); // 400 Bad Request
}

// --- 4. File Size Validation (optional but recommended) ---
if ($fileInfo['size'] > $maxFileSize) {
    $data = ['success' => false, 'message' => 'File size exceeds 5MB limit.'];
    sendResponse($data, 400); // 400 Bad Request
}

// --- 5. Generate Unique File Name and Target Path ---
$fileExtension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
// Use uniqid() with more_entropy=true for higher uniqueness
$newFileName = uniqid('img_', true) . "." . $fileExtension;
$targetFile = $targetDir . $newFileName;

// --- 6. Attempt to Move the Uploaded File ---
// The file is moved from the temporary location to the final target path.
if (move_uploaded_file($fileInfo['tmp_name'], $targetFile)) {
    
    // Return the public path used for saving to the database
    $publicPath = 'assets/images/uploads/' . $newFileName;
    $data = ['success' => true, 'message' => 'Image uploaded successfully.', 'url' => $publicPath];
    sendResponse($data, 200); // 200 OK
    
} else {
    // Failed to move file (often due to wrong directory permissions)
    $data = ['success' => false, 'message' => 'Failed to move uploaded file. Check directory permissions or server logs.'];
    sendResponse($data, 500); // 500 Internal Server Error
}