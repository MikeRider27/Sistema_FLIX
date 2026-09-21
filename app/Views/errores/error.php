<div class="error-page">
  <h2 class="headline text-red"><?= (int) $codigo ?></h2>
  <div class="error-content">
    <h3><i class="fa fa-warning text-red"></i> <?= e($titulo) ?></h3>
    <?php if ($mensaje): ?><p><?= e($mensaje) ?></p><?php endif; ?>
    <p><a href="/">Volver al inicio</a></p>
  </div>
</div>
