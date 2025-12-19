<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? $page_title : 'Recipe Sharing Platform'; ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

  <style>
    /* HEADER STYLES */
    header {
      background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .header-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 70px;
    }

    .logo {
      font-size: 28px;
      font-weight: 700;
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo:hover {
      opacity: 0.9;
    }

    .nav-menu {
      display: flex;
      align-items: center;
      gap: 10px;
      list-style: none;
    }

    .nav-link {
      color: white;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s;
      text-decoration: none;
      display: block;
    }

    .nav-link:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .welcome-text {
      color: white;
      font-weight: 600;
      padding: 10px 20px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .user-icon {
      font-size: 20px;
    }

    .btn-logout {
      background: rgba(239, 68, 68, 0.9);
      color: white;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s;
      text-decoration: none;
      display: block;
    }

    .btn-logout:hover {
      background: rgba(239, 68, 68, 1);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
      display: none;
      background: none;
      border: none;
      color: white;
      font-size: 28px;
      cursor: pointer;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      .mobile-menu-toggle {
        display: block;
      }

      .nav-menu {
        position: absolute;
        top: 70px;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
        flex-direction: column;
        padding: 20px;
        gap: 5px;
        display: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      }

      .nav-menu.active {
        display: flex;
      }

      .nav-link,
      .btn-logout,
      .welcome-text {
        width: 100%;
        text-align: center;
      }
    }
  </style>

  <header>
    <div class="header-container">
      <a href="index.php" class="logo">
        🍳 Recipe Sharing
      </a>

      <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>

      <nav>
        <ul class="nav-menu" id="navMenu">
          <li><a href="index.php" class="nav-link">🏠 Home</a></li>

          <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Logged in user menu - My Recipes removed -->
            <li><a href="add-recipe.php" class="nav-link">➕ Add Recipe</a></li>
            <li>
              <span class="welcome-text">
                <span class="user-icon">👤</span>
                Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
              </span>
            </li>
            <li><a href="logout.php" class="btn-logout">🚪 Logout</a></li>
          <?php else: ?>
            <!-- Guest menu -->
            <li><a href="login.php" class="nav-link">🔐 Login</a></li>
            <li><a href="register.php" class="nav-link">📝 Register</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <script>
    function toggleMobileMenu() {
      const navMenu = document.getElementById('navMenu');
      navMenu.classList.toggle('active');
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      const navMenu = document.getElementById('navMenu');
      const toggle = document.querySelector('.mobile-menu-toggle');

      if (!navMenu.contains(event.target) && !toggle.contains(event.target)) {
        navMenu.classList.remove('active');
      }
    });
  </script>