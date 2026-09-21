<div class="box">
  <div class="box-header with-border"><a class="btn btn-primary" href="/planes/nuevo"><i class="fa fa-plus"></i> Nuevo plan</a></div>
  <div class="box-body table-responsive">
    <table class="table table-hover">
      <thead><tr><th>Nombre</th><th>Descripción</th><th>Duración</th><th>Precio</th><th>Estado</th><th></th></tr></thead>
      <?php foreach ($planes as $p): ?>
        <tr><td><?= e($p['nombre']) ?></td><td><?= e($p['descripcion'] ?? '') ?></td><td><?= (int) $p['duracion_dias'] ?> días</td>
            <td><?= dinero($p['precio']) ?></td><td><?= badge_estado($p['activo'] ? 'activo' : 'inactivo') ?></td>
            <td class="text-right">
              <a class="btn btn-xs btn-default" href="/planes/<?= (int) $p['id_plan'] ?>/editar"><i class="fa fa-pencil"></i></a>
              <form style="display:inline" method="post" action="/planes/<?= (int) $p['id_plan'] ?>/eliminar" data-confirmar="¿Eliminar el plan?"><?= csrf() ?>
                <button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></form>
            </td></tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
