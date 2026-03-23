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

$id = intval($_POST['id']);
$nombre = $_POST['nombreEditar'] ;
$aula = $_POST['seleccionAulaEditar'] ;
$codigo = $_POST['codigoEditar'] ;

$sql="UPDATE equipo SET
    nombre = ?,
    idaula = ?,
    codigoInventario = ?
    WHERE idequipo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sisi",
    $nombre,
    $aula,
    $codigo,
    $id
);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "Error al actualizar: " . $stmt->error;
}
?>
