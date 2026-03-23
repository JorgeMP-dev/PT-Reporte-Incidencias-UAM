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
$id = intval($_POST['idusuarioEditar']);
$usuario = $_POST['usuarioEditar'];
$estado = $_POST['estadoEditar'];
$password = $_POST['passwordEditar'];

if($password != ""){
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE usuario 
            SET usuario=?, contraseña=?, estado=? 
            WHERE idusuario=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $usuario, $hash, $estado, $id);

}else{

    $sql = "UPDATE usuario 
            SET usuario=?, estado=? 
            WHERE idusuario=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $usuario, $estado, $id);
}

if($stmt->execute()){
    echo "ok";
}else{
    echo "Error al actualizar: " . $stmt->error;
}
?>