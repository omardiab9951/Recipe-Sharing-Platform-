<?php

/**
 * File: Comment.php
 * Purpose: Comment model - handles all comment database operations
 * Author: B2 Team Member
 * Date: December 2025
 * Description: Provides methods for comment CRUD operations
 */

class Comment
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
   * Create a new comment
   * @param int $recipe_id Recipe ID
   * @param int $user_id User ID
   * @param string $comment_text Comment text
   * @return int|bool Comment ID if successful, false otherwise
   */
  public function createComment($recipe_id, $user_id, $comment_text)
  {
    $stmt = $this->conn->prepare(
      "INSERT INTO comments (recipe_id, user_id, comment_text) VALUES (?, ?, ?)"
    );

    if (!$stmt) {
      return false;
    }

    $stmt->bind_param("iis", $recipe_id, $user_id, $comment_text);

    if ($stmt->execute()) {
      $comment_id = $stmt->insert_id;
      $stmt->close();
      return $comment_id;
    }

    $stmt->close();
    return false;
  }

  /**
   * Get comments by recipe ID
   * @param int $recipe_id Recipe ID
   * @return array Array of comments
   */
  public function getCommentsByRecipeId($recipe_id)
  {
    $stmt = $this->conn->prepare(
      "SELECT comments.*, users.name as author_name 
             FROM comments 
             INNER JOIN users ON comments.user_id = users.id 
             WHERE comments.recipe_id = ? 
             ORDER BY comments.created_at DESC"
    );

    if (!$stmt) {
      return [];
    }

    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = [];

    while ($row = $result->fetch_assoc()) {
      $comments[] = $row;
    }

    $stmt->close();
    return $comments;
  }

  /**
   * Get comment by ID
   * @param int $comment_id Comment ID
   * @return array|null Comment data or null
   */
  public function getCommentById($comment_id)
  {
    $stmt = $this->conn->prepare(
      "SELECT comments.*, users.name as author_name 
             FROM comments 
             INNER JOIN users ON comments.user_id = users.id 
             WHERE comments.id = ?"
    );

    if (!$stmt) {
      return null;
    }

    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $comment = $result->fetch_assoc();
      $stmt->close();
      return $comment;
    }

    $stmt->close();
    return null;
  }

  /**
   * Get comment count for recipe
   * @param int $recipe_id Recipe ID
   * @return int Comment count
   */
  public function getCommentCount($recipe_id)
  {
    $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM comments WHERE recipe_id = ?");

    if (!$stmt) {
      return 0;
    }

    $stmt->bind_param("i", $recipe_id);
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

  /**
   * Delete comment
   * @param int $comment_id Comment ID
   * @return bool Success status
   */
  public function deleteComment($comment_id)
  {
    $stmt = $this->conn->prepare("DELETE FROM comments WHERE id = ?");

    if (!$stmt) {
      return false;
    }

    $stmt->bind_param("i", $comment_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
  }

  /**
   * Check if user owns comment
   * @param int $comment_id Comment ID
   * @param int $user_id User ID
   * @return bool True if user owns comment
   */
  public function isOwner($comment_id, $user_id)
  {
    $stmt = $this->conn->prepare("SELECT id FROM comments WHERE id = ? AND user_id = ?");

    if (!$stmt) {
      return false;
    }

    $stmt->bind_param("ii", $comment_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    $is_owner = $stmt->num_rows > 0;
    $stmt->close();

    return $is_owner;
  }
}
