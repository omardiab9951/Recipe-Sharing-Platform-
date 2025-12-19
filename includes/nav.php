<?php

/**
 * File: nav.php
 * Purpose: Navigation menu - shows different options based on login status
 * Author: B1 Team Member
 * Date: December 2025
 */

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="main-nav">
  <ul class="nav-menu">
    <li class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
      <a href="index.php">Home</a>
    </li>
    <li class="<?php echo $current_page === 'search.php' ? 'active' : ''; ?>">
      <a href="search.php">Browse Recipes</a>
    </li>

    <?php if ($is_logged_in): ?>
      <li class="<?php echo $current_page === 'add-recipe.php' ? 'active' : ''; ?>">
        <a href="add-recipe.php">Add Recipe</a>
      </li>
      <li class="user-menu">
        <span class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <a href="logout.php" class="btn btn-logout">Logout</a>
      </li>
    <?php else: ?>
      <li class="<?php echo $current_page === 'login.php' ? 'active' : ''; ?>">
        <a href="login.php" class="btn btn-login">Login</a>
      </li>
      <li class="<?php echo $current_page === 'register.php' ? 'active' : ''; ?>">
        <a href="register.php" class="btn btn-register">Register</a>
      </li>
    <?php endif; ?>
  </ul>
</nav>