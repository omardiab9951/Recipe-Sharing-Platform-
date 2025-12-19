<?php
session_start();
require_once 'config/database.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  if (empty($email) || empty($password)) {
    $error = 'Please fill in all fields';
  } else {
    try {
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
      $stmt->execute([$email]);
      $user = $stmt->fetch();

      if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];

        header('Location: index.php');
        exit;
      } else {
        $error = 'Invalid email or password';
      }
    } catch (PDOException $e) {
      $error = 'Database error. Please try again later.';
    }
  }
}

// Set page title
$page_title = 'Login - Recipe Sharing Platform';

// Load HTML template
include 'templates/login.html';
