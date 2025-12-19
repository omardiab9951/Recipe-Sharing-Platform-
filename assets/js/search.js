// Live search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    
    if (searchInput) {
        // Search suggestions (optional enhancement)
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            
            if (query.length > 2) {
                // Highlight matching text in results
                const recipeCards = document.querySelectorAll('.recipe-card');
                recipeCards.forEach(card => {
                    const title = card.querySelector('.recipe-title');
                    const description = card.querySelector('.recipe-description');
                    
                    if (title || description) {
                        const titleText = title ? title.textContent.toLowerCase() : '';
                        const descText = description ? description.textContent.toLowerCase() : '';
                        
                        if (titleText.includes(query) || descText.includes(query)) {
                            card.style.display = 'block';
                            card.style.animation = 'fadeIn 0.3s';
                        }
                    }
                });
            }
        });
        
        // Clear search button
        const clearBtn = document.createElement('button');
        clearBtn.type = 'button';
        clearBtn.innerHTML = '✕';
        clearBtn.style.cssText = `
            position: absolute;
            right: 90px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-size: 20px;
            color: #6b7280;
            cursor: pointer;
            display: none;
        `;
        
        const searchForm = searchInput.closest('.search-form');
        if (searchForm) {
            searchForm.style.position = 'relative';
            searchForm.appendChild(clearBtn);
            
            searchInput.addEventListener('input', function() {
                clearBtn.style.display = this.value ? 'block' : 'none';
            });
            
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                clearBtn.style.display = 'none';
                searchInput.focus();
            });
        }
    }
});

// CSS animation for fadeIn
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);
