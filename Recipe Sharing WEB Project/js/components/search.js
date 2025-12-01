const Search = {
  init: function(inputSelector = '#search-input', formSelector = '#search-form') {
    const input = document.querySelector(inputSelector);
    const form = document.querySelector(formSelector);
    if (!input || !form) return;
    

    input.addEventListener('input', AppUtils.debounce(async (e) => {
      const query = e.target.value.trim();
      if (query.length < 2) return;
      
       try {
         const results = await AppAPI.get(`search.php?q=${encodeURIComponent(query)}`);
         this.showSuggestions(results);
       } catch (err) {
         console.error('Search error:', err);
      }
    }, 350));
    
    form.addEventListener('submit', (e) => {
      if (!input.value.trim()) {
        e.preventDefault();
        Toast.show('Please enter a search term');
      }
    });
  },
  
  showSuggestions: function(results) {
    console.log('Search suggestions:', results);
  }
};