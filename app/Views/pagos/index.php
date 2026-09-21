<div class="box">
  <div class="box-header with-border">
    <form class="form-inline" method="get" action="/pagos">
      <label>Desde</label> <input type="date" class="form-control" name="desde" value="<?= e($desde) ?>">
      <label>Hasta</label> <input type="date" class="form-control" name="hasta" value="<?= e($hasta) ?>">
      <button class="btn btn-default"><i class="fa fa-filter"></i> Filtrar</button>
      <span class="pull-right"><b>Total:</b> <?= dinero($total) ?></span>
    </form>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-hover datatable">
      <thead><tr><th>Fecha</th><th>Socio</th><th>Plan</th><th>Método</th><th>Referencia</th><th>Cajero</th><th class="text-right">Monto</th></tr></thead>
      <tbody>
      <?php foreach ($pagos as $p): ?>
        <tr><td data-order="<?= e($p['fecha_pago']) ?>"><?= fecha_hora($p['fecha_pago']) ?></td><td><?= e($p['socio']) ?></td><td><?= e($p['plan']) ?></td>
            <td><?= e($p['metodo']) ?></td><td><?= e($p['referencia'] ?? '') ?></td><td><?= e($p['cajero']) ?></td>
            <td class="text-right" data-order="<?= e($p['monto']) ?>"><?= dinero($p['monto']) ?></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
