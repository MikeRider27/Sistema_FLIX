<div class="box box-primary">
  <form method="post" action="/membresias/<?= (int) $m['id_membresia'] ?>/pagar"><?= csrf() ?>
    <div class="box-body">
      <p><b><?= e($m['socio']) ?></b> — <?= e($m['plan']) ?> (<?= fecha($m['fecha_inicio']) ?> – <?= fecha($m['fecha_fin']) ?>)<br>
         Precio <?= dinero($m['precio']) ?> · Pagado <?= dinero($m['pagado']) ?> · <b>Saldo <?= dinero($m['saldo']) ?></b></p>
      <div class="row">
        <?= campo('monto', 'Monto', ['tipo' => 'number', 'min' => 0.01, 'step' => '0.01', 'max' => $m['saldo'], 'valor' => $m['saldo'], 'requerido' => true, 'col' => 4]) ?>
        <?= selector('metodo', 'Método', ['efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta', 'transferencia' => 'Transferencia'], ['valor' => 'efectivo', 'requerido' => true, 'col' => 4]) ?>
        <?= campo('referencia', 'Referencia', ['col' => 4, 'maxlength' => 100]) ?>
      </div>
    </div>
    <div class="box-footer"><button class="btn btn-primary"><i class="fa fa-save"></i> Registrar pago</button> <a class="btn btn-default" href="/membresias/<?= (int) $m['id_membresia'] ?>">Cancelar</a></div>
  </form>
</div>
