<div class="box">
  <div class="box-header with-border"><a class="btn btn-primary" href="/entrenadores/nuevo"><i class="fa fa-plus"></i> Nuevo entrenador</a></div>
  <div class="box-body table-responsive">
    <table class="table table-hover">
      <thead><tr><th>CI</th><th>Nombre</th><th>Especialidad</th><th>Contacto</th><th>Alumnos</th><th>Estado</th><th></th></tr></thead>
      <?php foreach ($entrenadores as $t): ?>
        <tr><td><?= e($t['ci']) ?></td><td><?= e($t['apellido'] . ', ' . $t['nombre']) ?></td><td><?= e($t['especialidad'] ?? '—') ?></td>
            <td><?= e($t['telefono'] ?? '') ?> <?= e($t['email'] ?? '') ?></td><td><?= (int) $t['alumnos'] ?></td>
            <td><?= badge_estado($t['activo'] ? 'activo' : 'inactivo') ?></td>
            <td class="text-right">
              <a class="btn btn-xs btn-default" href="/entrenadores/<?= (int) $t['id_entrenador'] ?>/editar"><i class="fa fa-pencil"></i></a>
              <form style="display:inline" method="post" action="/entrenadores/<?= (int) $t['id_entrenador'] ?>/eliminar" data-confirmar="¿Eliminar al entrenador?"><?= csrf() ?>
                <button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></form>
            </td></tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
