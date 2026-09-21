<div class="box">
  <div class="box-header with-border">
    <form class="form-inline" method="get" action="/socios">
      <input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Cédula o nombre">
      <select class="form-control" name="estado">
        <option value="">Todos</option>
        <option value="activo"<?= $estado === 'activo' ? ' selected' : '' ?>>Activos</option>
        <option value="inactivo"<?= $estado === 'inactivo' ? ' selected' : '' ?>>Inactivos</option>
      </select>
      <button class="btn btn-default"><i class="fa fa-search"></i> Buscar</button>
      <a class="btn btn-primary pull-right" href="/socios/nuevo"><i class="fa fa-plus"></i> Nuevo socio</a>
    </form>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-hover datatable">
      <thead><tr><th>CI</th><th>Apellido y nombre</th><th>Teléfono</th><th>Membresía hasta</th><th>Estado</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($socios as $s): ?>
        <tr>
          <td><?= e($s['ci']) ?></td>
          <td><a href="/socios/<?= (int) $s['id_socio'] ?>"><?= e($s['apellido'] . ', ' . $s['nombre']) ?></a></td>
          <td><?= e($s['telefono'] ?? '—') ?></td>
          <td data-order="<?= e($s['vence'] ?? '') ?>"><?= fecha($s['vence']) ?></td>
          <td><?= badge_estado($s['estado']) ?></td>
          <td class="text-right"><a class="btn btn-xs btn-default" href="/socios/<?= (int) $s['id_socio'] ?>/editar"><i class="fa fa-pencil"></i></a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
