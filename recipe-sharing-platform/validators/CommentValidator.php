<?php
// File: validators/CommentValidator.php

class CommentValidator {

    private array $errors = [];

    /**
     * Validates data for creating a new comment.
     * Requires comment_text, user_id, and recipe_id.
     */
    public function validateCommentData(array $data): array {
        $this->errors = []; 

        // 1. Validate comment_text
        if (empty($data['comment_text'])) {
            $this->errors['comment_text'] = "Comment text is required.";
        } elseif (strlen($data['comment_text']) < 3) {
            $this->errors['comment_text'] = "Comment is too short (min 3 characters).";
        }

        // 2. Validate user_id
        if (empty($data['user_id']) || !filter_var($data['user_id'], FILTER_VALIDATE_INT)) {
            $this->errors['user_id'] = "Invalid user ID for creating the comment.";
        }

        // 3. Validate recipe_id
        if (empty($data['recipe_id']) || !filter_var($data['recipe_id'], FILTER_VALIDATE_INT)) {
            $this->errors['recipe_id'] = "Invalid recipe ID.";
        }

        return $this->errors;
    }

    /**
     * Validates data for updating an existing comment.
     * Requires comment_text, user_id, and comment ID.
     */
    public function validateUpdateData(array $data): array {
        $this->errors = [];

        // 1. Validate comment_text
        if (empty($data['comment_text'])) {
            $this->errors['comment_text'] = "Comment text is required.";
        } elseif (strlen($data['comment_text']) < 3) {
            $this->errors['comment_text'] = "Comment is too short (min 3 characters).";
        }

        // 2. Validate user_id
        if (empty($data['user_id']) || !filter_var($data['user_id'], FILTER_VALIDATE_INT)) {
            $this->errors['user_id'] = "Invalid user ID context for update.";
        }

        // 3. Validate comment ID (id)
        if (empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
            $this->errors['id'] = "Invalid comment ID for update operation.";
        }

        return $this->errors;
    }

    /**
     * Validates data required for deletion (comment ID and user ID context).
     */
    public function validateDelete(array $data): array {
        $this->errors = [];
        
        // Validate comment ID
        if (empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
            $this->errors['id'] = "Invalid comment ID for deletion.";
        }
        
        // Validate user ID
        if (empty($data['user_id']) || !filter_var($data['user_id'], FILTER_VALIDATE_INT)) {
            $this->errors['user_id'] = "User ID context is required for deletion.";
        }
        
        return $this->errors;
    }
}