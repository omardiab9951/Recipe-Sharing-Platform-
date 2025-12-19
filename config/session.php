<?php

/**
 * File: session.php
 * Purpose: Session security configuration
 * Author: B1 Team Member
 * Date: December 2025
 * Description: Implements secure session handling with hijacking prevention
 */

// Prevent session hijacking
if (session_status() === PHP_SESSION_NONE) {
  // Set secure session cookie parameters
  session_set_cookie_params([
    'lifetime' => 3600, // 1 hour
    'path' => '/',
    'secure' => false, // Set to true if using HTTPS
    'httponly' => true, // Prevent JavaScript access
    'samesite' => 'Lax' // CSRF protection
  ]);

  session_start();

  // Regenerate session ID periodically to prevent fixation attacks
  if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
  } else if (time() - $_SESSION['created'] > 1800) {
    // Session created more than 30 minutes ago, regenerate ID
    session_regenerate_id(true);
    $_SESSION['created'] = time();
  }

  // Session timeout after 1 hour of inactivity
  if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
    // Last request was more than 1 hour ago
    session_unset();
    session_destroy();
    session_start();
  }
  $_SESSION['last_activity'] = time();
}
