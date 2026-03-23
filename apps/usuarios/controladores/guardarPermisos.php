<?php
session_start();
if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Usuarios'])){
    header("Location: ../../../login.php");
    exit;
}

require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;

$data = json_decode(file_get_contents("php://input"), true);

$idusuario = $data['idusuario'];
$permisos = $data['permisos'];

$sql = "DELETE FROM permiso WHERE idusuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idusuario);
$stmt->execute();

foreach($permisos as $permiso){
    $sql=" INSERT INTO permiso (idusuario, idmodulo, idtipoPermiso)
           VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii",
        $idusuario,
        $permiso['idmodulo'],
        $permiso['idtipoPermiso']
    );
    $stmt->execute();
}
echo "ok";
?>