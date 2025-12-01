document.addEventListener('DOMContentLoaded', () => {
  // Initialize global components
  Dropdown.init();
  
  // Initialize page-specific scripts based on body class
  const body = document.body;
  
  if (body.classList.contains('page-home')) {
    HomePage.init();
  }
  else if (body.classList.contains('page-add-recipe')) {
    AddRecipePage.init();
  }
  else if (body.classList.contains('page-edit-recipe')) {
    EditRecipePage.init();
  }
  else if (body.classList.contains('page-recipe-detail')) {
    RecipeDetailPage.init();
  }
  else if (body.classList.contains('page-profile')) {
    ProfilePage.init();
  }
  else if (body.classList.contains('page-search')) {
    SearchPage.init();
  }
});
