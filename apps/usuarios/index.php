<?php
session_start();
if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Usuarios'])){
    header("Location: ../../login.php");
    exit;
}

require ('../../lib/var/varlib.php');
require ('../../'.$_RUTADB);
?>

<!DOCTYPE html>
<?php echo $_ENCABEZADOSHTML ;?>
<?php echo $_DEPENDENCIASLIB ;?>
<link href="css/usuarios.css" rel="stylesheet">
<script src="usuarios.js"></script>
<body class="d-flex flex-column min-vh-100">
  <?php require('../../nav.php') ;?>
  <main class="flex-fill">
    <div class="container-fluid px-4">
      <div class="page-header text-center py-4">
        <h1>Usuarios</h1>
        <p>Departamento Sistemas</p>
      </div>
      <div class="tabla-card">
        <div class="tabla-toolbar">
          <div class="toolbar-fecha">
            <i class="bi bi-calendar3"></i>
            Fecha: <?= date('d/m/Y') ?>
          </div>
          <div class="toolbar-right">
            <button class="btn-añadir" onclick="abrirModalAñadir()">
              <i class="bi bi-person-add"></i> Añadir Usuario
            </button>
            <div class="search-wrap">
              <input type="search" id="buscador" placeholder="Buscar...">
              <i class="bi bi-search icon-search"></i>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <div id="cargaTablaUsuarios"></div>
        </div>
        <div class="tabla-footer">
          <span id="conteoRegistros">Cargando usuarios...</span>
          <span>Gestión de accesos al sistema</span>
        </div>

      </div>

    </div>
  </main>
  <?php include("modals/modalAñadir.php"); ?>
  <?php include("modals/modalEditar.php"); ?>
  <?php include("modals/modalPermisos.php"); ?>
  <?php require('../../footer.php') ;?> 
</body>
</html> 