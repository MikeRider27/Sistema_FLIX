<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= e($titulo) ?> · <?= e(App\Core\Config::get('app.nombre')) ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('font-awesome-4.4.0/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/AdminLTE.min.css') ?>">
</head>
<body class="hold-transition login-page">
<?= $contenido ?>
<script src="<?= asset('plugins/jQuery/jQuery-2.1.4.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>
</body>
</html>
