/**
 * File: search.js
 * Purpose: Search and filter functionality
 * Author: F2 Team Member
 * Date: December 2025
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // SEARCH FUNCTIONALITY
    // ============================================
    
    const searchForm = document.querySelector('.search-form');
    const searchInput = document.querySelector('#search-input');
    
    if (searchForm && searchInput) {
        // Prevent empty searches
        searchForm.addEventListener('submit', function(e) {
            const searchValue = searchInput.value.trim();
            
            if (searchValue.length === 0) {
                e.preventDefault();
                searchInput.focus();
                showSearchError('Please enter a search term');
            } else if (searchValue.length < 2) {
                e.preventDefault();
                showSearchError('Search term must be at least 2 characters');
            }
        });
        
        // Clear search button
        const clearButton = document.createElement('button');
        clearButton.type = 'button';
        clearButton.innerHTML = '×';
        clearButton.className = 'clear-search';
        clearButton.style.cssText = `
            position: absolute;
            right: 140px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-size: 24px;
            color: #7f8c8d;
            cursor: pointer;
            display: none;
        `;
        
        const searchWrapper = searchInput.closest('.search-input-wrapper');
        if (searchWrapper) {
            searchWrapper.style.position = 'relative';
            searchWrapper.appendChild(clearButton);
        }
        
        // Show/hide clear button
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                clearButton.style.display = 'block';
            } else {
                clearButton.style.display = 'none';
            }
        });
        
        // Clear search
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            this.style.display = 'none';
            searchInput.focus();
        });
        
        // Initial check
        if (searchInput.value.length > 0) {
            clearButton.style.display = 'block';
        }
    }
    
    // ============================================
    // CATEGORY FILTER (Live filtering - optional)
    // ============================================
    
    const categoryButtons = document.querySelectorAll('.category-btn');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Remove active class from all buttons
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
        });
    });
    
    // ============================================
    // INSTANT SEARCH (Optional - AJAX)
    // ============================================
    
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            const searchValue = this.value.trim();
            
            // Only search if more than 2 characters
            if (searchValue.length >= 2) {
                searchTimeout = setTimeout(() => {
                    performInstantSearch(searchValue);
                }, 500); // Wait 500ms after user stops typing
            }
        });
    }
    
    function performInstantSearch(query) {
        // This is optional - would require AJAX implementation
        console.log('Instant search for:', query);
        
        // Example implementation (would need PHP backend endpoint):
        /*
        fetch(`search-ajax.php?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data);
            })
            .catch(error => {
                console.error('Search error:', error);
            });
        */
    }
    
    // ============================================
    // SEARCH SUGGESTIONS (Optional)
    // ============================================
    
    if (searchInput) {
        const suggestionsContainer = document.createElement('div');
        suggestionsContainer.className = 'search-suggestions';
        suggestionsContainer.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid var(--border-color);
            border-top: none;
            border-radius: 0 0 var(--radius-md) var(--radius-md);
            box-shadow: var(--shadow-md);
            max-height: 300px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        `;
        
        const searchWrapper = searchInput.closest('.search-input-wrapper');
        if (searchWrapper) {
            searchWrapper.style.position = 'relative';
            searchWrapper.appendChild(suggestionsContainer);
        }
        
        // Popular searches (example data)
        const popularSearches = [
            'Chocolate Cake',
            'Pasta Carbonara',
            'Chicken Curry',
            'Caesar Salad',
            'Beef Stir Fry'
        ];
        
        searchInput.addEventListener('focus', function() {
            if (this.value.length === 0) {
                showPopularSearches();
            }
        });
        
        function showPopularSearches() {
            suggestionsContainer.innerHTML = '<div style="padding: 10px; font-weight: bold; color: var(--text-light);">Popular Searches:</div>';
            
            popularSearches.forEach(search => {
                const suggestionItem = document.createElement('div');
                suggestionItem.className = 'suggestion-item';
                suggestionItem.textContent = search;
                suggestionItem.style.cssText = `
                    padding: 10px 15px;
                    cursor: pointer;
                    transition: var(--transition);
                `;
                
                suggestionItem.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = 'var(--light-color)';
                });
                
                suggestionItem.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'white';
                });
                
                suggestionItem.addEventListener('click', function() {
                    searchInput.value = this.textContent;
                    searchForm.submit();
                });
                
                suggestionsContainer.appendChild(suggestionItem);
            });
            
            suggestionsContainer.style.display = 'block';
        }
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchWrapper.contains(e.target)) {
                suggestionsContainer.style.display = 'none';
            }
        });
    }
    
    // ============================================
    // HELPER FUNCTIONS
    // ============================================
    
    function showSearchError(message) {
        // Remove existing error
        const existingError = document.querySelector('.search-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Create new error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'search-error alert alert-error';
        errorDiv.textContent = message;
        errorDiv.style.marginTop = 'var(--spacing-sm)';
        
        const searchSection = document.querySelector('.search-section');
        if (searchSection) {
            searchSection.appendChild(errorDiv);
            
            // Auto-remove after 3 seconds
            setTimeout(() => {
                errorDiv.remove();
            }, 3000);
        }
    }
    
    console.log('Search.js loaded successfully!');
});
