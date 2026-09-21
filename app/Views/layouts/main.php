<?php
use App\Core\Auth;
use App\Core\Config;
use App\Core\Flash;

$usuario = Auth::usuario();
$flash = Flash::mensaje();
$items = [
    ['/',            'fa-dashboard',  'Panel',        false],
    ['/socios',      'fa-users',      'Socios',       false],
    ['/membresias',  'fa-id-card-o',  'Membresías',   false],
    ['/pagos',       'fa-money',      'Pagos',        false],
    ['/asistencias', 'fa-check-square-o', 'Asistencias', false],
    ['/planes',      'fa-tags',       'Planes',       true],
    ['/entrenadores','fa-heartbeat',  'Entrenadores', true],
    ['/usuarios',    'fa-lock',       'Usuarios',     true],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= e($titulo) ?> · <?= e(Config::get('app.nombre')) ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('font-awesome-4.4.0/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/AdminLTE.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/skins/skin-blue.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('plugins/datatables/dataTables.bootstrap.css') ?>">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="/" class="logo"><span class="logo-mini"><b>F</b>X</span><span class="logo-lg"><b>Sistema</b> FLIX</span></a>
    <nav class="navbar navbar-static-top" role="navigation">
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"><span class="sr-only">Menú</span></a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="navbar-text" style="color:#fff;padding:0 15px">
            <i class="fa fa-user"></i> <?= e($usuario['nombre']) ?> <small>(<?= e($usuario['rol']) ?>)</small>
          </li>
          <li>
            <form action="/logout" method="post" style="margin:0"><?= csrf() ?>
              <button type="submit" class="btn btn-link" style="color:#fff;padding:15px"><i class="fa fa-sign-out"></i> Salir</button>
            </form>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu">
        <li class="header">NAVEGACIÓN</li>
        <?php foreach ($items as [$ruta, $icono, $texto, $soloAdmin]): ?>
          <?php if ($soloAdmin && !es_admin()) continue; ?>
          <li class="<?= menu_activo($ruta) ?>"><a href="<?= $ruta ?>"><i class="fa <?= $icono ?>"></i> <span><?= e($texto) ?></span></a></li>
        <?php endforeach; ?>
      </ul>
    </section>
  </aside>

  <div class="content-wrapper">
    <section class="content-header"><h1><?= e($titulo) ?></h1></section>
    <section class="content">
      <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['tipo']) ?> alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
          <?= e($flash['texto']) ?>
        </div>
      <?php endif; ?>
      <?= $contenido ?>
    </section>
  </div>

  <footer class="main-footer"><strong><?= e(Config::get('app.nombre')) ?></strong> &middot; gestión de gimnasio</footer>
</div>

<script src="<?= asset('plugins/jQuery/jQuery-2.1.4.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>
<script src="<?= asset('plugins/slimScroll/jquery.slimscroll.min.js') ?>"></script>
<script src="<?= asset('plugins/fastclick/fastclick.min.js') ?>"></script>
<script src="<?= asset('js/adminlte/js/app.min.js') ?>"></script>
<script src="<?= asset('plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= asset('plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>
<script>
$(function () {
  $('table.datatable').DataTable({
    language: {
      search: 'Buscar:', lengthMenu: 'Mostrar _MENU_', info: 'Mostrando _START_ a _END_ de _TOTAL_',
      infoEmpty: 'Sin registros', zeroRecords: 'Sin resultados', emptyTable: 'Sin datos',
      paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
    },
    order: []
  });
  // Confirmación para acciones destructivas
  $('form[data-confirmar]').on('submit', function () { return confirm($(this).data('confirmar')); });
});
</script>
</body>
</html>
