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


require ('../../../lib/var/varlib.php');
require ('../../../'.$_RUTADB);

$id = intval($_POST['id']);
$nombre = $_POST['nombre'] ;
$apellidoP = $_POST['apellidoP'] ;
$apellidoM = $_POST['apellidoM'] ;
$correo = $_POST['correo'] ;
$telefono = $_POST['telefono'] ;
$numeroEconomico = $_POST['numeroEconomico'] ;
$rol = intval($_POST['rol']);
$codigoUnico = $_POST['codigoUnico'] ;
if($codigoUnico == ""){
    $codigoUnico=NULL;
}
if($telefono == ""){
    $telefono=NULL;
}

$sql="UPDATE personal SET
    nombre = ?,
    apellidoP = ?,
    apellidoM = ?,
    correo = ?,
    telefono = ?,
    numeroEconomico = ?,
    idtipoPersonal = ?,
    codigoUnico = ?
    WHERE idpersonal = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssisi",
    $nombre,
    $apellidoP,
    $apellidoM,
    $correo,
    $telefono,
    $numeroEconomico,
    $rol,
    $codigoUnico,
    $id
);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "Error al actualizar: " . $stmt->error;
}
?>
