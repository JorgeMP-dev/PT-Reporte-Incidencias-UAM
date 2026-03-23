<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cargando...</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            background-color: #000;
            overflow: hidden;
        }

        .loading-container {
            width: 100%;
            height: 100%;
            background-color: #000;
        }

        video {
            max-width: 450px;
            width: 60vw;
            height: auto;
            object-fit: contain;
            background: transparent;
            border: none;
            outline: none;
        }
    </style>
</head>

<body>
    <div class="loading-container d-flex justify-content-center align-items-center">
        <video id="loadingVideo" autoplay muted playsinline>
            <source src="video/cargando.mp4" type="video/mp4">
        </video>
    </div>

    <script>
        const video = document.getElementById("loadingVideo");
        video.playbackRate = 2;
        video.addEventListener("ended", () => {
            window.location.href = "index.php";
        });
    </script>
</body>
</html>