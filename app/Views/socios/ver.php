<div class="row">
  <div class="col-md-4">
    <div class="box box-primary">
      <div class="box-body">
        <h3 class="profile-username text-center"><?= e($socio['nombre'] . ' ' . $socio['apellido']) ?></h3>
        <p class="text-center"><?= badge_estado($socio['estado']) ?></p>
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item"><b>Cédula</b> <span class="pull-right"><?= e($socio['ci']) ?></span></li>
          <li class="list-group-item"><b>Nacimiento</b> <span class="pull-right"><?= fecha($socio['fecha_nacimiento']) ?></span></li>
          <li class="list-group-item"><b>Teléfono</b> <span class="pull-right"><?= e($socio['telefono'] ?? '—') ?></span></li>
          <li class="list-group-item"><b>Correo</b> <span class="pull-right"><?= e($socio['email'] ?? '—') ?></span></li>
          <li class="list-group-item"><b>Dirección</b> <span class="pull-right"><?= e($socio['direccion'] ?? '—') ?></span></li>
          <li class="list-group-item"><b>Emergencia</b> <span class="pull-right"><?= e($socio['contacto_emergencia'] ?? '—') ?></span></li>
        </ul>
        <?php if ($socio['notas']): ?><p><b>Notas:</b><br><?= nl2br(e($socio['notas'])) ?></p><?php endif; ?>
        <a class="btn btn-default btn-sm" href="/socios/<?= (int) $socio['id_socio'] ?>/editar"><i class="fa fa-pencil"></i> Editar</a>
        <?php if (es_admin()): ?>
          <form class="inline" style="display:inline" method="post" action="/socios/<?= (int) $socio['id_socio'] ?>/eliminar" data-confirmar="¿Eliminar definitivamente a este socio?">
            <?= csrf() ?><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Eliminar</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box">
      <div class="box-header with-border"><h3 class="box-title">Membresías</h3>
        <div class="box-tools"><a class="btn btn-xs btn-success" href="/membresias/nueva?socio=<?= (int) $socio['id_socio'] ?>"><i class="fa fa-plus"></i> Nueva / renovar</a></div></div>
      <div class="box-body no-padding"><table class="table">
        <tr><th>Plan</th><th>Período</th><th>Saldo</th><th>Estado</th></tr>
        <?php foreach ($membresias as $m): ?>
          <tr><td><a href="/membresias/<?= (int) $m['id_membresia'] ?>"><?= e($m['plan']) ?></a></td>
              <td><?= fecha($m['fecha_inicio']) ?> – <?= fecha($m['fecha_fin']) ?></td>
              <td><?= dinero($m['saldo']) ?></td><td><?= badge_estado($m['estado_real']) ?></td></tr>
        <?php endforeach; ?>
        <?php if (!$membresias): ?><tr><td colspan="4" class="text-muted">Sin membresías.</td></tr><?php endif; ?>
      </table></div>
    </div>
    <div class="row">
      <div class="col-md-6"><div class="box">
        <div class="box-header with-border"><h3 class="box-title">Pagos</h3></div>
        <div class="box-body no-padding"><table class="table">
          <?php foreach ($pagos as $p): ?>
            <tr><td><?= fecha($p['fecha_pago']) ?></td><td><?= e($p['plan']) ?></td><td class="text-right"><?= dinero($p['monto']) ?></td></tr>
          <?php endforeach; ?>
          <?php if (!$pagos): ?><tr><td class="text-muted">Sin pagos.</td></tr><?php endif; ?>
        </table></div>
      </div></div>
      <div class="col-md-6"><div class="box">
        <div class="box-header with-border"><h3 class="box-title">Últimas asistencias</h3></div>
        <div class="box-body no-padding"><table class="table">
          <?php foreach ($asistencias as $a): ?><tr><td><?= fecha_hora($a['entrada']) ?></td></tr><?php endforeach; ?>
          <?php if (!$asistencias): ?><tr><td class="text-muted">Sin asistencias.</td></tr><?php endif; ?>
        </table></div>
      </div></div>
    </div>
  </div>
</div>
