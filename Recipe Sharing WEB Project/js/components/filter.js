export function initFilters(containerSelector = '#filter-sidebar') {
const container = document.querySelector(containerSelector);
if (!container) return;


// delegate clicks on category buttons/links
container.addEventListener('click', (e) => {
const btn = e.target.closest('[data-filter]');
if (!btn) return;
const val = btn.dataset.filter;
const url = new URL(window.location.href);
url.searchParams.set('category', val);
window.location.href = url.toString();
});
}