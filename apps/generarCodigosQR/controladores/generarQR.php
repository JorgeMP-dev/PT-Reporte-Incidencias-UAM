<?php
session_start();
if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Generador CodigosQR'])){
    header("Location: ../../../login.php");
    exit;
}

require ('../../../lib/var/varlib.php');
require ('../../../'.$_RUTADB);
require ('../../../lib/phpqrcode/qrlib.php');

$texto = $_GET['data'] ;
QRcode::png($texto, false,QR_ECLEVEL_Q,12);
?>
