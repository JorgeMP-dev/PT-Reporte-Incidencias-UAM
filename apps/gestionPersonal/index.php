<?php
session_start();
if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Gestion Personal'])){
    header("Location: ../../login.php");
    exit;
}

require ('../../lib/var/varlib.php');
require ('../../'.$_RUTADB);

?>

<!DOCTYPE html>
<?php echo $_ENCABEZADOSHTML ;?>
<?php echo $_DEPENDENCIASLIB ;?>
<link href="css/personal.css" rel="stylesheet">
<script src="gestionPersonal.js"></script>
<body class="d-flex flex-column min-vh-100">
  <?php require('../../nav.php') ;?>
  <main class="flex-fill">
  <div class="container-fluid px-4">
      <div class="page-header text-center py-4">
        <h1>Gestión de Personal</h1>
        <p>Departamento Sistemas</p>
      </div>
      <div class="tabs-selector">
        <button class="tab-btn" id="btnAyudantes">
          <i class="bi bi-people-fill"></i> Ayudantes / Servicio Social
        </button>
        <button class="tab-btn" id="btnProfesores">
          <i class="bi bi-person-badge-fill"></i> Profesores / Administrativos
        </button>
      </div>
      <div class="tabla-card d-none" id="tableHeader">
        <div class="tabla-toolbar">
          <div class="toolbar-fecha">
            <i class="bi bi-calendar3"></i>
            Fecha: <?= date('d/m/Y') ?>
          </div>
          <div class="toolbar-right">
            <button class="btn-añadir" onclick="abrirModalPersonal()">
              <i class="bi bi-person-add"></i> Añadir Personal
            </button>
            <div class="search-wrap">
              <input type="search" id="buscador" placeholder="Buscar...">
              <i class="bi bi-search icon-search"></i>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <div id="cargaTabla"></div>
        </div>
        <div class="tabla-footer">
          <span id="conteoRegistros">Selecciona una categoría</span>
          <span id="labelTipo"></span>
        </div>
      </div>
    </div>
  </main>
  <?php include("modals/modalAñadir.php"); ?>
  <?php include("modals/modalHorario.php"); ?>
  <?php include("modals/modalEditar.php"); ?>
  <?php include("modals/modalBaja.php"); ?>
  <?php require('../../footer.php') ;?> 
</body>
</html>