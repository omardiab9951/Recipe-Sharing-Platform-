<?php

/**
 * File: auth_check.php
 * Purpose: Authentication helper functions
 * Author: B1 Team Member
 * Date: December 2025
 * Description: Provides authentication checking and protection for pages
 */

require_once __DIR__ . '/../config/session.php';

/**
 * Require user to be logged in
 * Redirects to login page if not authenticated
 */
function requireLogin()
{
  if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
  }
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn()
{
  return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Get current user ID
 * @return int|null
 */
function getCurrentUserId()
{
  return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

/**
 * Get current user name
 * @return string|null
 */
function getCurrentUserName()
{
  return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
}

/**
 * Prevent logged-in users from accessing auth pages
 */
function redirectIfLoggedIn()
{
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit();
  }
}
