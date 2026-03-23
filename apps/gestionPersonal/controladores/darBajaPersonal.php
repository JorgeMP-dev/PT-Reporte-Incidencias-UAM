<?php
session_start();
require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;

if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Gestion Personal'])){
    header("Location: ../../../login.php");
    exit;
}


$idpersonal = $_POST['idpersonal'];
$motivo     = $_POST['motivo'];

$conn->begin_transaction();

try {
    $sql="UPDATE personal SET estado='INACTIVO' WHERE idpersonal=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idpersonal);
    $stmt->execute();

    $sql = "INSERT INTO bajasPersonal
            (idpersonal, motivo) 
            VALUES (?,?)";
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param("is", $idpersonal, $motivo);
    $stmt2->execute();

    $conn->commit();
    echo "ok";
} catch (Exception $e) {
    $conn->rollback();
    echo "error";
}
?> 