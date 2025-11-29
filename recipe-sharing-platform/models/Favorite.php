<?php
// File: models/Favorite.php - Handles user favorite recipe interactions

require_once dirname(__DIR__) . '/config/database.php';

class Favorite {
    private $conn;
    private $tableName = "favorites"; 

    public $user_id;
    public $recipe_id;

    public function __construct($db){
        $this->conn = $db;
    }

    /**
     * A. Adds a recipe to the user's favorites (CREATE).
     * Automatically checks for duplication before insertion.
     */
    public function addFavorite(): bool {
        // Prevent duplicate entries
        if ($this->isFavorite()) {
            return true; 
        }

        $query = "INSERT INTO " . $this->tableName . "
                  SET user_id=:user_id, recipe_id=:recipe_id";

        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":recipe_id", $this->recipe_id);

        return $stmt->execute();
    }

    /**
     * B. Removes a recipe from the user's favorites (DELETE).
     */
    public function removeFavorite(): bool {

        $query = "DELETE FROM " . $this->tableName . " 
                  WHERE user_id = ? AND recipe_id = ?";

        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->recipe_id); 

        return $stmt->execute();
    }

    /**
     * C. Retrieves the list of favorite recipes for a specific user (READ).
     * Includes full recipe details and author name via JOINS.
     */
    public function getFavoritesByUserId() {
        $query = "SELECT r.*, u.username as user_name 
                  FROM recipes r
                  JOIN " . $this->tableName . " f ON r.id = f.recipe_id
                  JOIN users u ON r.user_id = u.id 
                  WHERE f.user_id = ?
                  ORDER BY r.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Helper function to check if a recipe is already marked as favorite by the user.
     */
    public function isFavorite(): bool {
        $query = "SELECT 1 FROM " . $this->tableName . " 
                  WHERE user_id = ? AND recipe_id = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->recipe_id); 
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}