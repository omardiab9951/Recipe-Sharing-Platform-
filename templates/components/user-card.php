<div class="user-card">
  <img src="<?= $user['avatar'] ?: '/assets/images/default-avatar.png' ?>"
       alt="Avatar"
       class="user-avatar">

  <h3><?= htmlspecialchars($user['name']) ?></h3>
  <p><?= htmlspecialchars($user['email']) ?></p>
</div>
