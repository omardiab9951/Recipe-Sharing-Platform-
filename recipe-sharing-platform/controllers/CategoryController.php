<?php
// File: controllers/CategoryController.php - Handles category CRUD business logic

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/models/Category.php';

class CategoryController {
    private $db;
    private $category;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->category = new Category($this->db);
    }

    // A. READ ALL: Retrieves all categories
    public function getAllCategories(): array {
        $stmt = $this->category->readAll();
        $num = $stmt->rowCount();
        $categoriesArr = [];

        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categoriesArr[] = [
                    "id"   => $row['id'],
                    "name" => $row['name']
                ];
            }
            http_response_code(200); // OK
            return ['success' => true, 'data' => $categoriesArr];
        } else {
            http_response_code(404); // Not Found
            return ['success' => false, 'message' => 'No categories found.'];
        }
    }

    // B. CREATE: Creates a new category
    public function createCategory(array $data): array {
        // Validation
        if (empty($data['name']) || strlen($data['name']) < 2) {
            http_response_code(400); // Bad Request
            return ['success' => false, 'message' => 'Category name is required and must be at least 2 characters.'];
        }

        $this->category->name = $data['name'];

        if ($this->category->create()) {
            http_response_code(201); // Created
            return ['success' => true, 'message' => 'Category created successfully.', 'id' => $this->category->id];
        } else {
            http_response_code(503); // Service Unavailable
            return ['success' => false, 'message' => 'Unable to create category. Database error.'];
        }
    }

    // C. UPDATE: Updates an existing category
    public function updateCategory(array $data): array {
        // Validation
        if (empty($data['id']) || !is_numeric($data['id'])) {
            http_response_code(400); // Bad Request
            return ['success' => false, 'message' => 'Category ID is required for update.'];
        }
        if (empty($data['name']) || strlen($data['name']) < 2) {
            http_response_code(400); // Bad Request
            return ['success' => false, 'message' => 'New category name is required.'];
        }

        $this->category->id = $data['id'];
        $this->category->name = $data['name'];

        if ($this->category->update()) {
            http_response_code(200); // OK
            return ['success' => true, 'message' => 'Category updated successfully.'];
        } else {
            // Fails if category ID is not found (rowCount() == 0)
            http_response_code(404); // Not Found
            return ['success' => false, 'message' => 'Unable to update category. Category may not exist.'];
        }
    }

    // D. DELETE: Deletes a category
    public function deleteCategory(array $data): array {
        // Validation
        if (empty($data['id']) || !is_numeric($data['id'])) {
            http_response_code(400); // Bad Request
            return ['success' => false, 'message' => 'Category ID is required for deletion.'];
        }

        $this->category->id = $data['id'];

        if ($this->category->delete()) {
            http_response_code(200); // OK
            return ['success' => true, 'message' => 'Category deleted successfully.'];
        } else {
            // The Model returns false if a Foreign Key constraint prevents deletion
            http_response_code(409); // Conflict (Often used for integrity constraints)
            return ['success' => false, 'message' => 'Unable to delete category. Check if recipes are still linked to it.'];
        }
    }
}