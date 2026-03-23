<?php
session_start();
if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Gestion Incidencias'])){
    header("Location: ../../login.php");
    exit;
}

require ('../../lib/var/varlib.php');
require ('../../'.$_RUTADB);
$sql = "SELECT idaula, nombre FROM aula ORDER BY nombre";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<?php echo $_ENCABEZADOSHTML ;?>
<?php echo $_DEPENDENCIASLIB ;?>
<link href="css/notificaciones.css" rel="stylesheet">
<script src="consultaIncidencias.js"></script>
<body class="d-flex flex-column min-vh-100">
  <?php require('../../nav.php') ;?>
  <main class="flex-fill">
    <div class="container-fluid px-4">
      <div class="page-header text-center py-4">
        <h1>Gestión de Incidencias</h1>
        <p>Departamento Sistemas</p>
      </div>
      <div class="tabla-card">
        <div class="tabla-toolbar">
          <div class="toolbar-fecha">
            <i class="bi bi-calendar3"></i>
            Fecha: <?= date('d/m/Y') ?>
          </div>
        </div>

        <div class="table-responsive">
          <div id="cargaTablaIncidencias"></div>
        </div>

        <div class="tabla-footer">
          <span id="conteoRegistros">Cargando incidencias...</span>
          <span>Incidencias pendientes de resolución</span>
        </div>

      </div>

    </div>
  </main>
  <?php include("modals/modalSolucion.php"); ?>
  <?php require('../../footer.php') ;?> 
</body>
</html>