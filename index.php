     <?php
        session_start();
        if($_SESSION){
         session_destroy();
        }
        ?>
<html><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema ZEUS</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/font-awesome-4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="assets/ionicons-2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/css/AdminLTE.min.css">
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="login.php"><b>Sistema </b>FLIX</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
           <?php
                if(!empty($_SESSION['error'])){?>
                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign"> </span>
                    <?php echo $_SESSION['error'];?>
                </div>
                <?php }?>
        <p class="login-box-msg">Inicio de sesión</p>
        <form action="Controlador/acceso.php" method="post" autocomplete="off">
          <div class="form-group has-feedback">
            <input type="text"  name="usuario" class="form-control" placeholder="Usuario" 
                   value=""  onkeyup="javascript:this.value=this.value.toUpperCase();"required="">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="pass" class="form-control" placeholder="Contraseña" required=""
                   minlength: jQuery.validator.format("Debe ingresar {0} caracteres minimo")>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
            <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" name="login" class="btn btn-primary btn-block btn-flat">Entrar</button>
            </div><!-- /.col -->
          </div>
        </form>
     </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
    <!-- jQuery 2.1.4 -->
    <script src="assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="assets/js/bootstrap.min.js"></script>
	 </body></html>