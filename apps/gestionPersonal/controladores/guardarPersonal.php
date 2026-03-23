<?php
require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;
session_start();
if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Gestion Personal'])){
    header("Location: ../../../login.php");
    exit;
}

$nombre = $_POST['nombre'];
$apellidoM = $_POST['apellidoM'];
$apellidoP = $_POST['apellidoP'];
$rol = (int)$_POST['rol'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$numeroEconomico = $_POST['numeroEconomico'];
$codigoUnico = $_POST['codigoUnico'];
$horarios = $_POST['horarios'] ?? [];

if($codigoUnico == ""){
    $codigoUnico=NULL;
}
if($telefono == ""){
    $telefono=NULL;
}

$conn->begin_transaction();

try {
    $sql = "INSERT INTO personal
            (nombre, apellidoM, apellidoP, numeroEconomico, correo, telefono, codigoUnico, idtipoPersonal)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssi",
        $nombre,
        $apellidoM,
        $apellidoP,
        $numeroEconomico,
        $correo,
        $telefono,
        $codigoUnico,
        $rol
    );
    $stmt->execute();

    $idpersonal = $stmt->insert_id;

    if(!empty($horarios)){
        $sqlHorario = "INSERT INTO horario
                       (idpersonal, diaSemana, horaInicio, horaFin)
                       VALUES (?, ?, ?, ?)";

        $stmtHorario = $conn->prepare($sqlHorario);

        foreach($horarios as $h){
            $stmtHorario->bind_param(
                "isss",
                $idpersonal,
                $h['dia'],
                $h['inicio'],
                $h['fin']
            );
            $stmtHorario->execute();
        }
    }
    $conn->commit();
    echo "ok";
} catch (Exception $e) {
    $conn->rollback();
    echo "Falló en: " . $e->getMessage();
}

?>