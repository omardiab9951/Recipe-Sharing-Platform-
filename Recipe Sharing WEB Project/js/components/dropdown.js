const Dropdown = {
  init: function() {
    document.addEventListener('click', (e) => {
      const toggle = e.target.closest('[data-dropdown-toggle]');
      if (toggle) {
        e.stopPropagation();
        const id = toggle.dataset.dropdownToggle;
        const menu = document.getElementById(id);
        if (menu) {

            document.querySelectorAll('.dropdown.open').forEach(d => {
            if (d.id !== id) d.classList.remove('open');
          });
          menu.classList.toggle('open');
        }
      } else {

        document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
      }
    });
  }
};
