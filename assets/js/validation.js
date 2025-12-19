// Advanced form validation
function validateRecipeForm(form) {
    let isValid = true;
    const errors = [];
    
    // Title validation
    const title = form.querySelector('#title');
    if (title && title.value.trim().length < 3) {
        errors.push('Recipe title must be at least 3 characters long');
        title.style.borderColor = '#ef4444';
        isValid = false;
    } else if (title) {
        title.style.borderColor = '#10b981';
    }
    
    // Ingredients validation
    const ingredients = form.querySelector('#ingredients');
    if (ingredients && ingredients.value.trim().length < 10) {
        errors.push('Please provide detailed ingredients');
        ingredients.style.borderColor = '#ef4444';
        isValid = false;
    } else if (ingredients) {
        ingredients.style.borderColor = '#10b981';
    }
    
    // Instructions validation
    const instructions = form.querySelector('#instructions');
    if (instructions && instructions.value.trim().length < 20) {
        errors.push('Please provide detailed instructions');
        instructions.style.borderColor = '#ef4444';
        isValid = false;
    } else if (instructions) {
        instructions.style.borderColor = '#10b981';
    }
    
    // Category validation
    const category = form.querySelector('#category');
    if (category && category.value === '') {
        errors.push('Please select a category');
        category.style.borderColor = '#ef4444';
        isValid = false;
    } else if (category) {
        category.style.borderColor = '#10b981';
    }
    
    // Display errors
    if (!isValid) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'validation-errors';
        errorDiv.style.cssText = `
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        `;
        errorDiv.innerHTML = '<strong>Please fix the following errors:</strong><ul style="margin: 10px 0 0 20px;">' + 
            errors.map(err => `<li>${err}</li>`).join('') + '</ul>';
        
        const existingError = form.querySelector('.validation-errors');
        if (existingError) {
            existingError.remove();
        }
        
        form.insertBefore(errorDiv, form.firstChild);
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    return isValid;
}

// Email validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Real-time email validation
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.querySelector('input[type="email"]');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            if (this.value && !validateEmail(this.value)) {
                this.style.borderColor = '#ef4444';
                let errorMsg = this.parentElement.querySelector('.email-error');
                if (!errorMsg) {
                    errorMsg = document.createElement('span');
                    errorMsg.className = 'email-error';
                    errorMsg.style.cssText = 'color: #ef4444; font-size: 13px; margin-top: 5px; display: block;';
                    errorMsg.textContent = 'Please enter a valid email address';
                    this.parentElement.appendChild(errorMsg);
                }
            } else {
                this.style.borderColor = '#10b981';
                const errorMsg = this.parentElement.querySelector('.email-error');
                if (errorMsg) errorMsg.remove();
            }
        });
    }
});
