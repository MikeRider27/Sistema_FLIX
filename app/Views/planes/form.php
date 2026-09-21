<div class="box box-primary">
  <form method="post" action="<?= e($accion) ?>"><?= csrf() ?>
    <div class="box-body"><div class="row">
      <?= campo('nombre', 'Nombre', ['valor' => $plan['nombre'] ?? '', 'requerido' => true, 'col' => 6, 'maxlength' => 80]) ?>
      <?= campo('duracion_dias', 'Duración (días)', ['tipo' => 'number', 'min' => 1, 'valor' => $plan['duracion_dias'] ?? '', 'requerido' => true, 'col' => 3]) ?>
      <?= campo('precio', 'Precio', ['tipo' => 'number', 'min' => 0, 'step' => '0.01', 'valor' => $plan['precio'] ?? '', 'requerido' => true, 'col' => 3]) ?>
      <?= campo('descripcion', 'Descripción', ['valor' => $plan['descripcion'] ?? '', 'col' => 12, 'maxlength' => 250]) ?>
      <?= casilla('activo', 'Plan activo (disponible para nuevas membresías)', (bool) ($plan['activo'] ?? true)) ?>
    </div></div>
    <div class="box-footer"><button class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button> <a class="btn btn-default" href="/planes">Cancelar</a></div>
  </form>
</div>
