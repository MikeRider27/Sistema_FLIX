<?php
require 'clases/conexion.php';
$sql="select * from v_usuarios where usu_nick = '".$_REQUEST['usuario']."' "
        . "and usu_clave = md5('".$_REQUEST['pass']."')";
$resultado= consultas::get_datos($sql);

session_start();
if($resultado[0]['id_usu']==null){
    $_SESSION['error']='Usuario o Contraseña Incorrecta';
    header('location:index.php');
}else{
    $_SESSION['id_usu']=$resultado[0]['id_usu'];
    $_SESSION['usu_nick']=$resultado[0]['usu_nick'];
    $_SESSION['usu_foto']='';
    $_SESSION['nombres']=$resultado[0]['fun_nombre'].' '.$resultado[0]['fun_apellido'];
    $_SESSION['id_funcionario']=$resultado[0]['id_funcionario'];
    $_SESSION['cargo']=$resultado[0]['car_des'];
     $_SESSION['id_sucusal']=$resultado[0]['suc_des'];
    $_SESSION['sucursal']=$resultado[0]['suc_des'];
    $_SESSION['id_grupo']=$resultado[0]['id_grupo'];
    $_SESSION['grupo']=$resultado[0]['gru_nombre']; 
    header('location:menu.php');
}
