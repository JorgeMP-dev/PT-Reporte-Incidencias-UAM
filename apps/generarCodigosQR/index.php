<?php
session_start();
if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Generador CodigosQR'])){
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
<link href="css/codigosQR.css" rel="stylesheet">
<link href="css/impresion.css" rel="stylesheet">
<script src="inventarioEquipos.js"></script>
<body class="d-flex flex-column min-vh-100">
  <?php require('../../nav.php') ;?>
  <div class="container-fluid px-4">
      <div class="page-header text-center py-4">
        <h1>Generador Códigos QR</h1>
        <p>Departamento Sistemas</p>
      </div>
      <div class="tabla-card">
        <div class="tabla-toolbar">
          <div class="toolbar-fecha">
            <i class="bi bi-calendar3"></i>Fecha: <?= date('d/m/Y') ?>
          </div>
          <div class="toolbar-right">
            <button class="btn-añadir" onclick="abrirModalAñadir()">
              <i class="bi bi-pc-display"></i> Añadir Equipo
            </button>
            <button class="btn-qr" onclick="imprimirQRAula()">
              <i class="bi bi-qr-code"></i> Generar QR's del Aula
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
          <div id="cargaTablaEquipos"></div>
        </div>

        <div class="tabla-footer">
          <span id="conteoRegistros">Cargando equipos...</span>
          <span>Inventario de equipos con QR</span>
        </div>
      </div>
    </div>
  <?php include("modals/modalQR.php"); ?>
  <?php include("modals/modalAñadir.php"); ?>
  <?php include("modals/modalEditar.php"); ?>
  <?php require('../../footer.php') ;?> 
</body>
</html> 