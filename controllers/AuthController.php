<?php

/**
 * File: AuthController.php
 * Purpose: Authentication controller - handles login, register, and logout logic
 * Author: B1 Team Member
 * Date: December 2025
 * Description: Manages user authentication with security measures
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../validators/FormValidator.php';

class AuthController
{
  private $conn;
  private $userModel;
  private $validator;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->conn = getDBConnection();
    $this->userModel = new User($this->conn);
    $this->validator = new FormValidator();
  }

  /**
   * Handle user registration
   * @param string $name User's name
   * @param string $email User's email
   * @param string $password Password
   * @param string $confirm_password Password confirmation
   * @return array Result with success status and message/errors
   */
  public function register($name, $email, $password, $confirm_password)
  {
    // Sanitize inputs
    $name = $this->validator->sanitize($name);
    $email = $this->validator->sanitize($email);

    // Validate form data
    if (!$this->validator->validateRegistration($name, $email, $password, $confirm_password)) {
      return [
        'success' => false,
        'errors' => $this->validator->getErrors()
      ];
    }

    // Check if email already exists
    if ($this->userModel->emailExists($email)) {
      return [
        'success' => false,
        'errors' => ['email' => 'Email already registered']
      ];
    }

    // Register user
    if ($this->userModel->register($name, $email, $password)) {
      return [
        'success' => true,
        'message' => 'Registration successful! Please login.'
      ];
    } else {
      return [
        'success' => false,
        'errors' => ['general' => 'Registration failed. Please try again.']
      ];
    }
  }

  /**
   * Handle user login
   * @param string $email User's email
   * @param string $password Password
   * @return array Result with success status and message/errors
   */
  public function login($email, $password)
  {
    // Sanitize inputs
    $email = $this->validator->sanitize($email);

    // Validate form data
    if (!$this->validator->validateLogin($email, $password)) {
      return [
        'success' => false,
        'errors' => $this->validator->getErrors()
      ];
    }

    // Attempt login
    $user = $this->userModel->login($email, $password);

    if ($user) {
      // Regenerate session ID to prevent session fixation
      session_regenerate_id(true);

      // Store user data in session
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      $_SESSION['user_email'] = $user['email'];
      $_SESSION['logged_in'] = true;
      $_SESSION['login_time'] = time();

      return [
        'success' => true,
        'message' => 'Login successful!',
        'user' => $user
      ];
    } else {
      return [
        'success' => false,
        'errors' => ['general' => 'Invalid email or password']
      ];
    }
  }

  /**
   * Handle user logout
   * @return array Result with success status
   */
  public function logout()
  {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time() - 3600, '/');
    }

    // Destroy the session
    session_destroy();

    return [
      'success' => true,
      'message' => 'Logged out successfully'
    ];
  }

  /**
   * Check if user is logged in
   * @return bool
   */
  public function isLoggedIn()
  {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
  }

  /**
   * Get current logged-in user
   * @return array|null User data or null
   */
  public function getCurrentUser()
  {
    if ($this->isLoggedIn()) {
      return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email']
      ];
    }

    return null;
  }

  /**
   * Generate CSRF token
   * @return string CSRF token
   */
  public function generateCSRFToken()
  {
    if (!isset($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
  }

  /**
   * Verify CSRF token
   * @param string $token Token to verify
   * @return bool
   */
  public function verifyCSRFToken($token)
  {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
  }

  /**
   * Destructor - close database connection
   */
  public function __destruct()
  {
    if ($this->conn) {
      $this->conn->close();
    }
  }
}
