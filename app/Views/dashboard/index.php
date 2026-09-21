<div class="row">
  <?php foreach ([
    ['aqua',   'fa-users',        $sociosActivos,        'Socios activos'],
    ['green',  'fa-id-card-o',    $vigentes,             'Membresías vigentes'],
    ['yellow', 'fa-check-square-o', $asistenciasHoy,     'Ingresos de hoy'],
    ['red',    'fa-money',        dinero($ingresosMes),  'Ingresos del mes'],
  ] as [$color, $icono, $valor, $texto]): ?>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-<?= $color ?>"><i class="fa <?= $icono ?>"></i></span>
        <div class="info-box-content"><span class="info-box-text"><?= e($texto) ?></span><span class="info-box-number"><?= e($valor) ?></span></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="box box-warning">
      <div class="box-header with-border"><h3 class="box-title">Vencen en los próximos 7 días</h3></div>
      <div class="box-body no-padding">
        <table class="table table-striped">
          <?php foreach ($porVencer as $m): ?>
            <tr><td><a href="/socios/<?= (int) $m['id_socio'] ?>"><?= e($m['socio']) ?></a> <small class="text-muted"><?= e($m['plan']) ?></small></td>
                <td class="text-right"><?= fecha($m['fecha_fin']) ?> <a class="btn btn-xs btn-success" href="/membresias/nueva?socio=<?= (int) $m['id_socio'] ?>">Renovar</a></td></tr>
          <?php endforeach; ?>
          <?php if (!$porVencer): ?><tr><td class="text-muted">Nada por vencer.</td></tr><?php endif; ?>
        </table>
      </div>
    </div>
    <div class="box box-danger">
      <div class="box-header with-border"><h3 class="box-title">Saldos pendientes</h3></div>
      <div class="box-body no-padding">
        <table class="table table-striped">
          <?php foreach ($conSaldo as $m): ?>
            <tr><td><a href="/membresias/<?= (int) $m['id_membresia'] ?>"><?= e($m['socio']) ?></a> <small class="text-muted"><?= e($m['plan']) ?></small></td>
                <td class="text-right"><?= dinero($m['saldo']) ?> <a class="btn btn-xs btn-primary" href="/membresias/<?= (int) $m['id_membresia'] ?>/pagar">Cobrar</a></td></tr>
          <?php endforeach; ?>
          <?php if (!$conSaldo): ?><tr><td class="text-muted">Sin deudas.</td></tr><?php endif; ?>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="box box-info">
      <div class="box-header with-border"><h3 class="box-title">Últimos ingresos</h3>
        <div class="box-tools"><a href="/asistencias" class="btn btn-xs btn-info">Registrar ingreso</a></div></div>
      <div class="box-body no-padding">
        <table class="table table-striped">
          <?php foreach ($ultimas as $a): ?>
            <tr><td><?= e($a['socio']) ?> <small class="text-muted"><?= e($a['ci']) ?></small></td><td class="text-right"><?= fecha_hora($a['entrada']) ?></td></tr>
          <?php endforeach; ?>
          <?php if (!$ultimas): ?><tr><td class="text-muted">Sin ingresos todavía.</td></tr><?php endif; ?>
        </table>
      </div>
    </div>
  </div>
</div>
