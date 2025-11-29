<?php
// File: models/User.php - Handles user data interactions with the database

class User {
    private $conn;
    private $tableName = "users";

    // User properties (Public for easy data binding/access)
    public $id;
    public $username;
    public $email;
    public $password;
    public $createdAt;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Retrieves a single user record by their ID.
     * @return bool True if the user is found, false otherwise.
     */
    public function readOne(): bool {
        // SQL query to fetch user data
        $query = "SELECT id, username, email, created_at
                  FROM " . $this->tableName . "
                  WHERE id = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        // Bind the ID parameter
        $stmt->bindParam(1, $this->id);

        // Execute the query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Populate object properties
        if ($row) {
            $this->username   = $row['username'];
            $this->email      = $row['email'];
            $this->createdAt  = $row['created_at'];
            return true;
        }

        return false;
    }
    
    // Additional methods for registration (create) and login (readByEmail) will be added later by B1
}