<?php

/**
 * File: search-bar.php
 * Purpose: Reusable search bar component
 * Author: B2 Team Member (used by F2 for styling)
 * Date: December 2025
 */

$current_search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
?>

<div class="search-bar-container">
  <form method="GET" action="search.php" class="search-form">
    <div class="search-input-wrapper">
      <input
        type="text"
        name="search"
        id="search-input"
        value="<?php echo $current_search; ?>"
        placeholder="Search recipes by name or ingredient..."
        class="search-input">
      <button type="submit" class="search-button">
        <span>Search</span>
      </button>
    </div>
  </form>
</div>