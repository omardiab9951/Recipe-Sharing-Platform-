/**
 * File: validation.js
 * Purpose: Client-side form validation
 * Author: F2 Team Member
 * Date: December 2025
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // REGISTRATION FORM VALIDATION
    // ============================================
    
    const registerForm = document.querySelector('form[action*="register"]');
    
    if (registerForm) {
        const nameInput = registerForm.querySelector('#name');
        const emailInput = registerForm.querySelector('#email');
        const passwordInput = registerForm.querySelector('#password');
        const confirmPasswordInput = registerForm.querySelector('#confirm_password');
        
        // Real-time validation
        if (nameInput) {
            nameInput.addEventListener('blur', function() {
                validateName(this);
            });
        }
        
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                validateEmail(this);
            });
        }
        
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                validatePassword(this);
                if (confirmPasswordInput && confirmPasswordInput.value) {
                    validateConfirmPassword(confirmPasswordInput, passwordInput);
                }
            });
        }
        
        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', function() {
                validateConfirmPassword(this, passwordInput);
            });
        }
        
        // Form submission validation
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            if (nameInput && !validateName(nameInput)) isValid = false;
            if (emailInput && !validateEmail(emailInput)) isValid = false;
            if (passwordInput && !validatePassword(passwordInput)) isValid = false;
            if (confirmPasswordInput && !validateConfirmPassword(confirmPasswordInput, passwordInput)) isValid = false;
            
            if (!isValid) {
                e.preventDefault();
                showError('Please fix the errors before submitting.');
            }
        });
    }
    
    // ============================================
    // LOGIN FORM VALIDATION
    // ============================================
    
    const loginForm = document.querySelector('form[action*="login"]');
    
    if (loginForm) {
        const emailInput = loginForm.querySelector('#email');
        const passwordInput = loginForm.querySelector('#password');
        
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            if (emailInput && !validateEmail(emailInput)) isValid = false;
            if (passwordInput && !validatePasswordRequired(passwordInput)) isValid = false;
            
            if (!isValid) {
                e.preventDefault();
                showError('Please fix the errors before submitting.');
            }
        });
    }
    
    // ============================================
    // RECIPE FORM VALIDATION
    // ============================================
    
    const recipeForm = document.querySelector('form.recipe-form');
    
    if (recipeForm) {
        const titleInput = recipeForm.querySelector('#title');
        const categoryInput = recipeForm.querySelector('#category');
        const ingredientsInput = recipeForm.querySelector('#ingredients');
        const instructionsInput = recipeForm.querySelector('#instructions');
        const imageInput = recipeForm.querySelector('#image');
        
        // Real-time validation
        if (titleInput) {
            titleInput.addEventListener('blur', function() {
                validateTitle(this);
            });
        }
        
        if (categoryInput) {
            categoryInput.addEventListener('change', function() {
                validateCategory(this);
            });
        }
        
        if (ingredientsInput) {
            ingredientsInput.addEventListener('blur', function() {
                validateIngredients(this);
            });
        }
        
        if (instructionsInput) {
            instructionsInput.addEventListener('blur', function() {
                validateInstructions(this);
            });
        }
        
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                validateImage(this);
            });
        }
        
        // Form submission validation
        recipeForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            if (titleInput && !validateTitle(titleInput)) isValid = false;
            if (categoryInput && !validateCategory(categoryInput)) isValid = false;
            if (ingredientsInput && !validateIngredients(ingredientsInput)) isValid = false;
            if (instructionsInput && !validateInstructions(instructionsInput)) isValid = false;
            if (imageInput && imageInput.files.length > 0 && !validateImage(imageInput)) isValid = false;
            
            if (!isValid) {
                e.preventDefault();
                showError('Please fix the errors before submitting.');
            }
        });
    }
    
    // ============================================
    // VALIDATION FUNCTIONS
    // ============================================
    
    function validateName(input) {
        const value = input.value.trim();
        const errorSpan = getErrorSpan(input);
        
        if (value.length < 2) {
            showInputError(input, errorSpan, 'Name must be at least 2 characters');
            return false;
        }
        
        if (!/^[a-zA-Z\s]+$/.test(value)) {
            showInputError(input, errorSpan, 'Name can only contain letters and spaces');
            return false;
        }
        
        showInputSuccess(input, errorSpan);
        return true;
    }
    
    function validateEmail(input) {
        const value = input.value.trim();
        const errorSpan = getErrorSpan(input);
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailRegex.test(value)) {
            showInputError(input, errorSpan, 'Please enter a valid email address');
            return false;
        }
        
        showInputSuccess(input, errorSpan);
        return true;
    }
    
    function validatePassword(input) {
        const value = input.value;
        const errorSpan = getErrorSpan(input);
        
        if (value.length < 6) {
            showInputError(input, errorSpan, 'Password must be at least 6 characters');
            return false;
        }
        
        if (!/[A-Za-z]/.test(value) || !/[0-9]/.test(value)) {
            showInputError(input, errorSpan, 'Password must contain both letters and numbers');
            return false;
        }
        
        showInputSuccess(input, errorSpan);
        return true;
    }
    
    function validatePasswordRequired(input) {
        const value = input.value;
        const errorSpan = getErrorSpan(input);
        
        if (value.length === 0) {
            showInputError(input, errorSpan, 'Password is required');
            return false;
        }
        
        showInputSuccess(input, errorSpan);
        return true;
    }
    
    function validateConfirmPassword(input, passwordInput) {
        const value = input.value;
        const passwordValue = passwordInput.value;
        const errorSpan = getErrorSpan(input);
        
        if (value !== passwordValue) {
            showInputError(input, errorSpan, 'Passwords do not match');
            return false;
        }
        
        showInputSuccess(input, errorSpan);
        return true;
    }
    
    function validateTitle(input) {
        const value = input.value.trim();
        const errorSpan = getErrorSpan(input);
        
        if (value.length < 3) {
            showInputError(input, errorSpan, 'Title must be at least 3 characters');
            return false;
        }
        
        showInputSuccess(input, errorSpan);
        return true;
    }
    
    function validateCategory(input) {
        const value = input.value;
        const errorSpan = getErrorSpan(input);
        
        if (!value) {
            showInputError(input, errorSpan, 'Please select a category');
            return false;
        }
        
        showInputSuccess(input, errorSpan);
        return true;
    }
    
    function validateIngredients(input) {
        const value = input.value.trim();
        const errorSpan = getErrorSpan(input);
        
        if (value.length < 10) {
            showInputError(input, errorSpan, 'Ingredients must be at least 10 characters');
            return false;
        }
        
        showInputSuccess(input, errorSpan);
        return true;
    }
    
    function validateInstructions(input) {
        const value = input.value.trim();
        const errorSpan = getErrorSpan(input);
        
        if (value.length < 20) {
            showInputError(input, errorSpan, 'Instructions must be at least 20 characters');
            return false;
        }
        
        showInputSuccess(input, errorSpan);
        return true;
    }
    
    function validateImage(input) {
        const file = input.files[0];
        const errorSpan = getErrorSpan(input);
        
        if (!file) return true; // Optional field
        
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!allowedTypes.includes(file.type)) {
            showInputError(input, errorSpan, 'Only JPG, PNG, and GIF images are allowed');
            input.value = '';
            return false;
        }
        
        if (file.size > maxSize) {
            showInputError(input, errorSpan, 'Image size must be less than 5MB');
            input.value = '';
            return false;
        }
        
        showInputSuccess(input, errorSpan);
        return true;
    }
    
    // ============================================
    // HELPER FUNCTIONS
    // ============================================
    
    function getErrorSpan(input) {
        let errorSpan = input.parentElement.querySelector('.error-message');
        if (!errorSpan) {
            errorSpan = document.createElement('span');
            errorSpan.className = 'error-message';
            input.parentElement.appendChild(errorSpan);
        }
        return errorSpan;
    }
    
    function showInputError(input, errorSpan, message) {
        input.classList.add('invalid');
        input.classList.remove('valid');
        errorSpan.textContent = message;
        errorSpan.style.display = 'block';
    }
    
    function showInputSuccess(input, errorSpan) {
        input.classList.remove('invalid');
        input.classList.add('valid');
        errorSpan.textContent = '';
        errorSpan.style.display = 'none';
    }
    
    function showError(message) {
        // Check if alert already exists
        let alert = document.querySelector('.alert-error');
        if (!alert) {
            alert = document.createElement('div');
            alert.className = 'alert alert-error';
            alert.textContent = message;
            
            const form = document.querySelector('form');
            if (form) {
                form.insertBefore(alert, form.firstChild);
                
                // Auto-remove after 5 seconds
                setTimeout(() => {
                    alert.remove();
                }, 5000);
            }
        }
    }
    
    console.log('Validation.js loaded successfully!');
});
