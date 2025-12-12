<article class="recipe-card" data-id="<?= $r['id'] ?>">
  <img class="recipe-img"
       src="<?= $r['image'] ?: '/assets/images/default-recipe.jpg' ?>"
       alt="<?= htmlspecialchars($r['title']) ?>">

  <div class="card-body">
    <h3 class="card-title"><?= htmlspecialchars($r['title']) ?></h3>

    <p class="card-desc">
      <?= htmlspecialchars(substr($r['ingredients'], 0, 80)) ?>...
    </p>

    <div class="card-meta">
      <span class="card-category"><?= htmlspecialchars($r['category']) ?></span>
      <a class="btn-link" href="/recipe-detail.php?id=<?= $r['id'] ?>">View</a>
    </div>
  </div>
</article>
