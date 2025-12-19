<?php
session_start();
require_once 'config/database.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = isset($_POST['name']) ? trim($_POST['name']) : '';
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';
  $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

  // Validation
  if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
    $error = 'Please fill in all fields';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Please enter a valid email address';
  } elseif (strlen($password) < 6) {
    $error = 'Password must be at least 6 characters long';
  } elseif ($password !== $confirm_password) {
    $error = 'Passwords do not match';
  } else {
    // Check if email already exists
    try {
      $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
      $stmt->execute([$email]);

      if ($stmt->fetch()) {
        $error = 'Email already registered';
      } else {
        // Create user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");

        if ($stmt->execute([$name, $email, $hashed_password])) {
          $user_id = $pdo->lastInsertId();

          // Auto-login
          $_SESSION['user_id'] = $user_id;
          $_SESSION['user_name'] = $name;
          $_SESSION['user_email'] = $email;

          header('Location: index.php');
          exit;
        } else {
          $error = 'Registration failed. Please try again.';
        }
      }
    } catch (PDOException $e) {
      $error = 'Database error. Please try again later.';
    }
  }
}

// Set page title
$page_title = 'Register - Recipe Sharing Platform';

// Load HTML template
include 'templates/register.html';
