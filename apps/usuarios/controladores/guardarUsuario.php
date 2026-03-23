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

$idpersonal = intval($_POST['idpersonal']);
$usuario = trim($_POST['usuario']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$estado = $_POST['estado'];

$sql = "INSERT INTO usuario (idpersonal, usuario, contraseña, estado)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $idpersonal, $usuario, $password, $estado);

if($stmt->execute()){
    echo "ok";
}else{
    echo "Error al guardar";
}
?>