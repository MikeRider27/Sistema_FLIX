<div class="box box-primary">
  <form method="post" action="<?= e($accion) ?>"><?= csrf() ?>
    <div class="box-body"><div class="row">
      <?= campo('ci', 'Cédula', ['valor' => $entrenador['ci'] ?? '', 'requerido' => true, 'col' => 4, 'maxlength' => 20]) ?>
      <?= campo('nombre', 'Nombre', ['valor' => $entrenador['nombre'] ?? '', 'requerido' => true, 'col' => 4, 'maxlength' => 80]) ?>
      <?= campo('apellido', 'Apellido', ['valor' => $entrenador['apellido'] ?? '', 'requerido' => true, 'col' => 4, 'maxlength' => 80]) ?>
      <?= campo('telefono', 'Teléfono', ['valor' => $entrenador['telefono'] ?? '', 'col' => 4, 'maxlength' => 30]) ?>
      <?= campo('email', 'Correo', ['tipo' => 'email', 'valor' => $entrenador['email'] ?? '', 'col' => 4, 'maxlength' => 120]) ?>
      <?= campo('especialidad', 'Especialidad', ['valor' => $entrenador['especialidad'] ?? '', 'col' => 4, 'maxlength' => 100]) ?>
      <?= casilla('activo', 'Entrenador activo', (bool) ($entrenador['activo'] ?? true)) ?>
    </div></div>
    <div class="box-footer"><button class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button> <a class="btn btn-default" href="/entrenadores">Cancelar</a></div>
  </form>
</div>
