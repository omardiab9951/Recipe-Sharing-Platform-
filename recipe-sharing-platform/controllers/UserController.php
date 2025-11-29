<?php
// File: controllers/UserController.php - Handles user-related business logic

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/models/User.php';

class UserController {
    private $db;
    private $userModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
    }

    /**
     * Retrieves the details of a single user by ID.
     * @param int $userId The ID of the user to retrieve.
     * @return array Response array containing success status and data/message.
     */
    public function readUser(int $userId): array {
        
        // Input validation (basic check, more complex validation should be in a Validator class)
        if (empty($userId) || !filter_var($userId, FILTER_VALIDATE_INT)) {
            http_response_code(400); // Bad Request
            return ['success' => false, 'message' => 'Invalid User ID.'];
        }

        $this->userModel->id = $userId;

        if ($this->userModel->readOne()) {
            // Data to be returned (excluding sensitive info like password)
            $userInfo = [
                "id"         => $this->userModel->id,
                "username"   => $this->userModel->username,
                "email"      => $this->userModel->email,
                "created_at" => $this->userModel->createdAt // Note: property name changed to follow standard
            ];
            
            http_response_code(200); // OK
            return ['success' => true, 'data' => $userInfo];
        }

        http_response_code(404); // Not Found
        return ['success' => false, 'message' => 'User not found.'];
    }
    
    // Additional methods for register, login, update, and delete will be added by B1
}