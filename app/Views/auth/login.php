<?php use App\Core\Flash; ?>
<div class="login-box">
  <div class="login-logo"><b>Sistema</b> FLIX</div>
  <div class="login-box-body">
    <?php if ($e = Flash::error('general')): ?>
      <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle"></i> <?= e($e) ?></div>
    <?php endif; ?>
    <p class="login-box-msg">Inicio de sesión</p>
    <form action="/login" method="post" autocomplete="off">
      <?= csrf() ?>
      <div class="form-group has-feedback">
        <input type="text" name="usuario" class="form-control" placeholder="Usuario" value="<?= e(Flash::old('usuario', '')) ?>" required autofocus>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="clave" class="form-control" placeholder="Contraseña" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row"><div class="col-xs-4 col-xs-offset-8">
        <button type="submit" class="btn btn-primary btn-block btn-flat">Entrar</button>
      </div></div>
    </form>
  </div>
</div>
