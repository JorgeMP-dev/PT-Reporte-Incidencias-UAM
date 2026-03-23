<?php
require '../../lib/var/varlib.php';
require '../../'.$_RUTADB;

$idequipo = $_GET['equipo'] ?? null;

if (!$idequipo) {
  die("Es necesario escanear un QR");
}

$sql = "SELECT 
          e.nombre AS equipo,
          a.nombre AS aula
        FROM equipo e
        INNER JOIN aula a ON a.idaula = e.idaula
        WHERE e.idequipo = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idequipo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  die("Equipo no encontrado");
}
$datos = $result->fetch_assoc();
$aula   = $datos['aula'];
$equipo = $datos['equipo'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../../lib/css/bootstrap.min.css" rel="stylesheet">
<title>Departamento Sistemas</title>
</head>
<body class="bg-dark text-light">
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6">

      <div class="card shadow-lg border-0">
        <div class="card-body p-4">

          <div class="text-center mb-4">
            <img src="../../img/sistemasLogo.webp" class="img-fluid mb-3" style="max-width:160px;">
            <h5 class="fw-bold">Reporte de Incidencias</h5>
          </div>

          <div class="mb-3">
            <strong>Aula:</strong> <?= htmlspecialchars($aula) ?><br>
            <strong>Equipo:</strong> <?= htmlspecialchars($equipo) ?>
          </div>

          <form method="POST" action="guardarIncidencias.php">
            <input type="hidden" name="idequipo" value="<?= $idequipo ?>">

            <div class="mb-3">
              <strong>Número Económico / Matrícula</strong>
              <input type="text" id="numeroEconomicoIncidencia" name="numeroEconomicoIncidencia" class="form-control" required>
            </div>

            <div class="mb-4">
              <strong >Descripción del problema</strong>
              <textarea name="descripcion" class="form-control" rows="4"
                placeholder="Describe el problema..." required></textarea>
            </div>

            <button class="btn btn-primary w-100 py-2 fw-semibold" id="btnEnviarReporte">
              Enviar reporte
            </button>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function(){
    const form = document.querySelector("form");
    const boton = document.getElementById("btnEnviarReporte");
    form.addEventListener("submit", function(){      
      boton.disabled = true;
      boton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';
    });
  });
</script>
</body>
</html>