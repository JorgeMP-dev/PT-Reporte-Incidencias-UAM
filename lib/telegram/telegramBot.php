<?php
class TelegramBot {
    private $token;
    private $apiUrl;
    
    public function __construct($token) {
        $this->token = $token;
        $this->apiUrl = "https://api.telegram.org/bot{$token}/";
    }

    public function enviarIncidenciaUrgente($chatId, $idincidencia, $aula, $equipo, $descripcion,$quienReporto) {
        $mensaje = "🚨 *INCIDENCIA URGENTE*\n\n";
        $mensaje .= "📍 *Aula:* {$aula}\n";
        $mensaje .= "💻 *Equipo:* {$equipo}\n";
        $mensaje .= "🎓 *Reporto:* {$quienReporto}\n";
        $mensaje .= "📝 *Problema:*\n{$descripcion}\n\n";
        $mensaje .= "¿Quieres atender esta incidencia?";
        
        $keyboard = [
            'inline_keyboard' => [
                [
                    [
                        'text' => '✅ Atender',
                        'callback_data' => "atender_{$idincidencia}"
                    ]
                ]
            ]
        ];
        return $this->sendMessage($chatId, $mensaje, $keyboard);
    }
    
    public function editarMensaje($chatId, $messageId, $nuevoTexto,$keyboard = null) {
        $data = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $nuevoTexto,
            'parse_mode' => 'Markdown'
        ];
        
        if ($keyboard) {
            $data['reply_markup'] = json_encode($keyboard);
        }

        return $this->makeRequest('editMessageText', $data);
    }

    public function sendMessage($chatId, $text, $keyboard = null, $parseMode = null) {
        $data = [
            'chat_id' => $chatId,
            'text' => $text
        ];

        if ($parseMode) {
            $data['parse_mode'] = $parseMode;
        }

        if ($keyboard) {
            $data['reply_markup'] = json_encode($keyboard);
        }

        return $this->makeRequest('sendMessage', $data);
    }

    public function answerCallbackQuery($callbackId, $text) {
        return $this->makeRequest('answerCallbackQuery', [
            'callback_query_id' => $callbackId,
            'text' => $text,
            'show_alert' => true
        ]);
    }

    public function notificarAsignacion($chatId, $nombreAyudante, $aula, $equipo) {
        $mensaje = "✅ *Incidencia Asignada*\n\n";
        $mensaje .= "👤 *Atendida por:* {$nombreAyudante}\n";
        $mensaje .= "📍 *Aula:* {$aula}\n";
        $mensaje .= "💻 *Equipo:* {$equipo}";
        
        return $this->sendMessage($chatId, $mensaje);
    }
    
    public function borrarMensaje($chatId, $messageId) {
        return $this->makeRequest('deleteMessage', [
            'chat_id' => $chatId,
            'message_id' => $messageId
        ]);
    }

    private function makeRequest($method, $data) {
        $url = $this->apiUrl . $method;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            error_log("Error cURL: " . curl_error($ch));
        }
        
        curl_close($ch);
        
        $response = json_decode($result, true);

        if (!$response['ok']) {
            error_log("Telegram API Error: " . json_encode($response));
        }
        
        return $response;
    }
}
?>