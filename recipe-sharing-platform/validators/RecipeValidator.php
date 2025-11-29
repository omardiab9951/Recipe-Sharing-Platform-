<?php
// File: validators/RecipeValidator.php

class RecipeValidator {

    // Array to hold validation errors
    private $errors = [];

    /**
     * Main function to validate recipe creation/update data.
     * @param array $data The data array (e.g., from POST or JSON body).
     * @return array Returns an array of errors (empty if validation passes).
     */
    public function validateRecipeData(array $data): array {
        
        // 1. Validate Title
        if (empty($data['title'])) {
            $this->errors['title'] = "Recipe title is required.";
        } elseif (strlen($data['title']) < 5) {
            $this->errors['title'] = "The title must be at least 5 characters long.";
        }

        // 2. Validate Category ID (Must be present and a valid integer)
        if (empty($data['category_id']) || !filter_var($data['category_id'], FILTER_VALIDATE_INT)) {
            $this->errors['category_id'] = "Recipe category ID is missing or invalid.";
        }

        // 3. Validate Ingredients
        if (empty($data['ingredients'])) {
            $this->errors['ingredients'] = "The list of ingredients is required.";
        }

        // 4. Validate Instructions
        if (empty($data['instructions'])) {
            $this->errors['instructions'] = "Preparation steps (instructions) are required.";
        }

        // 5. Validate User ID (Must be present and a valid integer)
        if (empty($data['user_id']) || !filter_var($data['user_id'], FILTER_VALIDATE_INT)) {
            $this->errors['user_id'] = "Invalid user ID.";
        }
        
        // Note: image_url is not mandatorily checked here as it can be null/default.

        // Return the errors array. If empty, data is valid.
        return $this->errors;
    }

    /**
     * Function to validate data required for deletion (ID and context).
     * @param array $data The data array containing 'id' and 'user_id'.
     * @return array Returns an array of errors.
     */
    public function validateDelete(array $data): array {
        
        // Validate Recipe ID
        if (empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
            $this->errors['id'] = "Invalid recipe ID for deletion process.";
        }
        
        // Check for user_id integrity for ownership check
         if (empty($data['user_id']) || !filter_var($data['user_id'], FILTER_VALIDATE_INT)) {
            $this->errors['user_id'] = "User ID context is missing for deletion.";
        }

        return $this->errors;
    }
}