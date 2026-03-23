<?php
session_start();
if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Gestion Personal'])){
    header("Location: ../../../login.php");
    exit;
}

require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;
header('Content-Type: application/json');
$tipoTabla = $_POST['tipoTabla'];
if($tipoTabla == "ayudantes"){
    $sql = "SELECT * FROM tipopersonal WHERE idtipoPersonal IN (1,2 )ORDER BY idtipopersonal DESC";
}
else{
    $sql = "SELECT * FROM tipopersonal WHERE idtipoPersonal NOT IN (1,2 )ORDER BY idtipopersonal DESC";
}
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
    $datos[] = $row;
}
echo json_encode($datos);
exit;
?>