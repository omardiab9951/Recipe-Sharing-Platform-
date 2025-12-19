<?php

/**
 * File: Recipe.php
 * Purpose: Recipe model - handles all recipe database operations
 * Author: B2 Team Member
 * Date: December 2025
 * Description: Provides methods for recipe CRUD operations
 */

class Recipe
{
    private $conn;

    /**
     * Constructor
     * @param mysqli $connection Database connection
     */
    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    /**
     * Create a new recipe
     * @param int $user_id User ID
     * @param string $title Recipe title
     * @param string $category Recipe category
     * @param string $ingredients Ingredients list
     * @param string $instructions Cooking instructions
     * @param string $image Image filename
     * @return int|bool Recipe ID if successful, false otherwise
     */
    public function createRecipe($user_id, $title, $category, $ingredients, $instructions, $image = 'default-recipe.jpg')
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO recipes (user_id, title, category, ingredients, instructions, image) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("isssss", $user_id, $title, $category, $ingredients, $instructions, $image);

        if ($stmt->execute()) {
            $recipe_id = $stmt->insert_id;
            $stmt->close();
            return $recipe_id;
        }

        $stmt->close();
        return false;
    }

    /**
     * Get all recipes
     * @param int $limit Number of recipes to return (0 = all)
     * @param int $offset Offset for pagination
     * @return array Array of recipes
     */
    public function getAllRecipes($limit = 0, $offset = 0)
    {
        if ($limit > 0) {
            $sql = "SELECT recipes.*, users.name as author_name 
                    FROM recipes 
                    INNER JOIN users ON recipes.user_id = users.id 
                    ORDER BY recipes.created_at DESC 
                    LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $limit, $offset);
        } else {
            $sql = "SELECT recipes.*, users.name as author_name 
                    FROM recipes 
                    INNER JOIN users ON recipes.user_id = users.id 
                    ORDER BY recipes.created_at DESC";
            $stmt = $this->conn->prepare($sql);
        }

        if (!$stmt) {
            return [];
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $recipes = [];

        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }

        $stmt->close();
        return $recipes;
    }

    /**
     * Get recipe by ID
     * @param int $recipe_id Recipe ID
     * @return array|null Recipe data or null
     */
    public function getRecipeById($recipe_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT recipes.*, users.name as author_name, users.email as author_email 
             FROM recipes 
             INNER JOIN users ON recipes.user_id = users.id 
             WHERE recipes.id = ?"
        );

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $recipe_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $recipe = $result->fetch_assoc();
            $stmt->close();
            return $recipe;
        }

        $stmt->close();
        return null;
    }

    /**
     * Get recipes by user ID
     * @param int $user_id User ID
     * @return array Array of recipes
     */
    public function getRecipesByUserId($user_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM recipes WHERE user_id = ? ORDER BY created_at DESC"
        );

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $recipes = [];

        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }

        $stmt->close();
        return $recipes;
    }

    /**
     * Get recipes by category
     * @param string $category Category name
     * @return array Array of recipes
     */
    public function getRecipesByCategory($category)
    {
        $stmt = $this->conn->prepare(
            "SELECT recipes.*, users.name as author_name 
             FROM recipes 
             INNER JOIN users ON recipes.user_id = users.id 
             WHERE recipes.category = ? 
             ORDER BY recipes.created_at DESC"
        );

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        $recipes = [];

        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }

        $stmt->close();
        return $recipes;
    }

    /**
     * Search recipes by title
     * @param string $search_term Search term
     * @return array Array of recipes
     */
    public function searchRecipes($search_term)
    {
        $search_term = "%{$search_term}%";
        $stmt = $this->conn->prepare(
            "SELECT recipes.*, users.name as author_name 
             FROM recipes 
             INNER JOIN users ON recipes.user_id = users.id 
             WHERE recipes.title LIKE ? OR recipes.ingredients LIKE ? 
             ORDER BY recipes.created_at DESC"
        );

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("ss", $search_term, $search_term);
        $stmt->execute();
        $result = $stmt->get_result();
        $recipes = [];

        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }

        $stmt->close();
        return $recipes;
    }

    /**
     * Update recipe
     * @param int $recipe_id Recipe ID
     * @param string $title Recipe title
     * @param string $category Recipe category
     * @param string $ingredients Ingredients list
     * @param string $instructions Cooking instructions
     * @param string $image Image filename (optional)
     * @return bool Success status
     */
    public function updateRecipe($recipe_id, $title, $category, $ingredients, $instructions, $image = null)
    {
        if ($image) {
            $stmt = $this->conn->prepare(
                "UPDATE recipes 
                 SET title = ?, category = ?, ingredients = ?, instructions = ?, image = ? 
                 WHERE id = ?"
            );
            $stmt->bind_param("sssssi", $title, $category, $ingredients, $instructions, $image, $recipe_id);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE recipes 
                 SET title = ?, category = ?, ingredients = ?, instructions = ? 
                 WHERE id = ?"
            );
            $stmt->bind_param("ssssi", $title, $category, $ingredients, $instructions, $recipe_id);
        }

        if (!$stmt) {
            return false;
        }

        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Delete recipe
     * @param int $recipe_id Recipe ID
     * @return bool Success status
     */
    public function deleteRecipe($recipe_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM recipes WHERE id = ?");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $recipe_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Check if user owns recipe
     * @param int $recipe_id Recipe ID
     * @param int $user_id User ID
     * @return bool True if user owns recipe
     */
    public function isOwner($recipe_id, $user_id)
    {
        $stmt = $this->conn->prepare("SELECT id FROM recipes WHERE id = ? AND user_id = ?");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ii", $recipe_id, $user_id);
        $stmt->execute();
        $stmt->store_result();

        $is_owner = $stmt->num_rows > 0;
        $stmt->close();

        return $is_owner;
    }

    /**
     * Get total recipe count
     * @return int Total recipes
     */
    public function getTotalRecipes()
    {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM recipes");

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }

        return 0;
    }

    /**
     * Get recipe count by category
     * @param string $category Category name
     * @return int Recipe count
     */
    public function getRecipeCountByCategory($category)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM recipes WHERE category = ?");

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['total'];
        }

        $stmt->close();
        return 0;
    }
}
