const Comments = {
  init: function(formSelector = '#comment-form', listSelector = '#comments-list') {
    const form = document.querySelector(formSelector);
    const list = document.querySelector(listSelector);
    if (!form || !list) return;
    
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const textarea = form.querySelector('textarea[name="comment"]');
      if (!textarea || !Validation.isRequired(textarea.value)) {
        Toast.error('Please enter a comment');
        return;
      }
      
      const fd = new FormData(form);
      
      try {
        const res = await AppAPI.post('comments/add-comment.php', fd);
        
        if (res && res.success) {
          this.addCommentToDOM(list, res.comment);
          form.reset();
          Toast.success('Comment posted');
        } else {
          Toast.error(res.message || 'Failed to post comment');
        }
      } catch (err) {
        Toast.error('Network error');
        console.error(err);
      }
    });
    
    // Delete comment
    list.addEventListener('click', async (e) => {
      const deleteBtn = e.target.closest('.delete-comment');
      if (!deleteBtn) return;
      
      const commentId = deleteBtn.dataset.commentId;
      Modal.confirm('Delete this comment?', async () => {
        try {
          const res = await AppAPI.post('comments/delete-comment.php', { id: commentId });
          if (res && res.success) {
            deleteBtn.closest('.comment-item').remove();
            Toast.success('Comment deleted');
          } else {
            Toast.error(res.message || 'Failed to delete');
          }
        } catch (err) {
          Toast.error('Network error');
        }
      });
    });
  },
  
  addCommentToDOM: function(list, comment) {
    const item = document.createElement('div');
    item.className = 'comment-item';
    item.dataset.commentId = comment.id;
    
    const author = document.createElement('strong');
    author.textContent = comment.author || 'Anonymous';
    
    const text = document.createElement('p');
    text.textContent = comment.text;
    
    const meta = document.createElement('small');
    meta.className = 'comment-meta';
    meta.textContent = comment.created_at || 'Just now';
    
    item.appendChild(author);
    item.appendChild(text);
    item.appendChild(meta);
    
    if (comment.can_delete) {
      const deleteBtn = document.createElement('button');
      deleteBtn.className = 'delete-comment btn-sm';
      deleteBtn.dataset.commentId = comment.id;
      deleteBtn.textContent = 'Delete';
      item.appendChild(deleteBtn);
    }
    
    list.prepend(item);
  }
};