<div class="box box-primary">
  <form method="post" action="/membresias"><?= csrf() ?>
    <div class="box-body"><div class="row">
      <?= selector('id_socio', 'Socio', $socios, ['valor' => $valores['id_socio'], 'vacio' => '— Elegí un socio —', 'requerido' => true, 'col' => 6]) ?>
      <?= selector('id_plan', 'Plan', $planes, ['valor' => '', 'vacio' => '— Elegí un plan —', 'requerido' => true, 'col' => 6]) ?>
      <?= selector('id_entrenador', 'Entrenador (opcional)', $entrenadores, ['valor' => '', 'vacio' => 'Sin entrenador asignado', 'col' => 6]) ?>
      <?= campo('fecha_inicio', 'Fecha de inicio', ['tipo' => 'date', 'valor' => $valores['fecha_inicio'], 'requerido' => true, 'col' => 6]) ?>
      <div class="col-md-12"><h4>Pago inicial (opcional)</h4></div>
      <?= campo('monto', 'Monto', ['tipo' => 'number', 'min' => 0, 'step' => '0.01', 'col' => 4]) ?>
      <?= selector('metodo', 'Método', ['efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta', 'transferencia' => 'Transferencia'], ['valor' => $valores['metodo'], 'col' => 4]) ?>
      <?= campo('referencia', 'Referencia', ['col' => 4, 'maxlength' => 100]) ?>
    </div>
    <p class="text-muted">La fecha de fin y el precio se calculan a partir del plan elegido.</p></div>
    <div class="box-footer"><button class="btn btn-primary"><i class="fa fa-save"></i> Registrar</button> <a class="btn btn-default" href="/membresias">Cancelar</a></div>
  </form>
</div>
