<?php

/**
 * File: FormValidator.php
 * Purpose: Form validation for authentication and other forms
 * Author: B1 Team Member
 * Date: December 2025
 * Description: Server-side validation with comprehensive error handling
 */

class FormValidator
{
  private $errors = [];

  /**
   * Validate registration form
   * @param string $name User's name
   * @param string $email User's email
   * @param string $password Password
   * @param string $confirm_password Password confirmation
   * @return bool Validation success
   */
  public function validateRegistration($name, $email, $password, $confirm_password)
  {
    $this->errors = [];

    // Validate name
    if (empty($name)) {
      $this->errors['name'] = "Name is required";
    } elseif (strlen($name) < 2) {
      $this->errors['name'] = "Name must be at least 2 characters";
    } elseif (strlen($name) > 100) {
      $this->errors['name'] = "Name must not exceed 100 characters";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
      $this->errors['name'] = "Name can only contain letters and spaces";
    }

    // Validate email
    if (empty($email)) {
      $this->errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->errors['email'] = "Invalid email format";
    } elseif (strlen($email) > 100) {
      $this->errors['email'] = "Email must not exceed 100 characters";
    }

    // Validate password
    if (empty($password)) {
      $this->errors['password'] = "Password is required";
    } elseif (strlen($password) < 6) {
      $this->errors['password'] = "Password must be at least 6 characters";
    } elseif (strlen($password) > 255) {
      $this->errors['password'] = "Password is too long";
    } elseif (!preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
      $this->errors['password'] = "Password must contain both letters and numbers";
    }

    // Validate password confirmation
    if (empty($confirm_password)) {
      $this->errors['confirm_password'] = "Please confirm your password";
    } elseif ($password !== $confirm_password) {
      $this->errors['confirm_password'] = "Passwords do not match";
    }

    return empty($this->errors);
  }

  /**
   * Validate login form
   * @param string $email User's email
   * @param string $password Password
   * @return bool Validation success
   */
  public function validateLogin($email, $password)
  {
    $this->errors = [];

    // Validate email
    if (empty($email)) {
      $this->errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->errors['email'] = "Invalid email format";
    }

    // Validate password
    if (empty($password)) {
      $this->errors['password'] = "Password is required";
    }

    return empty($this->errors);
  }

  /**
   * Get all validation errors
   * @return array Errors array
   */
  public function getErrors()
  {
    return $this->errors;
  }

  /**
   * Get specific field error
   * @param string $field Field name
   * @return string Error message or empty string
   */
  public function getError($field)
  {
    return isset($this->errors[$field]) ? $this->errors[$field] : '';
  }

  /**
   * Check if there are any errors
   * @return bool
   */
  public function hasErrors()
  {
    return !empty($this->errors);
  }

  /**
   * Sanitize input data
   * @param string $data Input data
   * @return string Sanitized data
   */
  public function sanitize($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
  }

  /**
   * Validate CSRF token
   * @param string $token Token to validate
   * @return bool
   */
  public function validateCSRFToken($token)
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
  }
}
