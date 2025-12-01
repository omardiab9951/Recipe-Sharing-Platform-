const Favorites = {
  init: function(selector = '.favorite-btn') {
    document.addEventListener('click', async (e) => {
      const btn = e.target.closest(selector);
      if (!btn) return;
      
      const recipeId = btn.dataset.recipeId;
      if (!recipeId) return;
      
      const isFavorited = btn.classList.contains('is-favorited');
      const endpoint = isFavorited 
        ? 'favorites/remove-favorite.php' 
        : 'favorites/add-favorite.php';
      
      // Optimistic UI update
      btn.classList.toggle('is-favorited');
      btn.disabled = true;
      
      try {
        const res = await AppAPI.post(endpoint, { recipe_id: recipeId });
        
        if (res && res.success) {
          Toast.success(res.message || 'Updated favorites');
        } else {
          // Revert on failure
          btn.classList.toggle('is-favorited');
          Toast.error(res.message || 'Could not update favorite');
        }
      } catch (err) {
        // Revert on error
        btn.classList.toggle('is-favorited');
        Toast.error('Network error');
        console.error(err);
      } finally {
        btn.disabled = false;
      }
    });
  }
};