const RecipeDetailPage = {
  init: function() {
    Comments.init();
    Favorites.init();
    
    // Delete recipe functionality
    const deleteBtn = document.querySelector('.delete-recipe-btn');
    if (deleteBtn) {
      deleteBtn.addEventListener('click', () => {
        Modal.confirm('Are you sure you want to delete this recipe?', async () => {
          const recipeId = deleteBtn.dataset.recipeId;
          try {
            const res = await AppAPI.post('recipes/delete-recipe.php', { id: recipeId });
            if (res && res.success) {
              Toast.success('Recipe deleted');
              setTimeout(() => {
                window.location.href = '/my-recipes.php';
              }, 1000);
            } else {
              Toast.error(res.message || 'Failed to delete');
            }
          } catch (err) {
            Toast.error('Network error');
          }
        });
      });
    }
  }
};
