export function openModal(htmlContent, opts = {}) {
const overlay = document.createElement('div');
overlay.className = 'modal-overlay';
const modal = document.createElement('div');
modal.className = 'modal';
modal.innerHTML = `<button class="modal-close" aria-label="Close">×</button><div class="modal-body"></div>`;
modal.querySelector('.modal-body').appendChild(typeof htmlContent === 'string' ? document.createRange().createContextualFragment(htmlContent) : htmlContent);
overlay.appendChild(modal);
document.body.appendChild(overlay);
overlay.querySelector('.modal-close').addEventListener('click', () => overlay.remove());
overlay.addEventListener('click', (e) => { if (e.target === overlay) overlay.remove(); });
return overlay;
}