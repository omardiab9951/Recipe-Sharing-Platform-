<?php

/**
 * File: User.php
 * Purpose: User model - handles all user database operations
 * Author: B1 Team Member
 * Date: December 2025
 * Description: Provides methods for user registration, authentication, and data retrieval
 */

class User
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
   * Register a new user
   * @param string $name User's full name
   * @param string $email User's email
   * @param string $password Plain text password (will be hashed)
   * @return bool Success status
   */
  public function register($name, $email, $password)
  {
    // Hash the password using bcrypt
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $this->conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");

    if (!$stmt) {
      return false;
    }

    $stmt->bind_param("sss", $name, $email, $password_hash);

    // Execute and return result
    $result = $stmt->execute();
    $stmt->close();

    return $result;
  }

  /**
   * Check if email already exists in database
   * @param string $email Email to check
   * @return bool True if exists, false otherwise
   */
  public function emailExists($email)
  {
    $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");

    if (!$stmt) {
      return false;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    $exists = $stmt->num_rows > 0;
    $stmt->close();

    return $exists;
  }

  /**
   * Login user - verify credentials
   * @param string $email User's email
   * @param string $password Plain text password
   * @return array|bool User data if successful, false otherwise
   */
  public function login($email, $password)
  {
    $stmt = $this->conn->prepare("SELECT id, name, email, password_hash FROM users WHERE email = ?");

    if (!$stmt) {
      return false;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();

      // Verify password using password_verify
      if (password_verify($password, $user['password_hash'])) {
        $stmt->close();

        // Return user data without password hash
        unset($user['password_hash']);
        return $user;
      }
    }

    $stmt->close();
    return false;
  }

  /**
   * Get user by ID
   * @param int $user_id User ID
   * @return array|null User data or null
   */
  public function getUserById($user_id)
  {
    $stmt = $this->conn->prepare("SELECT id, name, email, created_at FROM users WHERE id = ?");

    if (!$stmt) {
      return null;
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();
      $stmt->close();
      return $user;
    }

    $stmt->close();
    return null;
  }

  /**
   * Get user by email
   * @param string $email User email
   * @return array|null User data or null
   */
  public function getUserByEmail($email)
  {
    $stmt = $this->conn->prepare("SELECT id, name, email, created_at FROM users WHERE email = ?");

    if (!$stmt) {
      return null;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();
      $stmt->close();
      return $user;
    }

    $stmt->close();
    return null;
  }

  /**
   * Update user information
   * @param int $user_id User ID
   * @param string $name New name
   * @return bool Success status
   */
  public function updateUser($user_id, $name)
  {
    $stmt = $this->conn->prepare("UPDATE users SET name = ? WHERE id = ?");

    if (!$stmt) {
      return false;
    }

    $stmt->bind_param("si", $name, $user_id);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
  }

  /**
   * Get total number of users
   * @return int User count
   */
  public function getTotalUsers()
  {
    $result = $this->conn->query("SELECT COUNT(*) as total FROM users");

    if ($result) {
      $row = $result->fetch_assoc();
      return $row['total'];
    }

    return 0;
  }
}
