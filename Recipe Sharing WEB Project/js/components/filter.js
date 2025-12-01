const Filter = {
  init: function(containerSelector = '#filter-sidebar') {
    const container = document.querySelector(containerSelector);
    if (!container) return;
    
    container.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-filter]');
      if (!btn) return;
      
      const filterType = btn.dataset.filterType || 'category';
      const filterValue = btn.dataset.filter;
      
      const url = new URL(window.location.href);
      url.searchParams.set(filterType, filterValue);
      window.location.href = url.toString();
    });
  }
};