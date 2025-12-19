// Form Validation
document.addEventListener('DOMContentLoaded', function() {
    
    // Password strength indicator for register page
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = document.getElementById('password-strength');
            
            if (strength) {
                if (password.length === 0) {
                    strength.textContent = '';
                    strength.className = '';
                } else if (password.length < 6) {
                    strength.textContent = 'Weak password';
                    strength.className = 'password-weak';
                } else if (password.length < 10) {
                    strength.textContent = 'Medium password';
                    strength.className = 'password-medium';
                } else {
                    strength.textContent = 'Strong password';
                    strength.className = 'password-strong';
                }
            }
        });
    }
    
    // Confirm password validation
    const confirmPassword = document.getElementById('confirm_password');
    if (confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const message = document.getElementById('password-match');
            
            if (message) {
                if (confirm.length === 0) {
                    message.textContent = '';
                } else if (password === confirm) {
                    message.textContent = '✓ Passwords match';
                    message.className = 'password-match-yes';
                } else {
                    message.textContent = '✗ Passwords do not match';
                    message.className = 'password-match-no';
                }
            }
        });
    }
    
    // Image preview for recipe forms
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    let preview = document.getElementById('image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.id = 'image-preview';
                        preview.style.marginTop = '15px';
                        imageInput.parentElement.appendChild(preview);
                    }
                    preview.innerHTML = `<img src="${event.target.result}" style="max-width: 300px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Character counter for textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        const maxLength = 1000;
        const counter = document.createElement('div');
        counter.className = 'char-counter';
        counter.style.textAlign = 'right';
        counter.style.fontSize = '13px';
        counter.style.color = '#6b7280';
        counter.style.marginTop = '5px';
        textarea.parentElement.appendChild(counter);
        
        function updateCounter() {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${textarea.value.length} / ${maxLength} characters`;
            if (remaining < 100) {
                counter.style.color = '#ef4444';
            } else {
                counter.style.color = '#6b7280';
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });
    
    // Smooth scroll to top button
    const scrollButton = document.createElement('button');
    scrollButton.innerHTML = '↑';
    scrollButton.className = 'scroll-to-top';
    scrollButton.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: #d97706;
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 24px;
        cursor: pointer;
        display: none;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transition: all 0.3s;
    `;
    document.body.appendChild(scrollButton);
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            scrollButton.style.display = 'block';
        } else {
            scrollButton.style.display = 'none';
        }
    });
    
    scrollButton.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    // Loading animation for forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span style="opacity: 0.7;">Loading...</span>';
            }
        });
    });
    
    // Alert auto-dismiss
    const alerts = document.querySelectorAll('.error-message, .success-message');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // Recipe card click animation
    const recipeCards = document.querySelectorAll('.recipe-card');
    recipeCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.btn-view')) {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 100);
            }
        });
    });
});
