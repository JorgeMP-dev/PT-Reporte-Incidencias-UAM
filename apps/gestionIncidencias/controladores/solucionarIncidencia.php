<?php
session_start();
require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;

if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Gestion Incidencias'])){
    header("Location: ../../../login.php");
    exit;
}

$idIncidencia = intval($_POST['id']);
$solucion = trim($_POST['solucion']);
$idPersonal = $_SESSION['id_personal'];

$sql = "UPDATE incidencia 
        SET solucion = ?, 
            idestado = 2,
            fechaSolucion = CURDATE()
        WHERE idincidencia = ?
        AND idpersonal = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $solucion, $idIncidencia, $idPersonal);

if($stmt->execute()){
    echo "ok";
}else{
    echo "error";
}
?>