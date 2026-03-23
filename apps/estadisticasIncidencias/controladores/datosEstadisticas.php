<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../../login.php");
    exit;
}

if(!isset($_SESSION['permisos']['Estadisticas Incidencias'])){
    header("Location: ../../../login.php");
    exit;
}

require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;

header('Content-Type: application/json');

$inicio = $_POST['inicio'] ?? '';
$fin    = $_POST['fin'] ?? '';

if (!$inicio || !$fin) {
    echo json_encode(["error" => "Fechas inválidas"]);
    exit;
}

$inicio .= " 00:00:00";
$fin    .= " 23:59:59";


$sqlAulas = "SELECT a.nombre, COUNT(*) AS total
            FROM incidencia i
            INNER JOIN equipo e ON e.idequipo = i.idequipo
            INNER JOIN aula a ON a.idaula = e.idaula
            WHERE i.idestado = 2
            AND i.fechaReporte BETWEEN ? AND ?
            GROUP BY a.nombre
            ORDER BY total DESC";

$stmt = $conn->prepare($sqlAulas);
$stmt->bind_param("ss", $inicio, $fin);
$stmt->execute();
$resAulas = $stmt->get_result();

$aulas = [];
while ($row = $resAulas->fetch_assoc()) {
    $aulas[] = $row;
}

$sqlEquipos = "SELECT CONCAT(a.nombre, ' › ', e.nombre) AS nombre, COUNT(*) AS total
              FROM incidencia i
              INNER JOIN equipo e ON e.idequipo = i.idequipo
              INNER JOIN aula a   ON a.idaula   = e.idaula
              WHERE i.idestado = 2
              AND i.fechaReporte BETWEEN ? AND ?
              GROUP BY e.idequipo, e.nombre, a.nombre
              ORDER BY total DESC
              LIMIT 10";

$stmt = $conn->prepare($sqlEquipos);
$stmt->bind_param("ss", $inicio, $fin);
$stmt->execute();
$resEquipos = $stmt->get_result();

$equipos = [];
while ($row = $resEquipos->fetch_assoc()) {
    $equipos[] = $row;
}

$sqlPersonal = "SELECT CONCAT(p.nombre,' ',p.apellidoP) AS nombre,
               COUNT(*) AS total
               FROM incidencia i
               INNER JOIN personal p ON p.idpersonal = i.idpersonal
               WHERE i.idestado = 2
               AND i.fechaSolucion BETWEEN ? AND ?
               GROUP BY p.idpersonal
               ORDER BY total DESC";

$stmt = $conn->prepare($sqlPersonal);
$stmt->bind_param("ss", $inicio, $fin);
$stmt->execute();
$resPersonal = $stmt->get_result();

$personal = [];
while ($row = $resPersonal->fetch_assoc()) {
    $personal[] = $row;
}

echo json_encode([
    "aulas"    => $aulas,
    "equipos"  => $equipos,
    "personal" => $personal
]);