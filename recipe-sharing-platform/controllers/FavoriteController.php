<?php
// File: controllers/FavoriteController.php - Handles user favorites logic (Add, Remove, List)

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/models/Favorite.php';

class FavoriteController {
    private $db;
    private $favoriteModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->favoriteModel = new Favorite($this->db);
    }

    /**
     * Helper function to validate user_id and recipe_id existence and type.
     * @param array $data Input data array.
     * @return array Array of validation error messages.
     */
    private function validateFavoriteData(array $data): array {
        $errors = [];
        // Check for User ID
        if (empty($data['user_id']) || !filter_var($data['user_id'], FILTER_VALIDATE_INT)) {
            $errors[] = 'Invalid or missing user_id.';
        }
        // Check for Recipe ID
        if (empty($data['recipe_id']) || !filter_var($data['recipe_id'], FILTER_VALIDATE_INT)) {
            $errors[] = 'Invalid or missing recipe_id.';
        }
        return $errors;
    }

    // A. Adds a recipe to the user's favorites
    public function addFavorite(array $data): array {
        $errors = $this->validateFavoriteData($data);
        if (!empty($errors)) {
            http_response_code(400); // Bad Request
            return ['success' => false, 'errors' => $errors];
        }

        $this->favoriteModel->user_id = $data['user_id'];
        $this->favoriteModel->recipe_id = $data['recipe_id'];

        if ($this->favoriteModel->addFavorite()) {
            http_response_code(201); // Created
            return ['success' => true, 'message' => 'Recipe added to favorites.'];
        }
        
        http_response_code(503); // Service Unavailable (Database error)
        return ['success' => false, 'message' => 'Unable to add favorite.'];
    }

    // B. Removes a recipe from the user's favorites
    public function removeFavorite(array $data): array {
        $errors = $this->validateFavoriteData($data);
        if (!empty($errors)) {
            http_response_code(400); // Bad Request
            return ['success' => false, 'errors' => $errors];
        }

        $this->favoriteModel->user_id = $data['user_id'];
        $this->favoriteModel->recipe_id = $data['recipe_id'];

        if ($this->favoriteModel->removeFavorite()) {
            http_response_code(200); // OK
            return ['success' => true, 'message' => 'Recipe removed from favorites.'];
        }
        
        // This usually means the favorite entry wasn't found or a database issue occurred
        http_response_code(404); // Not Found 
        return ['success' => false, 'message' => 'Favorite entry not found or unable to remove.'];
    }

    // C. Retrieves the list of favorites for a specific user
    public function getFavorites(int $userId): array {
        // Validation check for ID passed via URL/parameter
        if (empty($userId) || !filter_var($userId, FILTER_VALIDATE_INT)) {
            http_response_code(400); // Bad Request
            return ['success' => false, 'message' => 'Invalid User ID.'];
        }

        $this->favoriteModel->user_id = $userId;
        $stmt = $this->favoriteModel->getFavoritesByUserId();
        
        // Handle database error if getFavoritesByUserId returns false (Though Model usually returns PDOStatement)
        if ($stmt === false) {
            http_response_code(500); 
            return ['success' => false, 'message' => 'Database error during fetch.'];
        }
        
        $num = $stmt->rowCount();
        $favoritesArr = [];

        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Mapping recipe data with joined user/category info
                $favoritesArr[] = [
                    "id"            => $row['id'],
                    "title"         => $row['title'],
                    "category_id"   => $row['category_id'] ?? null, 
                    "category_name" => $row['category_name'] ?? null,
                    "image_url"     => $row['image_url'],
                    "user_id"       => $row['user_id'],
                    "user_name"     => $row['user_name'],
                    "created_at"    => $row['created_at']
                ];
            }
            http_response_code(200); // OK
            return ['success' => true, 'data' => $favoritesArr, 'count' => $num];
        }
        
        http_response_code(404); // Not Found
        return ['success' => false, 'message' => 'No favorites found for this user.'];
    }
}