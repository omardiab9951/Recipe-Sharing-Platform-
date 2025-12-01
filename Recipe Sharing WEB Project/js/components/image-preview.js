const ImagePreview = {
  init: function(fileInputSelector = '.image-input', previewSelector = '.image-preview') {
    const inputs = document.querySelectorAll(fileInputSelector);
    
    inputs.forEach(input => {
      input.addEventListener('change', (e) => {
        const file = e.target.files && e.target.files[0];
        const preview = input.closest('label')
          ? input.closest('label').querySelector(previewSelector)
          : document.querySelector(previewSelector);
        
        if (!preview) return;
        
        if (!file) {
          preview.src = '';
          preview.classList.add('hidden');
          return;
        }
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
          Toast.error('Please select an image file');
          input.value = '';
          return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
          Toast.error('Image must be less than 5MB');
          input.value = '';
          return;
        }
        
        const reader = new FileReader();
        reader.onload = () => {
          preview.src = reader.result;
          preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
      });
    });
  }
};
