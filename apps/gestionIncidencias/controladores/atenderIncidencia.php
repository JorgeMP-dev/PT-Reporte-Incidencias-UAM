<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../../login.php");
    exit;
}

if(!isset($_SESSION['permisos']['Gestion Incidencias'])){
    header("Location: ../../../login.php");
    exit;
}

require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;
$idIncidencia = $_POST['id'];
$idPersonal = $_SESSION['id_personal'];
$sql = "SELECT idpersonal 
                FROM incidencia 
                WHERE idincidencia = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idIncidencia);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
if (!empty($row['idpersonal'])) {
    echo "alerta";    
}else{    
    $del = $conn->prepare("DELETE FROM notificacionIncidencia WHERE idincidencia = ?");
    $del->bind_param("i", $idIncidencia);
    $del->execute();
    $sql = "UPDATE incidencia 
            SET idestado = 3,
                idpersonal = ?
            WHERE idincidencia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idPersonal, $idIncidencia);    
    if ($stmt->execute()) {
        echo "ok";
    } else {
        echo "error";
    }
}
?>