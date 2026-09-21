<div class="row">
  <div class="col-md-4">
    <div class="box box-success">
      <div class="box-header with-border"><h3 class="box-title">Registrar ingreso</h3></div>
      <form method="post" action="/asistencias"><?= csrf() ?>
        <div class="box-body">
          <div class="form-group"><label for="ci">Cédula del socio</label>
            <input class="form-control input-lg" id="ci" name="ci" autofocus required autocomplete="off" inputmode="numeric"></div>
        </div>
        <div class="box-footer"><button class="btn btn-success btn-block"><i class="fa fa-check"></i> Registrar</button></div>
      </form>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box">
      <div class="box-header with-border">
        <form class="form-inline" method="get" action="/asistencias">
          <input type="date" class="form-control" name="fecha" value="<?= e($fecha) ?>" onchange="this.form.submit()">
          <span class="pull-right"><b><?= count($asistencias) ?></b> ingreso(s)</span>
        </form>
      </div>
      <div class="box-body no-padding"><table class="table table-striped">
        <tr><th>Hora</th><th>Socio</th><th>CI</th></tr>
        <?php foreach ($asistencias as $a): ?>
          <tr><td><?= fecha($a['entrada'], 'H:i') ?></td><td><a href="/socios/<?= (int) $a['id_socio'] ?>"><?= e($a['socio']) ?></a></td><td><?= e($a['ci']) ?></td></tr>
        <?php endforeach; ?>
        <?php if (!$asistencias): ?><tr><td colspan="3" class="text-muted">Sin ingresos ese día.</td></tr><?php endif; ?>
      </table></div>
    </div>
  </div>
</div>
