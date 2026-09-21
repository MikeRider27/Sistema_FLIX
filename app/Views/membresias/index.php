<div class="box">
  <div class="box-header with-border">
    <form class="form-inline" method="get" action="/membresias">
      <select class="form-control" name="estado" onchange="this.form.submit()">
        <option value="">Todos los estados</option>
        <?php foreach ($estados as $s): ?><option value="<?= $s ?>"<?= $estado === $s ? ' selected' : '' ?>><?= e(ucfirst($s)) ?></option><?php endforeach; ?>
      </select>
      <a class="btn btn-primary pull-right" href="/membresias/nueva"><i class="fa fa-plus"></i> Nueva membresía</a>
    </form>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-hover datatable">
      <thead><tr><th>#</th><th>Socio</th><th>Plan</th><th>Inicio</th><th>Fin</th><th>Precio</th><th>Saldo</th><th>Estado</th></tr></thead>
      <tbody>
      <?php foreach ($membresias as $m): ?>
        <tr><td><a href="/membresias/<?= (int) $m['id_membresia'] ?>"><?= (int) $m['id_membresia'] ?></a></td>
            <td><?= e($m['socio']) ?></td><td><?= e($m['plan']) ?></td>
            <td data-order="<?= e($m['fecha_inicio']) ?>"><?= fecha($m['fecha_inicio']) ?></td>
            <td data-order="<?= e($m['fecha_fin']) ?>"><?= fecha($m['fecha_fin']) ?></td>
            <td><?= dinero($m['precio']) ?></td><td><?= dinero($m['saldo']) ?></td><td><?= badge_estado($m['estado_real']) ?></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
