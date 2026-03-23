<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../../login.php");
    exit;
}

if(!isset($_SESSION['permisos']['Gestion Personal'])){
    header("Location: ../../../login.php");
    exit;
}

require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;

$idpersonal = (int) $_POST['idpersonal'];
$sql = "SELECT diaSemana, horaInicio, horaFin
        FROM horario
        WHERE idpersonal = ?
        ORDER BY FIELD(diaSemana,
            'lunes','martes','miercoles','jueves','viernes'
        ), horaInicio";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idpersonal);
$stmt->execute();
$result = $stmt->get_result();
$tabla = "";

while ($row = $result->fetch_assoc()) {
    $tabla .= "
        <tr>
        <td>{$row['diaSemana']}</td>
        <td>{$row['horaInicio']}</td>
        <td>{$row['horaFin']}</td>
        </tr>";
}
?>  
<table class="table table-hover table-bordered table-striped table-sm align-middle text-center w-100">
    <thead class="table-light">
    <tr>
    <th>Dia</th>
    <th>Hora Inicio</th>
    <th>Hora Fin</th>
    </tr>
    </thead>
    <tbody>
    <?= $tabla ?>
    </tbody>
</table>