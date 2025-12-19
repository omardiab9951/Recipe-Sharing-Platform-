<?php
session_start();
require_once 'config/database.php';

$page_title = 'Home - Recipe Sharing Platform';

// Get search query if exists
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch recipes (with search if provided)
if ($search) {
  $stmt = $pdo->prepare("
        SELECT r.*, u.name as author_name 
        FROM recipes r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.title LIKE ? OR r.ingredients LIKE ? OR r.category LIKE ?
        ORDER BY r.created_at DESC
    ");
  $searchTerm = "%{$search}%";
  $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
} else {
  $stmt = $pdo->query("
        SELECT r.*, u.name as author_name 
        FROM recipes r 
        JOIN users u ON r.user_id = u.id 
        ORDER BY r.created_at DESC 
        LIMIT 15
    ");
}
$recipes = $stmt->fetchAll();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
  <div class="container">
    <h1>Find Your Flavor</h1>
    <p>Welcome to Culinary Compass, your guide to a world of delicious recipes.<br>
      Discover, create, and share your passion for cooking.</p>
  </div>
</section>

<!-- Search Section -->
<section class="search-section">
  <div class="container">
    <div class="search-bar-container">
      <form method="GET" action="index.php" class="search-form">
        <input
          type="text"
          name="search"
          class="search-input"
          placeholder="Search recipes by name or ingredients..."
          value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="search-button">Search</button>
      </form>
    </div>
  </div>
</section>

<!-- Latest Recipes Section -->
<section class="recipes-section">
  <div class="container">
    <h2><?php echo $search ? 'Search Results' : 'Explore Recipes'; ?></h2>

    <?php if (count($recipes) > 0): ?>
      <div class="recipe-grid">
        <?php foreach ($recipes as $recipe): ?>
          <article class="recipe-card">
            <a href="recipe-detail.php?id=<?php echo $recipe['id']; ?>" class="recipe-card-link">
              <div class="recipe-card-image">
                <?php if (!empty($recipe['image']) && file_exists('assets/uploads/' . $recipe['image'])): ?>
                  <img src="assets/uploads/<?php echo htmlspecialchars($recipe['image']); ?>"
                    alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                <?php else: ?>
                  <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; font-size: 18px; font-weight: 600;">
                    🍽️ <?php echo htmlspecialchars($recipe['title']); ?>
                  </div>
                <?php endif; ?>

                <span class="recipe-category-badge">
                  <?php echo htmlspecialchars($recipe['category']); ?>
                </span>
              </div>

              <div class="recipe-card-content">
                <h3 class="recipe-card-title">
                  <?php echo htmlspecialchars($recipe['title']); ?>
                </h3>

                <p class="recipe-card-description">
                  <?php
                  $ingredients = htmlspecialchars($recipe['ingredients']);
                  echo substr($ingredients, 0, 100) . (strlen($ingredients) > 100 ? '...' : '');
                  ?>
                </p>

                <div class="recipe-card-meta">
                  <span class="recipe-author">
                    By <?php echo htmlspecialchars($recipe['author_name']); ?>
                  </span>
                  <span class="recipe-date">
                    <?php echo date('M d, Y', strtotime($recipe['created_at'])); ?>
                  </span>
                </div>
              </div>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-recipes">
        <p><?php echo $search ? 'No recipes found matching your search.' : 'No recipes found. Be the first to share a recipe!'; ?></p>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="add-recipe.php" class="btn btn-primary">Add Your First Recipe</a>
        <?php else: ?>
          <a href="register.php" class="btn btn-primary">Register to Add Recipes</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
  <div class="container">
    <h2>Browse by Category</h2>
    <div class="category-grid">
      <a href="browse.php?category=Breakfast" class="category-card">
        <h3>🥞 Breakfast</h3>
      </a>
      <a href="browse.php?category=Lunch" class="category-card">
        <h3>🥗 Lunch</h3>
      </a>
      <a href="browse.php?category=Dinner" class="category-card">
        <h3>🍝 Dinner</h3>
      </a>
      <a href="browse.php?category=Dessert" class="category-card">
        <h3>🍰 Dessert</h3>
      </a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>