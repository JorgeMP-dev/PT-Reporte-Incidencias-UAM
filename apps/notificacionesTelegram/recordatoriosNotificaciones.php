<?php
require_once  'C:/xampp/htdocs/ReporteIncidenciasUAM/lib/var/varlib.php';
require_once  'C:/xampp/htdocs/ReporteIncidenciasUAM/lib/db/db.php';
require_once  'C:/xampp/htdocs/ReporteIncidenciasUAM/lib/telegram/telegramBot.php';
$token = "tokenBot";
$bot = new TelegramBot($token);
$sql = "SELECT 
            i.idincidencia,
            i.descripcion,
            i.realizoReporte,
            a.nombre AS aula,
            e.nombre AS equipo
        FROM incidencia i
        JOIN equipo e ON e.idequipo = i.idequipo
        JOIN aula a ON a.idaula = e.idaula
        WHERE i.idpersonal IS NULL
          AND i.idestado = 1
          AND i.ultimoRecordatorio <= NOW() - INTERVAL 5 MINUTE ";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $idincidencia = $row['idincidencia'];
    $stmt = $conn->prepare("SELECT idtelegram, idmensaje, recordatorios 
                            FROM notificacionIncidencia 
                            WHERE idincidencia = ?");
    $stmt->bind_param("i", $idincidencia);
    $stmt->execute();
    $notificaciones = $stmt->get_result();
    while ($notificacion = $notificaciones->fetch_assoc()) {
        $contador = $notificacion['recordatorios'] + 1;
        $nuevoTexto =  "🚨 *INCIDENCIA URGENTE*\n\n";
        $nuevoTexto .= "📍 *Aula:* {$row['aula']}\n";
        $nuevoTexto .= "💻 *Equipo:* {$row['equipo']}\n";
        $nuevoTexto .= "🎓 *Reportó:* {$row['realizoReporte']}\n";
        $nuevoTexto .= "📝 *Problema:*\n{$row['descripcion']}\n\n";
        $nuevoTexto .= "⏰ *Recordatorio #{$contador}\n\n";
        $nuevoTexto .= "¿Quieres atender esta incidencia?";

        $botonSolucionar = [
            'inline_keyboard' => [[
                [
                    'text' => '✅ Atender', 
                    'callback_data' => "atender_{$idincidencia}"
                ]
            ]]
        ];

        $bot->borrarMensaje(
            $notificacion['idtelegram'],
            $notificacion['idmensaje']
        );

        $respuesta = $bot->sendMessage(
            $notificacion['idtelegram'],
            $nuevoTexto,
            $botonSolucionar
        );

        if ($respuesta && $respuesta['ok']) {
            $nuevoMsgId = $respuesta['result']['message_id'];
            $upd = $conn->prepare("UPDATE notificacionIncidencia 
                                   SET recordatorios = ?, idmensaje = ?
                                   WHERE idincidencia = ? AND idtelegram = ?");
            $upd->bind_param("iiii", $contador, $nuevoMsgId, $idincidencia, $notificacion['idtelegram']);
            $upd->execute();
        }
    }
    $upd = $conn->prepare("UPDATE incidencia SET ultimoRecordatorio = NOW() WHERE idincidencia = ?");
    $upd->bind_param("i", $idincidencia);
    $upd->execute();
}
?>
