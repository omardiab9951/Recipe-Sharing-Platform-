<?php

/**
 * File: RecipeController.php
 * Purpose: Recipe controller - handles recipe CRUD operations
 * Author: B2 Team Member
 * Date: December 2025
 * Description: Manages recipe creation, reading, updating, and deletion
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/Recipe.php';
require_once __DIR__ . '/../models/Comment.php';

class RecipeController
{
    private $conn;
    private $recipeModel;
    private $commentModel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->conn = getDBConnection();
        $this->recipeModel = new Recipe($this->conn);
        $this->commentModel = new Comment($this->conn);
    }

    /**
     * Create a new recipe
     * @param int $user_id User ID
     * @param string $title Recipe title
     * @param string $category Recipe category
     * @param string $ingredients Ingredients
     * @param string $instructions Instructions
     * @param array $image Uploaded image file array
     * @return array Result array
     */
    public function createRecipe($user_id, $title, $category, $ingredients, $instructions, $image = null)
    {
        // Validate inputs
        if (empty($title) || strlen($title) < 3) {
            return ['success' => false, 'errors' => ['title' => 'Title must be at least 3 characters']];
        }

        if (empty($category)) {
            return ['success' => false, 'errors' => ['category' => 'Category is required']];
        }

        if (empty($ingredients) || strlen($ingredients) < 10) {
            return ['success' => false, 'errors' => ['ingredients' => 'Ingredients must be at least 10 characters']];
        }

        if (empty($instructions) || strlen($instructions) < 20) {
            return ['success' => false, 'errors' => ['instructions' => 'Instructions must be at least 20 characters']];
        }

        // Handle image upload
        $image_name = 'default-recipe.jpg';
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $upload_result = $this->handleImageUpload($image);
            if ($upload_result['success']) {
                $image_name = $upload_result['filename'];
            } else {
                return ['success' => false, 'errors' => ['image' => $upload_result['error']]];
            }
        }

        // Create recipe
        $recipe_id = $this->recipeModel->createRecipe($user_id, $title, $category, $ingredients, $instructions, $image_name);

        if ($recipe_id) {
            return [
                'success' => true,
                'message' => 'Recipe created successfully!',
                'recipe_id' => $recipe_id
            ];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to create recipe']];
        }
    }

    /**
     * Update a recipe
     * @param int $recipe_id Recipe ID
     * @param int $user_id User ID
     * @param string $title Recipe title
     * @param string $category Recipe category
     * @param string $ingredients Ingredients
     * @param string $instructions Instructions
     * @param array $image Uploaded image file array
     * @return array Result array
     */
    public function updateRecipe($recipe_id, $user_id, $title, $category, $ingredients, $instructions, $image = null)
    {
        // Check ownership
        if (!$this->recipeModel->isOwner($recipe_id, $user_id)) {
            return ['success' => false, 'errors' => ['general' => 'You do not have permission to edit this recipe']];
        }

        // Validate inputs
        if (empty($title) || strlen($title) < 3) {
            return ['success' => false, 'errors' => ['title' => 'Title must be at least 3 characters']];
        }

        if (empty($category)) {
            return ['success' => false, 'errors' => ['category' => 'Category is required']];
        }

        if (empty($ingredients) || strlen($ingredients) < 10) {
            return ['success' => false, 'errors' => ['ingredients' => 'Ingredients must be at least 10 characters']];
        }

        if (empty($instructions) || strlen($instructions) < 20) {
            return ['success' => false, 'errors' => ['instructions' => 'Instructions must be at least 20 characters']];
        }

        // Handle image upload
        $image_name = null;
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $upload_result = $this->handleImageUpload($image);
            if ($upload_result['success']) {
                $image_name = $upload_result['filename'];
            } else {
                return ['success' => false, 'errors' => ['image' => $upload_result['error']]];
            }
        }

        // Update recipe
        if ($this->recipeModel->updateRecipe($recipe_id, $title, $category, $ingredients, $instructions, $image_name)) {
            return [
                'success' => true,
                'message' => 'Recipe updated successfully!'
            ];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to update recipe']];
        }
    }

    /**
     * Delete a recipe
     * @param int $recipe_id Recipe ID
     * @param int $user_id User ID
     * @return array Result array
     */
    public function deleteRecipe($recipe_id, $user_id)
    {
        // Check ownership
        if (!$this->recipeModel->isOwner($recipe_id, $user_id)) {
            return ['success' => false, 'errors' => ['general' => 'You do not have permission to delete this recipe']];
        }

        // Delete recipe
        if ($this->recipeModel->deleteRecipe($recipe_id)) {
            return [
                'success' => true,
                'message' => 'Recipe deleted successfully!'
            ];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to delete recipe']];
        }
    }

    /**
     * Handle image upload
     * @param array $image Uploaded file array
     * @return array Result with filename or error
     */
    private function handleImageUpload($image)
    {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        // Check file type
        if (!in_array($image['type'], $allowed_types)) {
            return ['success' => false, 'error' => 'Only JPG, PNG, and GIF images are allowed'];
        }

        // Check file size
        if ($image['size'] > $max_size) {
            return ['success' => false, 'error' => 'Image size must be less than 5MB'];
        }

        // Generate unique filename
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $filename = uniqid('recipe_') . '.' . $extension;
        $upload_path = __DIR__ . '/../assets/uploads/recipes/';

        // Create directory if it doesn't exist
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        // Move uploaded file
        if (move_uploaded_file($image['tmp_name'], $upload_path . $filename)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'error' => 'Failed to upload image'];
        }
    }

    /**
     * Add comment to recipe
     * @param int $recipe_id Recipe ID
     * @param int $user_id User ID
     * @param string $comment_text Comment text
     * @return array Result array
     */
    public function addComment($recipe_id, $user_id, $comment_text)
    {
        // Validate comment
        if (empty($comment_text) || strlen($comment_text) < 3) {
            return ['success' => false, 'errors' => ['comment' => 'Comment must be at least 3 characters']];
        }

        if (strlen($comment_text) > 1000) {
            return ['success' => false, 'errors' => ['comment' => 'Comment is too long']];
        }

        // Create comment
        $comment_id = $this->commentModel->createComment($recipe_id, $user_id, $comment_text);

        if ($comment_id) {
            return [
                'success' => true,
                'message' => 'Comment added successfully!',
                'comment_id' => $comment_id
            ];
        } else {
            return ['success' => false, 'errors' => ['general' => 'Failed to add comment']];
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
