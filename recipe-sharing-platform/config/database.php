<?php
// File: config/database.php - Manages the database connection

// Database Connection Constants
define('DB_HOST', 'localhost');
define('DB_NAME', 'recipe_db'); // Database name
define('DB_USER', 'root');      // Default username for MySQL (e.g., in XAMPP)
define('DB_PASS', '');          // Default password (usually empty in XAMPP/WAMP)

class Database {
    private $host = DB_HOST;
    private $dbName = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    public $conn;

    /**
     * Retrieves the active database connection.
     * @return PDO|null The PDO connection object or NULL on failure.
     */
    public function getConnection(): ?PDO {
        $this->conn = null;

        try {
            // Create a new PDO connection instance
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbName, 
                $this->username, 
                $this->password
            );

            // Set character encoding to UTF-8 for proper Arabic/Unicode support
            $this->conn->exec("set names utf8"); 
            
            // Set error mode to throw exceptions on SQL errors
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $exception) {
            // Log or display the connection error
            error_log("Database Connection Error: " . $exception->getMessage());
            // Friendly output message (optional in production)
            echo "Database Connection Error: " . $exception->getMessage();
            // In a production API, you might return a generic 500 error response instead of echoing the error.
        }

        return $this->conn;
    }
}

// --- Temporary Test Code Removed ---
// The final file should only contain the class definition.
// -----------------------------------