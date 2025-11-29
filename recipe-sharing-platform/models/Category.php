<?php
// File: models/Category.php - Handles category CRUD operations

class Category {
    private $conn;
    private $tableName = "categories";

    // Object properties
    public $id;
    public $name;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Reads all category records (READ ALL).
     * @return PDOStatement
     */
    public function readAll() {
        $query = "SELECT id, name FROM " . $this->tableName . " ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Creates a new category record (CREATE).
     * @return bool True on success, false on failure.
     */
    public function create(): bool {
        $query = "INSERT INTO " . $this->tableName . " SET name=:name";
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->name = htmlspecialchars(strip_tags($this->name));

        // Bind parameter
        $stmt->bindParam(":name", $this->name);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Updates an existing category record (UPDATE).
     * @return bool True if updated successfully (and a row was affected), false otherwise.
     */
    public function update(): bool {
        $query = "UPDATE " . $this->tableName . " SET name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameters
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Deletes a category record (DELETE).
     * Includes error handling for foreign key constraints (disallowing deletion if recipes exist in the category).
     * @return bool True if deleted successfully, false if not found or if constrained by a foreign key.
     * @throws PDOException If an unknown database error occurs.
     */
    public function delete(): bool {
        $query = "DELETE FROM " . $this->tableName . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameter
        $stmt->bindParam(':id', $this->id);

        try {
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            // Check for integrity constraint violation (e.g., Foreign Key constraint 23000)
            if ($e->getCode() == '23000') {
                // Cannot delete because recipes are still linked to this category
                return false; 
            }
            // Re-throw if it's a different, unexpected error
            throw $e;
        }
        return false;
    }
}