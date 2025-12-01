const Modal = {
  open: function(htmlContent, options = {}) {
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';
    
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
      <button class="modal-close" aria-label="Close">×</button>
      <div class="modal-body"></div>
    `;
    
    const body = modal.querySelector('.modal-body');
    if (typeof htmlContent === 'string') {
      body.innerHTML = htmlContent;
    } else {
      body.appendChild(htmlContent);
    }
    
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    
    // Close handlers
    overlay.querySelector('.modal-close').addEventListener('click', () => this.close(overlay));
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) this.close(overlay);
    });
    
    // ESC key to close
    const escHandler = (e) => {
      if (e.key === 'Escape') {
        this.close(overlay);
        document.removeEventListener('keydown', escHandler);
      }
    };
    document.addEventListener('keydown', escHandler);
    
    return overlay;
  },
  
  close: function(overlay) {
    if (overlay && overlay.parentElement) {
      overlay.remove();
    }
  },
  
  confirm: function(message, onConfirm) {
    const content = `
      <div class="modal-confirm">
        <p>${AppUtils.sanitizeHTML(message)}</p>
        <div class="modal-actions">
          <button class="btn btn-cancel">Cancel</button>
          <button class="btn btn-confirm">Confirm</button>
        </div>
      </div>
    `;
    const overlay = this.open(content);
    
    overlay.querySelector('.btn-cancel').addEventListener('click', () => this.close(overlay));
    overlay.querySelector('.btn-confirm').addEventListener('click', () => {
      if (onConfirm) onConfirm();
      this.close(overlay);
    });
  }
};