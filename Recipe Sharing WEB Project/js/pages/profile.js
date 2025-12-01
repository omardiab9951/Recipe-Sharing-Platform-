const ProfilePage = {
  init: function() {
    ImagePreview.init('.profile-image-input', '.profile-image-preview');
    
    const form = document.getElementById('profile-form');
    if (!form) return;
    
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const fd = new FormData(form);
      
      try {
        const res = await AppAPI.post('users/update-profile.php', fd);
        if (res && res.success) {
          Toast.success('Profile updated!');
        } else {
          Toast.error(res.message || 'Update failed');
        }
      } catch (err) {
        Toast.error('Network error');
      }
    });
  }
};
