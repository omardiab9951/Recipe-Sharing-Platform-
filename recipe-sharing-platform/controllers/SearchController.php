<?php
// File: controllers/SearchController.php - Handles search and filtering logic

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/models/Search.php';

class SearchController {
    private $db;
    private $searchModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->searchModel = new Search($this->db);
    }

    /**
     * Executes the search and filtering logic.
     * @param string $searchTerm The search keyword.
     * @param int|null $categoryId The Category ID for filtering.
     * @return array The search result array.
     */
    public function searchRecipes(string $searchTerm = '', $categoryId = null): array {

        // Execute the model function to get the PDOStatement
        $stmt = $this->searchModel->findRecipes($searchTerm, $categoryId);
        
        // Handle database error if findRecipes returns false
        if ($stmt === false) {
            http_response_code(500); // Internal Server Error
            return ['success' => false, 'message' => 'Database query failed.'];
        }

        $num = $stmt->rowCount();
        $recipesArr = [];

        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                // Note: The 'category' key might be missing if the Search model only returns raw recipe data.
                // It is generally better practice for the Model to handle joins and return necessary names.
                // Assuming 'category' might contain the name or ID depending on the Model's logic.
                $categoryValue = $row['category'] ?? null; 

                // Map essential data to the output array
                $recipeItem = [
                    "id"         => $row['id'],
                    "title"      => $row['title'],
                    "category"   => $categoryValue, 
                    "user_id"    => $row['user_id'],
                    "created_at" => $row['created_at']
                    // Only essential fields are returned for a list view
                ];
                array_push($recipesArr, $recipeItem);
            }
            http_response_code(200); // OK
            return ['success' => true, 'data' => $recipesArr, 'count' => $num];
        } else {
            http_response_code(404); // Not Found
            return ['success' => false, 'message' => 'No recipes match your search criteria.'];
        }
    }
}