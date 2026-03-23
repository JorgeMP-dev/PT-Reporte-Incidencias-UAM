<?php
$token = getenv('TELEGRAM_BOT_TOKEN');
if (!$token) {
    die("Token de Telegram no configurado");
}
$webhookUrl = "https://nonaphoristic-elicia-dynastically.ngrok-free.dev/ReporteIncidenciasUAM/apps/notificacionesTelegram/webhook.php";

$url = "https://api.telegram.org/bot{$token}/setWebhook?url={$webhookUrl}";
$response = file_get_contents($url);
?>
