<?php
require '../../lib/var/varlib.php';
require '../../'.$_RUTADB;
require '../../lib/telegram/telegramBot.php';
$token = getenv('TELEGRAM_BOT_TOKEN');
$bot = new TelegramBot($token);
$update = json_decode(file_get_contents("php://input"), true);
// ========================
// MENSAJES NORMALES
// ========================
if (isset($update['message'])) {

    $chatId  = $update['message']['from']['id'];
    $nombre  = $update['message']['from']['first_name'] ?? '';
    $user    = $update['message']['from']['username'] ?? '';
    $texto  = trim($update['message']['text'] ?? '');

    if ($texto === '/start') {
        $bot->sendMessage(
            $chatId,
            "👋 Hola *{$nombre}*\n\n".
            "Este bot te notificará incidencias urgentes.\n".
            "Si eres ayudante, escribe:\n".
            "`/registrar TU_CODIGO`",
            null,
            'Markdown'
        );
        exit;
    }

    if (preg_match('/\/registrar\s+(\S+)/', $texto, $mensaje)) {
        $codigo = $mensaje[1];
        $sql = "SELECT idpersonal FROM personal 
                WHERE codigoUnico = ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            $bot->sendMessage($chatId,
                "❌ Código inválido.");
            exit;
        }

        $row = $res->fetch_assoc();
        $idpersonal = $row['idpersonal'];

        $upd = $conn->prepare(
            "UPDATE personal 
             SET idtelegram = ? 
             WHERE idpersonal = ?"
        );
        $upd->bind_param("ii", $chatId, $idpersonal);
        $upd->execute();

        $bot->sendMessage($chatId,
            "✅ Registro exitoso\n\n".
            "A partir de ahora recibirás notificaciones de incidencias urgentes.");
        exit;
    }
}

// ========================
// CALLBACK DE BOTONES
// ========================
if (isset($update['callback_query'])) {    
    $callback = $update['callback_query'];
    $data = $callback['data'];
    $callbackId = $callback['id'];
    $chatIdUsuario = $callback['from']['id'];
    $chatIdMensaje = $callback['message']['chat']['id'];
    $msgId = $callback['message']['message_id'];
    
    if (strpos($data, 'atender_') === 0) {

        $idincidencia = (int) str_replace('atender_', '', $data);
        $sql = "SELECT i.idpersonal,i.descripcion,a.nombre AS aula,
                p.nombre,p.apellidoP,i.realizoReporte
                FROM incidencia i
                LEFT JOIN personal p ON p.idpersonal = i.idpersonal
                JOIN equipo e ON e.idequipo = i.idequipo
                JOIN aula a ON a.idaula = e.idaula
                WHERE i.idincidencia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idincidencia);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $aula = $row['aula'];
        $realizoReporte = $row['realizoReporte'];
        $problema=$row['descripcion'];
        if (!empty($row['idpersonal'])) {
            $bot->answerCallbackQuery(
                $callbackId,
                "⚠️ Ya fue tomada por otro ayudante"
            );

            $bot->editarMensaje(
                $chatIdMensaje,
                $msgId,
                "⚠️ *Incidencia ya tomada*\n\n".
                "👤 Atendida por: *{$row['nombre']}* *{$row['apellidoP']}*\n".
                "📍 Aula: *{$aula}*\n".
                "🎓 Reporto: *{$realizoReporte}*\n".
                "📝 Problema: *{$problema}*"
            );

            exit;
        }

        $sql = "SELECT idpersonal, nombre FROM personal WHERE idtelegram = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $chatIdUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $idpersonal = $row['idpersonal'];
        $bot->answerCallbackQuery(
            $callbackId,
            "✅ Incidencia asignada a ti"
        );

        $sql = "UPDATE incidencia 
                SET idpersonal = ?, idestado=3
                WHERE idincidencia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idpersonal, $idincidencia);
        $stmt->execute();
        $botonSolucionar = [
            'inline_keyboard' => [
                [
                    [
                        'text' => '🛠 Marcar como solucionada',
                        'callback_data' => "solucionar_{$idincidencia}"
                    ]
                ]
            ]
        ];
        $bot->editarMensaje(
            $chatIdMensaje,
            $msgId,
            "✅ *Incidencia asignada*\n\n".
            "👤 La estás atendiendo tú\n".
            "📍 Aula: *{$aula}*\n".
            "🎓 Reporto: *{$realizoReporte}*\n".
            "📝 Problema: *{$problema}*",
            $botonSolucionar            
        );
        /*Seccion Para Recordatorios Cada 5 min*/        
        $del = $conn->prepare("DELETE FROM notificacionIncidencia WHERE idincidencia = ?");
        $del->bind_param("i", $idincidencia);
        $del->execute();
        /*-----------------------------------------*/
        exit;
    }

    if (strpos($data, 'solucionar_') === 0) {

        $idincidencia = (int) str_replace('solucionar_', '', $data);

        $bot->answerCallbackQuery(
            $callbackId,
            "✍️ Escribe la solución del problema"
        );

        $bot->sendMessage(
            $chatIdUsuario,
            "🛠 *Describe brevemente la solución*\n\n".
            "_Incidencia #{$idincidencia}_",
            [
                'force_reply' => true
            ],
            'Markdown'
        );

        exit;
    }
}

if (isset($update['message']['reply_to_message'])) {
    $textoSolucion = trim($update['message']['text']);
    $mensajeOriginal = $update['message']['reply_to_message']['text'];
    $chatId = $update['message']['from']['id'];    
    if (preg_match('/Incidencia\s+#(\d+)/', $mensajeOriginal, $m)) {
        $idincidencia = (int)$m[1];
        $sql = "UPDATE incidencia
                SET solucion = ?,
                    fechaSolucion = NOW(),
                    idestado = 2
                WHERE idincidencia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $textoSolucion, $idincidencia);
        $stmt->execute();

        $bot->sendMessage(
            $chatId,
            "✅ *Incidencia marcada como solucionada*\n\nGracias por tu apoyo 🙌",
            null,
            'Markdown'
        );
        exit;
    }
}

?>