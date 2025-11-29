<?php
// File: controllers/CommentController.php - Handles comment CRUD business logic

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/models/Comment.php';
require_once dirname(__DIR__) . '/validators/CommentValidator.php';
// api_helper is usually included in the main API file, but included here for completeness
require_once dirname(__DIR__) . '/helpers/api_helper.php';

class CommentController {
    private $db;
    private $comment;
    private $validator;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->comment = new Comment($this->db);
        $this->validator = new CommentValidator();
    }

    // A. CREATE: Creates a new comment
    public function createComment(array $data): array {
        // 1. Validate data
        $errors = $this->validator->validateCommentData($data);
        if (!empty($errors)) {
            http_response_code(400); // Bad Request
            return ['success' => false, 'errors' => $errors];
        }

        // 2. Set Model properties (mapping input 'comment_text' to model's 'content')
        $this->comment->recipe_id = $data['recipe_id'];
        $this->comment->user_id   = $data['user_id'];
        $this->comment->content   = $data['comment_text']; 

        // 3. Attempt creation
        if ($this->comment->create()) {
            http_response_code(201); // Created
            return ['success' => true, 'message' => 'Comment added successfully.', 'id' => $this->comment->id];
        } else {
            http_response_code(503); // Service Unavailable (Database error)
            return ['success' => false, 'message' => 'Unable to add comment.'];
        }
    }

    // B. READ: Retrieves comments for a specific recipe
    public function getCommentsByRecipe(int $recipeId): array {
        $this->comment->recipe_id = $recipeId;
        $stmt = $this->comment->readByRecipeId();
        
        // Handle database error if readByRecipeId returns false (Though Model usually returns PDOStatement)
        if ($stmt === false) {
            http_response_code(500); 
            return ['success' => false, 'message' => 'Database error during fetch.'];
        }
        
        $num = $stmt->rowCount();
        $commentsArr = [];

        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Mapping model's 'content' back to controller's 'comment_text' output
                $commentsArr[] = [
                    "id"           => $row['id'],
                    "user_id"      => $row['user_id'],
                    "user_name"    => $row['user_name'] ?? 'Anonymous',
                    "comment_text" => $row['content'], 
                    "created_at"   => $row['created_at']
                ];
            }
            http_response_code(200); // OK
            return ['success' => true, 'data' => $commentsArr, 'count' => $num];
        } else {
            http_response_code(404); // Not Found
            return ['success' => false, 'message' => 'No comments found for this recipe.'];
        }
    }

    // C. DELETE: Deletes a comment
    public function deleteComment(array $data): array {
        // 1. Validate essential IDs
        $errors = $this->validator->validateDelete($data);
        if (!empty($errors)) {
            http_response_code(400); // Bad Request
            return ['success' => false, 'errors' => $errors];
        }

        $this->comment->id = $data['id'];
        $this->comment->user_id = $data['user_id'];

        // 2. Attempt deletion (includes ownership check)
        if ($this->comment->delete()) {
            http_response_code(200); // OK
            return ['success' => true, 'message' => 'Comment deleted successfully.'];
        } else {
            // Fails if not found OR if user_id doesn't match owner (Unauthorized)
            http_response_code(401); 
            return ['success' => false, 'message' => 'Unauthorized or Comment not found.'];
        }
    }

    // D. UPDATE: Updates an existing comment
    public function updateComment(array $data): array {
        
        // 1. Validate essential IDs and the new content
        $errors = $this->validator->validateUpdateData($data); 
        if (!empty($errors)) {
            http_response_code(400); // Bad Request
            return ['success' => false, 'errors' => $errors];
        }

        // 2. Set Model properties (mapping input 'comment_text' to model's 'content')
        $this->comment->id = $data['id'];
        $this->comment->user_id = $data['user_id'];
        $this->comment->content = $data['comment_text']; 

        // 3. Attempt update (includes ownership check)
        if ($this->comment->update()) {
            http_response_code(200); // OK
            return ['success' => true, 'message' => 'Comment updated successfully.'];
        } else {
            // Fails if not found OR if user_id doesn't match owner (Unauthorized)
            http_response_code(401); 
            return ['success' => false, 'message' => 'Unauthorized or Comment not found.'];
        }
    }
}