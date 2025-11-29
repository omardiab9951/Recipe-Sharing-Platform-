<?php
// File: models/Comment.php - Handles comment CRUD operations

require_once dirname(__DIR__) . '/config/database.php';

class Comment {
    private $conn;
    private $tableName = "comments";

    // Object properties
    public $id;
    public $recipe_id;
    public $user_id;
    public $content; 
    public $created_at;
    public $user_name; // Joined property

    public function __construct($db){
        $this->conn = $db;
    }

    /**
     * Creates a new comment record (CREATE).
     */
    public function create(): bool {
        $query = "INSERT INTO " . $this->tableName . "
                  SET recipe_id=:recipe_id, user_id=:user_id, content=:content";

        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->recipe_id = htmlspecialchars(strip_tags($this->recipe_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // Bind parameters
        $stmt->bindParam(":recipe_id", $this->recipe_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":content", $this->content); 

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Retrieves all comments for a specific recipe ID (READ).
     * Includes the author's username via JOIN.
     */
    public function readByRecipeId() {
        $query = "SELECT c.*, u.username as user_name
                  FROM " . $this->tableName . " c
                  LEFT JOIN users u ON c.user_id = u.id
                  WHERE c.recipe_id = ? 
                  ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->recipe_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Updates an existing comment (UPDATE).
     * Requires the comment ID and the matching user ID (ownership check).
     */
    public function update(): bool {
        $query = "UPDATE " . $this->tableName . "
                  SET content = :content
                  WHERE id = :id AND user_id = :user_id"; 

        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // Bind parameters
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);

        if ($stmt->execute()){
            // Check if any row was affected (ensures owner performed update)
            return $stmt->rowCount() > 0;
        }
        return false;
    }

    /**
     * Deletes a comment record (DELETE).
     * Requires the comment ID and the matching user ID (ownership check).
     */
    public function delete(): bool {
        $query = "DELETE FROM " . $this->tableName . " 
                  WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // Bind parameters
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);

        if ($stmt->execute()) {
            // Check if any row was affected (ensures owner performed deletion)
            return $stmt->rowCount() > 0;
        }
        return false;
    }
}