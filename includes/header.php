<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? $page_title : 'Recipe Sharing Platform'; ?></title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
      background: #f9fafb;
      min-height: 100vh;
    }

    header {
      background: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    nav {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-size: 24px;
      font-weight: 700;
      color: #d97706;
      text-decoration: none;
    }

    .nav-links {
      display: flex;
      gap: 30px;
      align-items: center;
      list-style: none;
    }

    .nav-links a {
      text-decoration: none;
      color: #374151;
      font-weight: 500;
      transition: color 0.3s;
    }

    .nav-links a:hover {
      color: #d97706;
    }

    .btn-primary {
      padding: 10px 24px;
      background: #d97706;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s;
    }

    .btn-primary:hover {
      background: #b45309;
      transform: translateY(-2px);
    }

    .btn-logout {
      padding: 10px 24px;
      background: #ef4444;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s;
    }

    .btn-logout:hover {
      background: #dc2626;
      transform: translateY(-2px);
    }

    .user-welcome {
      color: #374151;
      font-weight: 600;
    }

    @media (max-width: 768px) {
      nav {
        flex-direction: column;
        gap: 20px;
      }

      .nav-links {
        flex-direction: column;
        gap: 15px;
      }
    }
  </style>
</head>

<body>

  <header>
    <nav>
      <a href="index.php" class="logo">🍳 Recipe Sharing</a>

      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="browse.php">Browse</a></li>
        <li><a href="search.php">Search</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="add-recipe.php" class="btn-primary">➕ Add Recipe</a></li>
          <li><span class="user-welcome">👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?></span></li>
          <li><a href="logout.php" class="btn-logout">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php" class="btn-primary">Register</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>