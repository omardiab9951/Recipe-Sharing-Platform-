<form id="search-form" class="search-form" action="/search.php" method="GET">
  <input
    id="search-input"
    name="q"
    type="search"
    placeholder="Search recipes..."
    aria-label="Search recipes">
  
  <select id="category-filter" name="category">
    <option value="">All categories</option>
    <option>Breakfast</option>
    <option>Lunch</option>
    <option>Dinner</option>
    <option>Dessert</option>
    <option>Snack</option>
  </select>

  <button class="btn-primary" aria-label="Search">Search</button>
</form>
