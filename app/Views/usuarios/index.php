<div class="box">
  <div class="box-header with-border"><a class="btn btn-primary" href="/usuarios/nuevo"><i class="fa fa-plus"></i> Nuevo usuario</a></div>
  <div class="box-body table-responsive">
    <table class="table table-hover">
      <thead><tr><th>Nombre</th><th>Usuario</th><th>Rol</th><th>Estado</th><th>Creado</th><th></th></tr></thead>
      <?php foreach ($usuarios as $u): ?>
        <tr><td><?= e($u['nombre']) ?></td><td><?= e($u['usuario']) ?></td><td><?= e($u['rol']) ?></td>
            <td><?= badge_estado($u['activo'] ? 'activo' : 'inactivo') ?></td><td><?= fecha($u['creado_en']) ?></td>
            <td class="text-right"><a class="btn btn-xs btn-default" href="/usuarios/<?= (int) $u['id_usuario'] ?>/editar"><i class="fa fa-pencil"></i></a></td></tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
