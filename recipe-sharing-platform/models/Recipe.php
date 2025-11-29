<?php
// File: models/Recipe.php - The core data model for Recipe CRUD operations

// Include database connection file
require_once dirname(__DIR__) . '/config/database.php';

class Recipe {
    // Database connection and table name
    private $conn;
    private $tableName = "recipes";

    // Object properties (match the 'recipes' table columns)
    public $id;
    public $user_id;
    public $title;
    public $category_id; 
    public $ingredients;
    public $instructions;
    public $image_url;
    public $created_at;
    public $user_name;      // Joined data: Recipe author's username
    public $category_name;  // Joined data: Category name

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // A. CREATE: Function to save a new recipe
    public function create(): bool {
        $query = "INSERT INTO " . $this->tableName . "
                  SET
                      title=:title, 
                      category_id=:category_id, 
                      ingredients=:ingredients, 
                      instructions=:instructions, 
                      image_url=:image_url,
                      user_id=:user_id";

        $stmt = $this->conn->prepare($query);

        // Sanitize data for security
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->ingredients = htmlspecialchars(strip_tags($this->ingredients));
        $this->instructions = htmlspecialchars(strip_tags($this->instructions));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        
        // Bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":ingredients", $this->ingredients);
        $stmt->bindParam(":instructions", $this->instructions);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":user_id", $this->user_id);
        
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // B. READ ALL: Function to read all recipes (with joins)
    public function readAll() {
        // Joins with users and categories tables
        $query = "SELECT r.*, u.username as user_name, c.name as category_name
                  FROM " . $this->tableName . " r 
                  LEFT JOIN users u ON r.user_id = u.id 
                  LEFT JOIN categories c ON r.category_id = c.id
                  ORDER BY r.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    
    // C. READ ONE: Function to read a single recipe by ID (with joins)
    public function readOne(): bool {
        $query = "SELECT r.*, u.username as user_name, c.name as category_name
                  FROM " . $this->tableName . " r 
                  LEFT JOIN users u ON r.user_id = u.id 
                  LEFT JOIN categories c ON r.category_id = c.id
                  WHERE r.id = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->title = $row['title'];
            $this->category_id = $row['category_id'];
            $this->ingredients = $row['ingredients'];
            $this->instructions = $row['instructions'];
            $this->image_url = $row['image_url'];
            $this->user_id = $row['user_id'];
            $this->created_at = $row['created_at'];
            $this->user_name = $row['user_name'];
            $this->category_name = $row['category_name'];
            return true;
        }
        return false;
    }
    
    // D. UPDATE: Function to update an existing recipe
    public function update(): bool {
        $query = "UPDATE " . $this->tableName . "
                  SET 
                      title=:title, 
                      category_id=:category_id, 
                      ingredients=:ingredients, 
                      instructions=:instructions, 
                      image_url=:image_url
                  WHERE id = :id AND user_id = :user_id"; // Important: Owner check

        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->ingredients = htmlspecialchars(strip_tags($this->ingredients));
        $this->instructions = htmlspecialchars(strip_tags($this->instructions));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));

        // Bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":ingredients", $this->ingredients);
        $stmt->bindParam(":instructions", $this->instructions);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":user_id", $this->user_id); // Used for ownership check

        if ($stmt->execute()) {
            // Check if any row was affected (ensures the owner performed the update)
            return $stmt->rowCount() > 0;
        }
        return false;
    }

    // E. DELETE: Function to delete a recipe
    public function delete(): bool {
        // Important: Deletes only if recipe ID matches the user ID (ownership check)
        $query = "DELETE FROM " . $this->tableName . " WHERE id = ? AND user_id = ?";

        $stmt = $this->conn->prepare($query);

        // Bind values (Recipe ID and Owner User ID)
        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->user_id); 

        if ($stmt->execute()) {
            // Check if any row was affected (ensures the owner performed the deletion)
            return $stmt->rowCount() > 0;
        }
        return false;
    }
}