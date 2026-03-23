<?php
session_start();
require '../../lib/var/varlib.php';
require '../../' . $_RUTADB;
require ('../../lib/telegram/TelegramBot.php');

if (isset($_SESSION['reporte_enviado']) && $_SESSION['reporte_enviado'] == $_POST['idequipo']) {
    echo "
    <script>
        alert('Con un reporte es suficiente.\\nNuestro equipo ya fue notificado.\\nGracias.');
        window.location.href = 'reporteEnviado.php';
    </script>
    ";
    exit;
}

if (
    empty($_POST['idequipo']) ||
    empty($_POST['numeroEconomicoIncidencia']) ||
    empty($_POST['descripcion'])
) {
    die("Datos incompletos");
}
$idequipo = intval($_POST['idequipo']);
$numeroEconomico  = trim($_POST['numeroEconomicoIncidencia']);
$descripcion = trim($_POST['descripcion']);
$idestado = 1;
$fecha = date('Y-m-d');
$quienReporto="Alumno";
$idtipoIncidencia=2;
$ultimoRecordatorio = date('Y-m-d H:i:s');
$sql = "SELECT nombre,apellidoM,apellidoP FROM personal         
        WHERE numeroEconomico = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $numeroEconomico);
$stmt->execute();
$res = $stmt->get_result();
$datos=$res->fetch_assoc();
if ($res->num_rows > 0) {
    $idtipoIncidencia = 1; 
    $quienReporto = $datos['nombre']." ".$datos['apellidoP']." ".$datos['apellidoM'];
}

$sql = "INSERT INTO incidencia 
        (descripcion, fechaReporte, idequipo, idestado, idtipoIncidencia,realizoReporte,ultimoRecordatorio)
        VALUES (?, ?, ?, ?, ?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssiiiss",
    $descripcion,
    $fecha,
    $idequipo,
    $idestado,
    $idtipoIncidencia,
    $quienReporto,
    $ultimoRecordatorio
);

if (!$stmt->execute()) {
    die("Error al guardar la incidencia");
}

$idincidencia = $stmt->insert_id;
$sql = "SELECT 
            e.nombre AS equipo,
            a.nombre AS aula
        FROM equipo e
        INNER JOIN aula a ON a.idaula = e.idaula
        WHERE e.idequipo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idequipo);
$stmt->execute();
$datos = $stmt->get_result()->fetch_assoc();
$aula   = $datos['aula'];
$equipo = $datos['equipo'];
if ($idtipoIncidencia === 1) {
    $token = getenv('TELEGRAM_BOT_TOKEN');
    $bot = new TelegramBot($token);
    $sql = "SELECT idtelegram 
            FROM personal 
            WHERE idtelegram IS NOT NULL";


    /* CONSULTA PARA TRAER SOLO PERSONAL EN TURNO*/        
    /*$sql = "SELECT p.idtelegram 
            FROM personal p
            INNER JOIN horario h ON h.idpersonal = p.idpersonal
            WHERE p.idtelegram IS NOT NULL
            AND LOWER(
                CASE DAYOFWEEK(CURDATE())
                    WHEN 1 THEN 'domingo'
                    WHEN 2 THEN 'lunes'
                    WHEN 3 THEN 'martes'
                    WHEN 4 THEN 'miercoles'
                    WHEN 5 THEN 'jueves'
                    WHEN 6 THEN 'viernes'
                    WHEN 7 THEN 'sabado'
                END
            ) = h.diaSemana
            AND CURTIME() BETWEEN h.horaInicio AND h.horaFin";
    */


    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $respuestaBot=$bot->enviarIncidenciaUrgente(
            $row['idtelegram'],
            $idincidencia,
            $aula,
            $equipo,
            $descripcion,
            $quienReporto
        );
        /*Seccion Para Recordatorios Cada 5 min*/
        $messageId = $respuestaBot['result']['message_id'];
        $sql="INSERT INTO notificacionIncidencia 
              (idincidencia, idtelegram, idmensaje) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $idincidencia, $row['idtelegram'], $messageId);
        $stmt->execute();
        /*-----------------------------------------*/
    }
}
$_SESSION['reporte_enviado'] = $idequipo;
header("Location: reporteEnviado.php");
exit;
?>