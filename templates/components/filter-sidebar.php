<aside class="filter-sidebar">
  <h3>Filter Recipes</h3>

  <form action="/search.php" method="GET" id="filter-form">
    <label>Category</label>
    <select name="category" id="category-filter">
      <option value="">All</option>
      <option value="Breakfast">Breakfast</option>
      <option value="Lunch">Lunch</option>
      <option value="Dinner">Dinner</option>
      <option value="Dessert">Dessert</option>
      <option value="Snack">Snack</option>
    </select>

    <button class="btn-primary" type="submit">Apply</button>
  </form>
</aside>
