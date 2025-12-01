const Toast = {
  show: function(message, duration = 3000, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `app-toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    requestAnimationFrame(() => toast.classList.add('show'));
    
    setTimeout(() => toast.classList.remove('show'), duration - 300);
    setTimeout(() => toast.remove(), duration);
  },
  
  success: function(message, duration) {
    this.show(message, duration, 'success');
  },
  
  error: function(message, duration) {
    this.show(message, duration, 'error');
  }
};
