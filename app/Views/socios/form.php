<div class="box box-primary">
  <form method="post" action="<?= e($accion) ?>">
    <?= csrf() ?>
    <div class="box-body"><div class="row">
      <?= campo('ci', 'Cédula', ['valor' => $socio['ci'] ?? '', 'requerido' => true, 'col' => 4, 'maxlength' => 20]) ?>
      <?= campo('nombre', 'Nombre', ['valor' => $socio['nombre'] ?? '', 'requerido' => true, 'col' => 4, 'maxlength' => 80]) ?>
      <?= campo('apellido', 'Apellido', ['valor' => $socio['apellido'] ?? '', 'requerido' => true, 'col' => 4, 'maxlength' => 80]) ?>
      <?= campo('fecha_nacimiento', 'Fecha de nacimiento', ['tipo' => 'date', 'valor' => $socio['fecha_nacimiento'] ?? '', 'col' => 4]) ?>
      <?= selector('sexo', 'Sexo', ['M' => 'Masculino', 'F' => 'Femenino', 'O' => 'Otro'], ['valor' => $socio['sexo'] ?? '', 'vacio' => '—', 'col' => 4]) ?>
      <?= selector('estado', 'Estado', ['activo' => 'Activo', 'inactivo' => 'Inactivo'], ['valor' => $socio['estado'] ?? 'activo', 'requerido' => true, 'col' => 4]) ?>
      <?= campo('telefono', 'Teléfono', ['valor' => $socio['telefono'] ?? '', 'col' => 4, 'maxlength' => 30]) ?>
      <?= campo('email', 'Correo', ['tipo' => 'email', 'valor' => $socio['email'] ?? '', 'col' => 8, 'maxlength' => 120]) ?>
      <?= campo('direccion', 'Dirección', ['valor' => $socio['direccion'] ?? '', 'col' => 6, 'maxlength' => 200]) ?>
      <?= campo('contacto_emergencia', 'Contacto de emergencia', ['valor' => $socio['contacto_emergencia'] ?? '', 'col' => 6, 'maxlength' => 150]) ?>
      <?= area('notas', 'Notas (salud, lesiones, objetivos)', ['valor' => $socio['notas'] ?? '']) ?>
    </div></div>
    <div class="box-footer">
      <button class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
      <a class="btn btn-default" href="/socios">Cancelar</a>
    </div>
  </form>
</div>
