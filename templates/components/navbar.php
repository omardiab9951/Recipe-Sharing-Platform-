<nav class="navbar">
  <ul class="nav-list">
    <li><a href="/">Home</a></li>
    <li><a href="/add-recipe.php">Add Recipe</a></li>
    <li><a href="/favorites.php">Favorites</a></li>
    <li><a href="/my-recipes.php">My Recipes</a></li>
    <li><a href="/about.php">About</a></li>
    <li><a href="/contact.php">Contact</a></li>

    <?php if (!empty($user)): ?>
      <li class="dropdown">
        <button data-dropdown-toggle="user-menu" class="btn-link">Hi, <?= htmlspecialchars($user["name"]) ?></button>
        <div id="user-menu" class="dropdown-menu dropdown hidden">
          <a href="/profile.php">Profile</a>
          <a href="/logout.php">Logout</a>
        </div>
      </li>
    <?php else: ?>
      <li><a href="/login.php">Login</a></li>
      <li><a href="/register.php">Register</a></li>
    <?php endif; ?>
  </ul>
</nav>
