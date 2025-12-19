/**
 * File: recipe.js
 * Purpose: Recipe-specific interactions
 * Author: F2 Team Member
 * Date: December 2025
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // IMAGE PREVIEW ON UPLOAD
    // ============================================
    
    const imageInput = document.querySelector('input[type="file"][name="image"]');
    
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    // Remove existing preview
                    const existingPreview = document.querySelector('.image-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    
                    // Create preview
                    const previewContainer = document.createElement('div');
                    previewContainer.className = 'image-preview';
                    previewContainer.style.cssText = `
                        margin-top: var(--spacing-sm);
                        position: relative;
                        max-width: 300px;
                    `;
                    
                    const previewImage = document.createElement('img');
                    previewImage.src = event.target.result;
                    previewImage.style.cssText = `
                        width: 100%;
                        height: auto;
                        border-radius: var(--radius-md);
                        box-shadow: var(--shadow-md);
                    `;
                    
                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.innerHTML = '×';
                    removeButton.style.cssText = `
                        position: absolute;
                        top: 10px;
                        right: 10px;
                        background-color: var(--danger-color);
                        color: white;
                        border: none;
                        width: 30px;
                        height: 30px;
                        border-radius: 50%;
                        cursor: pointer;
                        font-size: 20px;
                        line-height: 1;
                    `;
                    
                    removeButton.addEventListener('click', function() {
                        imageInput.value = '';
                        previewContainer.remove();
                    });
                    
                    previewContainer.appendChild(previewImage);
                    previewContainer.appendChild(removeButton);
                    
                    imageInput.parentElement.appendChild(previewContainer);
                };
                
                reader.readAsDataURL(file);
            }
        });
    }
    
    // ============================================
    // CHARACTER COUNTER FOR TEXTAREAS
    // ============================================
    
    const textareas = document.querySelectorAll('textarea');
    
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');
        
        if (!maxLength) {
            // Add character counter
            const counter = document.createElement('div');
            counter.className = 'character-counter';
            counter.style.cssText = `
                text-align: right;
                font-size: 0.875rem;
                color: var(--text-light);
                margin-top: var(--spacing-xs);
            `;
            
            updateCounter();
            
            textarea.addEventListener('input', updateCounter);
            
            textarea.parentElement.appendChild(counter);
            
            function updateCounter() {
                const length = textarea.value.length;
                counter.textContent = `${length} characters`;
                
                if (length > 1000) {
                    counter.style.color = 'var(--danger-color)';
                } else if (length > 800) {
                    counter.style.color = 'var(--warning-color)';
                } else {
                    counter.style.color = 'var(--text-light)';
                }
            }
        }
    });
    
    // ============================================
    // INGREDIENT LIST FORMATTER
    // ============================================
    
    const ingredientsTextarea = document.querySelector('#ingredients');
    
    if (ingredientsTextarea) {
        ingredientsTextarea.addEventListener('blur', function() {
            // Auto-format ingredients list
            let lines = this.value.split('\n');
            lines = lines.map(line => {
                line = line.trim();
                if (line && !line.startsWith('-') && !line.startsWith('•')) {
                    return '• ' + line;
                }
                return line;
            });
            this.value = lines.join('\n');
        });
    }
    
    // ============================================
    // INSTRUCTIONS NUMBERING
    // ============================================
    
    const instructionsTextarea = document.querySelector('#instructions');
    
    if (instructionsTextarea) {
        // Add button to auto-number instructions
        const numberButton = document.createElement('button');
        numberButton.type = 'button';
        numberButton.textContent = 'Auto-Number Steps';
        numberButton.className = 'btn btn-secondary';
        numberButton.style.marginTop = 'var(--spacing-xs)';
        
        numberButton.addEventListener('click', function() {
            let lines = instructionsTextarea.value.split('\n');
            let stepNumber = 1;
            
            lines = lines.map(line => {
                line = line.trim();
                if (line) {
                    // Remove existing numbers
                    line = line.replace(/^\d+\.\s*/, '');
                    return stepNumber++ + '. ' + line;
                }
                return line;
            });
            
            instructionsTextarea.value = lines.join('\n');
        });
        
        instructionsTextarea.parentElement.appendChild(numberButton);
    }
    
    // ============================================
    // COMMENT FUNCTIONALITY
    // ============================================
    
    const commentTextarea = document.querySelector('textarea[name="comment_text"]');
    
    if (commentTextarea) {
        // Character limit for comments
        const maxCommentLength = 1000;
        
        const commentCounter = document.createElement('div');
        commentCounter.style.cssText = `
            text-align: right;
            font-size: 0.875rem;
            color: var(--text-light);
            margin-top: var(--spacing-xs);
        `;
        
        updateCommentCounter();
        
        commentTextarea.addEventListener('input', function() {
            if (this.value.length > maxCommentLength) {
                this.value = this.value.substring(0, maxCommentLength);
            }
            updateCommentCounter();
        });
        
        commentTextarea.parentElement.appendChild(commentCounter);
        
        function updateCommentCounter() {
            const remaining = maxCommentLength - commentTextarea.value.length;
            commentCounter.textContent = `${remaining} characters remaining`;
            
            if (remaining < 100) {
                commentCounter.style.color = 'var(--warning-color)';
            } else {
                commentCounter.style.color = 'var(--text-light)';
            }
        }
    }
    
    // ============================================
    // RECIPE CARD ANIMATIONS
    // ============================================
    
    const recipeCards = document.querySelectorAll('.recipe-card');
    
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    recipeCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.5s ease ${index * 0.1}s`;
        observer.observe(card);
    });
    
    // ============================================
    // PRINT RECIPE BUTTON
    // ============================================
    
    const recipeDetail = document.querySelector('.recipe-detail');
    
    if (recipeDetail) {
        const printButton = document.createElement('button');
        printButton.type = 'button';
        printButton.textContent = '🖨️ Print Recipe';
        printButton.className = 'btn btn-secondary';
        printButton.style.marginLeft = 'var(--spacing-sm)';
        
        printButton.addEventListener('click', function() {
            window.print();
        });
        
        const recipeActions = document.querySelector('.recipe-actions');
        if (recipeActions) {
            recipeActions.appendChild(printButton);
        }
    }
    
    // ============================================
    // SHARE RECIPE BUTTON (Optional)
    // ============================================
    
    if (recipeDetail && navigator.share) {
        const shareButton = document.createElement('button');
        shareButton.type = 'button';
        shareButton.textContent = '📤 Share Recipe';
        shareButton.className = 'btn btn-secondary';
        shareButton.style.marginLeft = 'var(--spacing-sm)';
        
        shareButton.addEventListener('click', async function() {
            try {
                await navigator.share({
                    title: document.title,
                    url: window.location.href
                });
            } catch (err) {
                console.log('Error sharing:', err);
            }
        });
        
        const recipeActions = document.querySelector('.recipe-actions');
        if (recipeActions) {
            recipeActions.appendChild(shareButton);
        }
    }
    
    console.log('Recipe.js loaded successfully!');
});
