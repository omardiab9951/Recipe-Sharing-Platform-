<?php

/**
 * File: comment-section.php
 * Purpose: Reusable comment section component
 * Author: B2 Team Member
 * Date: December 2025
 * Note: This is optional - already integrated in recipe-detail.php
 */

// This component expects these variables:
// $recipe_id, $comments (array), $is_logged_in, $csrf_token

?>

<div class="comments-section">
  <h2>Comments (<?php echo count($comments); ?>)</h2>

  <!-- Comment Form -->
  <?php if ($is_logged_in): ?>
    <form method="POST" class="comment-form">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

      <div class="form-group">
        <textarea
          name="comment_text"
          rows="3"
          placeholder="Add your comment..."
          required></textarea>
      </div>

      <button type="submit" name="submit_comment" class="btn btn-primary">Post Comment</button>
    </form>
  <?php else: ?>
    <p class="login-prompt">
      <a href="login.php">Login</a> to leave a comment
    </p>
  <?php endif; ?>

  <!-- Comments List -->
  <div class="comments-list">
    <?php if (empty($comments)): ?>
      <p class="no-comments">No comments yet. Be the first to comment!</p>
    <?php else: ?>
      <?php foreach ($comments as $comment): ?>
        <div class="comment">
          <div class="comment-header">
            <strong><?php echo htmlspecialchars($comment['author_name']); ?></strong>
            <span class="comment-date">
              <?php echo date('F j, Y \a\t g:i A', strtotime($comment['created_at'])); ?>
            </span>
          </div>
          <div class="comment-body">
            <?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>