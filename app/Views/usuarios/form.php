<?php $nuevo = !isset($usuario['id_usuario']); ?>
<div class="box box-primary">
  <form method="post" action="<?= e($accion) ?>" autocomplete="off"><?= csrf() ?>
    <div class="box-body"><div class="row">
      <?= campo('nombre', 'Nombre completo', ['valor' => $usuario['nombre'] ?? '', 'requerido' => true, 'col' => 6, 'maxlength' => 100]) ?>
      <?php if ($nuevo): ?>
        <?= campo('usuario', 'Usuario', ['valor' => '', 'requerido' => true, 'col' => 6, 'maxlength' => 50]) ?>
      <?php else: ?>
        <div class="col-md-6"><div class="form-group"><label>Usuario</label><input class="form-control" value="<?= e($usuario['usuario']) ?>" disabled></div></div>
      <?php endif; ?>
      <?= selector('rol', 'Rol', ['recepcion' => 'Recepción', 'admin' => 'Administrador'], ['valor' => $usuario['rol'] ?? 'recepcion', 'requerido' => true, 'col' => 6]) ?>
      <?= campo('clave', $nuevo ? 'Contraseña (mín. 8)' : 'Nueva contraseña (vacío = no cambiar)', ['tipo' => 'password', 'requerido' => $nuevo, 'col' => 6, 'extra' => 'autocomplete="new-password"']) ?>
      <?= casilla('activo', 'Usuario activo', (bool) ($usuario['activo'] ?? true)) ?>
    </div></div>
    <div class="box-footer"><button class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button> <a class="btn btn-default" href="/usuarios">Cancelar</a></div>
  </form>
</div>
