    <div class="pagination">
  <?php if ($page > 1): ?>
    <button data-page="<?= $page - 1 ?>">Prev</button>
  <?php endif; ?>

  <?php for ($i = 1; $i <= $total_pages; $i++): ?>
    <button data-page="<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>">
      <?= $i ?>
    </button>
  <?php endfor; ?>

  <?php if ($page < $total_pages): ?>
    <button data-page="<?= $page + 1 ?>">Next</button>
  <?php endif; ?>
</div>
