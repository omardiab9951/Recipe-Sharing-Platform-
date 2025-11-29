<?php
// File: models/Search.php - Handles recipe search and filtering logic

require_once dirname(__DIR__) . '/config/database.php';

class Search {
    private $conn;
    private $tableName = "recipes";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Executes dynamic search and filtering based on optional parameters.
     * Uses PDO Prepared Statements with execute($params) for safe execution.
     *
     * @param string $searchTerm Optional keyword to search in title/ingredients.
     * @param string|null $category Optional category name or ID for filtering.
     * @return PDOStatement|false The executed statement object or false on failure.
     */
    public function findRecipes(string $searchTerm = '', $category = null) {

        // Start base query, use WHERE 1=1 for easy condition appending
        $query = "SELECT * FROM " . $this->tableName . " WHERE 1=1";
        $params = [];

        // 1. Add search condition for keyword (searches in title OR ingredients)
        if (!empty($searchTerm)) {
            $query .= " AND (title LIKE ? OR ingredients LIKE ?)";
            // Add search term twice, wrapped in wildcards
            $params[] = "%" . $searchTerm . "%";
            $params[] = "%" . $searchTerm . "%";
        }

        // 2. Add filtering condition by category
        if (!empty($category)) {
            $query .= " AND category = ?";
            $params[] = $category;
        }

        // 3. Add ordering
        $query .= " ORDER BY created_at DESC";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Execute the statement using the dynamically built $params array
        if ($stmt->execute($params)) {
            return $stmt;
        }
        
        return false;
    }
}