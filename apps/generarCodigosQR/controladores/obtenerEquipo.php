<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: ../../../login.php");
    exit;
}

if(!isset($_SESSION['permisos']['Generador CodigosQR'])){
    header("Location: ../../../login.php");
    exit;
}

require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;
header('Content-Type: application/json');
$id = intval($_POST['id']);
$stmt = $conn->prepare("SELECT * FROM equipo WHERE idequipo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
echo json_encode($row);
exit;
?>