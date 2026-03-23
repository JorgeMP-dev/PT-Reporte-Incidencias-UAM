<?php
date_default_timezone_set('America/Mexico_City');
$host = "localhost";
$user = "root";
$pass = "";
$db   = "incidencias";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
