<footer style="background: #1f2937; color: white; padding: 40px 20px; margin-top: 80px;">
  <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
    <p style="margin-bottom: 15px; font-size: 24px; font-weight: 700;">🍳 Recipe Sharing Platform</p>
    <p style="color: #9ca3af; margin-bottom: 25px; font-size: 16px;">Your guide to delicious recipes from around the world</p>

    <div style="display: flex; justify-content: center; gap: 30px; margin-bottom: 25px; flex-wrap: wrap;">
      <a href="index.php" style="color: #d97706; text-decoration: none; font-weight: 500; transition: color 0.3s;">Home</a>
      <a href="browse.php" style="color: #d97706; text-decoration: none; font-weight: 500; transition: color 0.3s;">Browse Recipes</a>
      <a href="search.php" style="color: #d97706; text-decoration: none; font-weight: 500; transition: color 0.3s;">Search</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="add-recipe.php" style="color: #d97706; text-decoration: none; font-weight: 500; transition: color 0.3s;">Add Recipe</a>
      <?php endif; ?>
    </div>

    <div style="border-top: 1px solid #374151; padding-top: 20px; margin-top: 20px;">
      <p style="color: #6b7280; font-size: 14px;">&copy; <?php echo date('Y'); ?> Recipe Sharing Platform. All rights reserved.</p>
    </div>
  </div>
</footer>

<!-- JavaScript Files -->
<script src="assets/js/main.js"></script>
<script src="assets/js/validation.js"></script>
<script src="assets/js/search.js"></script>

</body>

</html>