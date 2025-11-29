<?php
// File: controllers/RecipeController.php - Handles recipe CRUD business logic

// Required files
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/models/Recipe.php';
require_once dirname(__DIR__) . '/validators/RecipeValidator.php';

class RecipeController {
    private $db;
    private $recipe;
    private $validator;

    // Constructor
    public function __construct() {
        // Initialize database connection
        $database = new Database();
        $this->db = $database->getConnection();

        // Initialize Model and Validator
        $this->recipe = new Recipe($this->db);
        $this->validator = new RecipeValidator();
    }

    // A. CREATE: Function to create a new recipe
    public function createRecipe(array $data): array {
        // 1. Validate data first
        $errors = $this->validator->validateRecipeData($data);
        if (!empty($errors)) {
            http_response_code(400); // Bad Request
            return ['success' => false, 'errors' => $errors];
        }

        // 2. Set Model properties from the submitted data
        $this->recipe->title        = $data['title'];
        $this->recipe->category_id  = $data['category_id'];
        $this->recipe->ingredients  = $data['ingredients'];
        $this->recipe->instructions = $data['instructions'];
        $this->recipe->image_url    = $data['image_url'] ?? null;
        $this->recipe->user_id      = $data['user_id'];

        // 3. Attempt creation via Model
        if ($this->recipe->create()) {
            http_response_code(201); // Created
            return ['success' => true, 'message' => 'Recipe created successfully.', 'id' => $this->recipe->id];
        } else {
            http_response_code(503); // Service Unavailable (Database error)
            return ['success' => false, 'message' => 'Unable to create recipe. Database error.'];
        }
    }

    // B. READ ALL: Function to fetch all recipes
    public function getAllRecipes(): array {
        $stmt = $this->recipe->readAll();
        $num = $stmt->rowCount();
        $recipesArr = [];

        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                // Extracting row data for easier access
                extract($row); 
                
                // Map data to output array
                $recipeItem = [
                    "id"            => $id,
                    "title"         => $title,
                    "category_id"   => $category_id, 
                    "category_name" => $category_name, 
                    "ingredients"   => $ingredients,
                    "instructions"  => $instructions,
                    "image_url"     => $image_url,
                    "user_id"       => $user_id,
                    "user_name"     => $user_name,
                    "created_at"    => $created_at
                ];
                array_push($recipesArr, $recipeItem);
            }
            http_response_code(200); // OK
            return ['success' => true, 'data' => $recipesArr];
        } else {
            http_response_code(404); // Not Found
            return ['success' => false, 'message' => 'No recipes found.'];
        }
    }

    // C. READ ONE: Function to fetch a single recipe
    public function getOneRecipe(int $id): array {
        $this->recipe->id = $id;

        if ($this->recipe->readOne()) {
            // Data successfully fetched, map to output array
            $recipeItem = [
                "id"            => $this->recipe->id,
                "title"         => $this->recipe->title,
                "category_id"   => $this->recipe->category_id, 
                "category_name" => $this->recipe->category_name, 
                "ingredients"   => $this->recipe->ingredients,
                "instructions"  => $this->recipe->instructions,
                "image_url"     => $this->recipe->image_url,
                "user_id"       => $this->recipe->user_id,
                "user_name"     => $this->recipe->user_name,
                "created_at"    => $this->recipe->created_at
            ];
            http_response_code(200);
            return ['success' => true, 'data' => $recipeItem];
        } else {
            http_response_code(404);
            return ['success' => false, 'message' => 'Recipe not found.'];
        }
    }

    // D. UPDATE: Function to update an existing recipe
    public function updateRecipe(array $data): array {
        // 1. Basic validation for essential IDs
        if (empty($data['id']) || empty($data['user_id'])) {
             http_response_code(400); 
             return ['success' => false, 'message' => 'Missing Recipe ID or User ID.'];
        }
        
        // 2. Validate content fields
        $errors = $this->validator->validateRecipeData($data);
        if (!empty($errors)) {
            http_response_code(400);
            return ['success' => false, 'errors' => $errors];
        }

        // 3. Set Model properties
        $this->recipe->id           = $data['id'];
        $this->recipe->user_id      = $data['user_id'];
        $this->recipe->title        = $data['title'];
        $this->recipe->category_id  = $data['category_id']; 
        $this->recipe->ingredients  = $data['ingredients'];
        $this->recipe->instructions = $data['instructions'];
        $this->recipe->image_url    = $data['image_url'] ?? null;

        // 4. Attempt update (which includes ownership check)
        if ($this->recipe->update()) {
            http_response_code(200); // OK
            return ['success' => true, 'message' => 'Recipe updated successfully.'];
        } else {
            // Fails if not found OR if user_id doesn't match owner (Unauthorized)
            http_response_code(401); 
            return ['success' => false, 'message' => 'Unauthorized or Recipe not found.'];
        }
    }

    // E. DELETE: Function to delete a recipe
    public function deleteRecipe(array $data): array {
        // 1. Basic validation for essential IDs
        if (empty($data['id']) || empty($data['user_id'])) {
             http_response_code(400); 
             return ['success' => false, 'message' => 'Missing Recipe ID or User ID.'];
        }

        $this->recipe->id = $data['id'];
        $this->recipe->user_id = $data['user_id'];

        // 2. Attempt deletion (which includes ownership check)
        if ($this->recipe->delete()) {
            http_response_code(200); // OK
            return ['success' => true, 'message' => 'Recipe deleted successfully.'];
        } else {
            // Fails if not found OR if user_id doesn't match owner (Unauthorized)
            http_response_code(401); 
            return ['success' => false, 'message' => 'Unauthorized or Recipe not found.'];
        }
    }
}

// --- Temporary Test Code Removed ---
// echo "✅ RecipeController loaded successfully.";
// -----------------------------------