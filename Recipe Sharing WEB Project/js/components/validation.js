const Validation = {
  isRequired: function(value) {
    return value != null && String(value).trim() !== '';
  },
  
  maxLength: function(value, n) {
    return String(value || '').trim().length <= n;
  },
  
  minLength: function(value, n) {
    return String(value || '').trim().length >= n;
  },
  
  isEmail: function(value) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(value).trim());
  },
  
  showError: function(input, message) {
    if (!input) return;
    input.classList.add('is-invalid');
    let err = input.parentElement.querySelector('.error-msg');
    if (!err) {
      err = document.createElement('div');
      err.className = 'error-msg';
      input.parentElement.appendChild(err);
    }
    err.textContent = message;
  },
  
  clearError: function(input) {
    if (!input) return;
    input.classList.remove('is-invalid');
    const err = input.parentElement.querySelector('.error-msg');
    if (err) err.textContent = '';
  },
  
  clearAllErrors: function(fields) {
    fields.forEach(field => this.clearError(field));
  }
};