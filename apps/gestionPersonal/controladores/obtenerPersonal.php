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
$id = intval($_POST['id']);
$sql ="SELECT * FROM personal WHERE idpersonal = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
echo json_encode($row);
exit;
?>