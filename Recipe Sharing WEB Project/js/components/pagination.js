const Pagination = {
  init: function(containerSelector = '.pagination') {
    const container = document.querySelector(containerSelector);
    if (!container) return;
    
    container.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-page]');
      if (!btn) return;
      
      e.preventDefault();
      const page = btn.dataset.page;
      const url = new URL(window.location.href);
      url.searchParams.set('page', page);
      window.location.href = url.toString();
    });
  }
};
