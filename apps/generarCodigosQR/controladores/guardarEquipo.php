<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../../login.php");
    exit;
}

if(!isset($_SESSION['permisos']['Generador CodigosQR'])){
    header("Location: ../../../login.php");
    exit;
}

require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;

$nombre = $_POST['nombre'];
$codigo = $_POST['codigo'];
$aula = $_POST['aula'];

$conn->begin_transaction();

try {
    $sql = "INSERT INTO equipo
            (codigoInventario, nombre, idaula)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssi",
        $codigo,
        $nombre,
        $aula,
    );
    $stmt->execute();
    $conn->commit();
    echo "ok";
} catch (Exception $e) {
    $conn->rollback();
    echo "Falló en: " . $e->getMessage();
}

?>