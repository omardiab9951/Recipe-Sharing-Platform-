const AddRecipePage = {
  init: function() {
    const form = document.getElementById('add-recipe-form');
    if (!form) return;
    
    ImagePreview.init('.recipe-image-input', '.recipe-image-preview');
    
    const title = form.querySelector('input[name="title"]');
    const category = form.querySelector('select[name="category"]');
    const ingredients = form.querySelector('textarea[name="ingredients"]');
    const steps = form.querySelector('textarea[name="steps"]');
    const image = form.querySelector('input[name="image"]');
    
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      // Clear previous errors
      Validation.clearAllErrors([title, category, ingredients, steps]);
      
      // Validate
      let isValid = true;
      
      if (!Validation.isRequired(title.value)) {
        Validation.showError(title, 'Title is required');
        isValid = false;
      } else if (!Validation.maxLength(title.value, 120)) {
        Validation.showError(title, 'Title must be under 120 characters');
        isValid = false;
      }
      
      if (!Validation.isRequired(category.value)) {
        Validation.showError(category, 'Please select a category');
        isValid = false;
      }
      
      if (!Validation.isRequired(ingredients.value)) {
        Validation.showError(ingredients, 'Please add ingredients');
        isValid = false;
      }
      
      if (!Validation.isRequired(steps.value)) {
        Validation.showError(steps, 'Please add cooking steps');
        isValid = false;
      }
      
      if (!isValid) return;
      
      // Prepare FormData
      const fd = new FormData();
      fd.append('title', title.value.trim());
      fd.append('category', category.value);
      fd.append('ingredients', ingredients.value.trim());
      fd.append('steps', steps.value.trim());
      
      if (image && image.files && image.files[0]) {
        fd.append('image', image.files[0]);
      }
      
      // Submit
      const submitBtn = form.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Adding...';
      
      try {
        const res = await AppAPI.post('recipes/create-recipe.php', fd);
        
        if (res && res.success) {
          Toast.success('Recipe added successfully!');
          if (res.id) {
            setTimeout(() => {
              window.location.href = `/recipe-detail.php?id=${res.id}`;
            }, 1000);
          }
        } else {
          Toast.error(res.message || 'Failed to add recipe');
          submitBtn.disabled = false;
          submitBtn.textContent = 'Add Recipe';
        }
      } catch (err) {
        Toast.error('Network error. Please try again.');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Add Recipe';
        console.error(err);
      }
    });
  }
};