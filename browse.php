<?php
session_start();
require_once 'config/database.php';

$page_title = 'Browse Recipes - Recipe Sharing Platform';

// Get search and category filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Fetch recipes based on filters
try {
  $sql = "SELECT r.*, u.name as author_name 
            FROM recipes r 
            LEFT JOIN users u ON r.user_id = u.id 
            WHERE 1=1";

  $params = [];

  if ($search) {
    $sql .= " AND (r.title LIKE ? OR r.ingredients LIKE ?)";
    $searchTerm = "%{$search}%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
  }

  if ($category) {
    $sql .= " AND r.category = ?";
    $params[] = $category;
  }

  $sql .= " ORDER BY r.created_at DESC";

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  $recipes = $stmt->fetchAll();
} catch (PDOException $e) {
  $recipes = [];
  $error = $e->getMessage();
}

include 'includes/header.php';
?>

<style>
  .browse-hero {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
    padding: 60px 0;
    text-align: center;
    color: white;
  }

  .browse-hero h1 {
    font-size: 48px;
    font-weight: 700;
    margin: 0 0 15px 0;
  }

  .browse-hero p {
    font-size: 20px;
    margin: 0;
    opacity: 0.95;
  }

  .filter-section {
    background: white;
    padding: 40px 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  .filter-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
  }

  .category-tabs {
    display: flex;
    gap: 12px;
    margin-bottom: 30px;
    flex-wrap: wrap;
    justify-content: center;
  }

  .category-tab {
    padding: 12px 24px;
    border-radius: 50px;
    border: 2px solid #e5e7eb;
    background: white;
    color: #374151;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .category-tab:hover {
    border-color: #d97706;
    color: #d97706;
    transform: translateY(-2px);
  }

  .category-tab.active {
    background: #d97706;
    color: white;
    border-color: #d97706;
  }

  .search-container {
    max-width: 700px;
    margin: 0 auto;
  }

  .search-form {
    display: flex;
    gap: 12px;
    background: white;
    padding: 8px;
    border-radius: 50px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  }

  .search-input {
    flex: 1;
    padding: 14px 24px;
    border: none;
    border-radius: 50px;
    font-size: 16px;
    outline: none;
  }

  .search-input::placeholder {
    color: #9ca3af;
  }

  .search-button {
    padding: 14px 32px;
    background: #d97706;
    color: white;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
  }

  .search-button:hover {
    background: #b45309;
    transform: scale(1.05);
  }

  .results-section {
    background: #f9fafb;
    padding: 60px 0;
    min-height: 500px;
  }

  .results-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
  }

  .results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
  }

  .results-title {
    font-size: 32px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
  }

  .results-count {
    color: #6b7280;
    font-size: 16px;
  }

  .recipe-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
  }

  .recipe-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s;
    text-decoration: none;
    color: inherit;
  }

  .recipe-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
  }

  .recipe-card-image {
    width: 100%;
    height: 220px;
    position: relative;
    overflow: hidden;
  }

  .recipe-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
  }

  .recipe-card:hover .recipe-card-image img {
    transform: scale(1.1);
  }

  .recipe-card-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #d97706;
    font-size: 18px;
    font-weight: 600;
    text-align: center;
    padding: 20px;
  }

  .recipe-category-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(255, 255, 255, 0.95);
    color: #d97706;
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
  }

  .recipe-card-content {
    padding: 24px;
  }

  .recipe-card-title {
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 12px 0;
    line-height: 1.3;
  }

  .recipe-card-description {
    color: #6b7280;
    font-size: 14px;
    line-height: 1.6;
    margin: 0 0 16px 0;
  }

  .recipe-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid #e5e7eb;
    font-size: 13px;
  }

  .recipe-author {
    color: #374151;
    font-weight: 600;
  }

  .recipe-date {
    color: #9ca3af;
  }

  .no-results {
    text-align: center;
    padding: 80px 20px;
  }

  .no-results-icon {
    font-size: 80px;
    margin-bottom: 20px;
  }

  .no-results h3 {
    font-size: 24px;
    color: #374151;
    margin: 0 0 10px 0;
  }

  .no-results p {
    color: #6b7280;
    font-size: 16px;
    margin: 0 0 30px 0;
  }

  @media (max-width: 768px) {
    .browse-hero h1 {
      font-size: 36px;
    }

    .category-tabs {
      justify-content: flex-start;
      overflow-x: auto;
    }

    .results-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 10px;
    }

    .recipe-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<!-- Hero Section -->
<section class="browse-hero">
  <div class="container">
    <h1>🍽️ Browse All Recipes</h1>
    <p>Discover amazing recipes from our community</p>
  </div>
</section>

<!-- Filter Section -->
<section class="filter-section">
  <div class="filter-container">

    <!-- Category Filters -->
    <div class="category-tabs">
      <a href="browse.php" class="category-tab <?php echo empty($category) ? 'active' : ''; ?>">
        🍽️ All
      </a>
      <a href="browse.php?category=Breakfast" class="category-tab <?php echo $category === 'Breakfast' ? 'active' : ''; ?>">
        🥞 Breakfast
      </a>
      <a href="browse.php?category=Lunch" class="category-tab <?php echo $category === 'Lunch' ? 'active' : ''; ?>">
        🥗 Lunch
      </a>
      <a href="browse.php?category=Dinner" class="category-tab <?php echo $category === 'Dinner' ? 'active' : ''; ?>">
        🍝 Dinner
      </a>
      <a href="browse.php?category=Dessert" class="category-tab <?php echo $category === 'Dessert' ? 'active' : ''; ?>">
        🍰 Dessert
      </a>
    </div>

    <!-- Search Bar -->
    <div class="search-container">
      <form method="GET" action="browse.php" class="search-form">
        <?php if ($category): ?>
          <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
        <?php endif; ?>
        <input
          type="text"
          name="search"
          class="search-input"
          placeholder="🔍 Search recipes..."
          value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="search-button">Search</button>
      </form>
    </div>

  </div>
</section>

<!-- Results Section -->
<section class="results-section">
  <div class="results-container">

    <div class="results-header">
      <h2 class="results-title">
        <?php
        if ($search && $category) {
          echo htmlspecialchars($category) . ' Recipes matching "' . htmlspecialchars($search) . '"';
        } elseif ($search) {
          echo 'Results for "' . htmlspecialchars($search) . '"';
        } elseif ($category) {
          echo htmlspecialchars($category) . ' Recipes';
        } else {
          echo 'All Recipes';
        }
        ?>
      </h2>
      <span class="results-count"><?php echo count($recipes); ?> recipe<?php echo count($recipes) != 1 ? 's' : ''; ?> found</span>
    </div>

    <?php if (count($recipes) > 0): ?>
      <div class="recipe-grid">
        <?php foreach ($recipes as $recipe): ?>
          <a href="recipe-detail.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
            <div class="recipe-card-image">
              <?php if (!empty($recipe['image']) && file_exists('assets/uploads/' . $recipe['image'])): ?>
                <img src="assets/uploads/<?php echo htmlspecialchars($recipe['image']); ?>"
                  alt="<?php echo htmlspecialchars($recipe['title']); ?>">
              <?php else: ?>
                <div class="recipe-card-placeholder">
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
                  👨‍🍳 <?php echo htmlspecialchars($recipe['author_name']); ?>
                </span>
                <span class="recipe-date">
                  <?php echo date('M d, Y', strtotime($recipe['created_at'])); ?>
                </span>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-results">
        <div class="no-results-icon">🔍</div>
        <h3>No recipes found</h3>
        <p>Try adjusting your search or browse all categories</p>
        <a href="browse.php" class="search-button">
          Browse All Recipes
        </a>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php include 'includes/footer.php'; ?>