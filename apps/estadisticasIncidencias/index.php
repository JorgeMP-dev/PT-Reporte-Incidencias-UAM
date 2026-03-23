<?php
session_start();
if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Estadisticas Incidencias'])){
    header("Location: ../../login.php");
    exit;
}

require ('../../lib/var/varlib.php');
require ('../../'.$_RUTADB);

$sql = "SELECT idaula, nombre FROM aula ORDER BY nombre";
$result = $conn->query($sql);
?>

<?php echo $_ENCABEZADOSHTML ;?>
<?php echo $_DEPENDENCIASLIB ;?>
<link href="css/estadisticas.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="estadisticas.js"></script>
<script src="exportarPdf.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<body class="d-flex flex-column min-vh-100">
  <?php require('../../nav.php') ;?>
  <main class="flex-fill">
    <div class="container-fluid px-4">

      <div class="page-header text-center py-4">
        <h1>Estadísticas de Incidencias</h1>
        <p>Departamento Sistemas</p>
      </div>
      <div class="filtros-card">
        <div class="filtro-group">
          <label>Fecha Inicio</label>
          <input type="date" id="fechaInicio">
        </div>
        <div class="filtro-group">
          <label>Fecha Fin</label>
          <input type="date" id="fechaFin">
        </div>
        <button class="btn-generar" onclick="cargarGraficas()" id="btnGenerar">
          <i class="bi bi-bar-chart"></i> Generar
        </button>
        <button class="btn-exportar" id="btnExportar" disabled onclick="exportarPDF()">
          <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
        </button>
      </div>
      <p class="meta-generado">
        <i class="bi bi-calendar-fill"></i>
        Generado el <?= date('d/m/Y H:i') ?>
      </p>
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="stat-card">
            <div class="stat-icon icon-total"><i class="bi bi-clipboard2-pulse"></i></div>
            <div class="stat-label">Total Incidencias</div>
            <div class="stat-value" id="totalIncidencias">0</div>
            <div class="stat-sub" id="totalIncidenciasSpan"></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card">
            <div class="stat-icon icon-aula"><i class="bi bi-building-exclamation"></i></div>
            <div class="stat-label">Aula con más reportes</div>
            <div class="stat-value" id="aulaTopNombre" style="font-size:1.5rem;">—</div>
            <div class="stat-sub" id="aulaTopTotal"></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card">
            <div class="stat-icon icon-personal"><i class="bi bi-people-fill"></i></div>
            <div class="stat-label">Personal Activo</div>
            <div class="stat-value" id="totalPersonal">0</div>
            <div class="stat-sub" id="totalPersonalSpan"></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="chart-card">
            <div class="chart-title">
              <i class="bi bi-bar-chart-fill"></i> Aulas con más incidencias
            </div>
            <div class="chart-canvas-wrapper alto-sm"><canvas id="graficaAulas"></canvas></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="chart-card">
            <div class="chart-title">
              <i class="bi bi-pc-display"></i> Equipos más afectados
              <span class="chart-subtitle">Top 10</span>
            </div>
            <div class="chart-canvas-wrapper alto-md"><canvas id="graficaEquipos"></canvas></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="chart-card">
            <div class="chart-title">
              <i class="bi bi-person-badge"></i> Estadísticas Personal
            </div>
            <div class="chart-canvas-wrapper alto-md"><canvas id="graficaPersonal"></canvas></div>
          </div>
        </div>
      </div>

    </div>
  </main>
    <?php require('../../footer.php') ;?> 
</body>
</html>