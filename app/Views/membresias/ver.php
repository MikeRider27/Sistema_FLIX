<div class="row">
  <div class="col-md-5">
    <div class="box box-primary"><div class="box-body">
      <ul class="list-group list-group-unbordered">
        <li class="list-group-item"><b>Socio</b> <span class="pull-right"><a href="/socios/<?= (int) $m['id_socio'] ?>"><?= e($m['socio']) ?></a></span></li>
        <li class="list-group-item"><b>Plan</b> <span class="pull-right"><?= e($m['plan']) ?></span></li>
        <li class="list-group-item"><b>Entrenador</b> <span class="pull-right"><?= e($m['entrenador'] ?? '—') ?></span></li>
        <li class="list-group-item"><b>Período</b> <span class="pull-right"><?= fecha($m['fecha_inicio']) ?> – <?= fecha($m['fecha_fin']) ?></span></li>
        <li class="list-group-item"><b>Estado</b> <span class="pull-right"><?= badge_estado($m['estado_real']) ?></span></li>
        <li class="list-group-item"><b>Precio</b> <span class="pull-right"><?= dinero($m['precio']) ?></span></li>
        <li class="list-group-item"><b>Pagado</b> <span class="pull-right"><?= dinero($m['pagado']) ?></span></li>
        <li class="list-group-item"><b>Saldo</b> <span class="pull-right"><strong><?= dinero($m['saldo']) ?></strong></span></li>
      </ul>
      <?php if ($m['estado'] !== 'cancelada'): ?>
        <?php if ($m['saldo'] > 0): ?><a class="btn btn-success btn-sm" href="/membresias/<?= (int) $m['id_membresia'] ?>/pagar"><i class="fa fa-money"></i> Registrar pago</a><?php endif; ?>
        <?php if (es_admin()): ?>
          <form style="display:inline" method="post" action="/membresias/<?= (int) $m['id_membresia'] ?>/cancelar" data-confirmar="¿Cancelar esta membresía? Los pagos ya cobrados se conservan.">
            <?= csrf() ?><button class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> Cancelar membresía</button></form>
        <?php endif; ?>
      <?php endif; ?>
    </div></div>
  </div>
  <div class="col-md-7">
    <div class="box"><div class="box-header with-border"><h3 class="box-title">Pagos</h3></div>
      <div class="box-body no-padding"><table class="table">
        <tr><th>Fecha</th><th>Método</th><th>Referencia</th><th>Cajero</th><th class="text-right">Monto</th></tr>
        <?php foreach ($pagos as $p): ?>
          <tr><td><?= fecha_hora($p['fecha_pago']) ?></td><td><?= e($p['metodo']) ?></td><td><?= e($p['referencia'] ?? '') ?></td><td><?= e($p['cajero']) ?></td><td class="text-right"><?= dinero($p['monto']) ?></td></tr>
        <?php endforeach; ?>
        <?php if (!$pagos): ?><tr><td colspan="5" class="text-muted">Sin pagos registrados.</td></tr><?php endif; ?>
      </table></div>
    </div>
  </div>
</div>
