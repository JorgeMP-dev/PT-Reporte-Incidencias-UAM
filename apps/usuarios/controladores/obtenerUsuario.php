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

$id = intval($_POST['id']);

$sql = "SELECT 
            u.idusuario,
            u.usuario,
            u.estado,
            p.nombre,
            p.apellidoP
        FROM usuario u
        INNER JOIN personal p ON p.idpersonal = u.idpersonal
        WHERE u.idusuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
echo json_encode($result->fetch_assoc());
 ?>