export function initDropdowns() {
document.addEventListener('click', (e) => {
const toggle = e.target.closest('[data-dropdown-toggle]');
if (toggle) {
const id = toggle.dataset.dropdownToggle;
const menu = document.getElementById(id);
if (menu) menu.classList.toggle('open');
} else {
// close all dropdowns when clicking elsewhere
document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
}
});
}