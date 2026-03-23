<?php
session_start();
if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Historial Incidencias'])){
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
<link href="css/historialIncidencias.css" rel="stylesheet">
<script src="historialIncidencias.js"></script>
<body class="d-flex flex-column min-vh-100">
  <?php require('../../nav.php') ;?>
  <main class="flex-fill">
  <div class="container-fluid px-4">
      <div class="page-header text-center py-4">
        <h1>Historial de Incidencias</h1>
        <p>Departamento Sistemas</p>
      </div>
      <div class="tabla-card">
        <div class="tabla-toolbar">
          <div class="toolbar-fecha">
            <i class="bi bi-calendar3"></i>
            Fecha: <?= date('d/m/Y') ?>
          </div>
          <div class="toolbar-right">
            <button class="btn-estadisticas" onclick="abrirEstadisticas()">
              <i class="bi bi-bar-chart-fill"></i> Ver Estadísticas
            </button>
            <select class="aula-select" id="seleccionAula">
              <option value="0">Todas las aulas</option>
              <?php while($aula = $result->fetch_assoc()): ?>
                <option value="<?= $aula['idaula'] ?>">
                  <?= htmlspecialchars($aula['nombre']) ?>
                </option>
              <?php endwhile; ?>
            </select>
            <div class="search-wrap">
              <input type="search" id="buscador" placeholder="Buscar...">
              <i class="bi bi-search icon-search"></i>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <div id="cargaTablaHistorialIncidencias"></div>
        </div>
        <div class="tabla-footer">
          <span id="conteoRegistros">Cargando registros...</span>
          <span>Solo incidencias resueltas</span>
        </div>
      </div>
    </div>
  </main>
  <?php require('../../footer.php') ;?> 
</body>
</html>