<form id="comment-form" class="comment-form" method="post" action="/api/comments/add-comment.php">
  <input type="hidden" name="recipe_id" value="<?= $recipe['id'] ?>">

  <textarea
    name="text"
    rows="3"
    placeholder="Write a comment..."
    required></textarea>

  <button type="submit" class="btn-primary">Post Comment</button>
</form>
