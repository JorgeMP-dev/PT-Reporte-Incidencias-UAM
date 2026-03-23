<?php  
$modulosMenu = [
  "Gestion Incidencias" => [
      "texto" => "Gestión de Incidencias",
      "ruta"  => "/ReporteIncidenciasUAM/apps/gestionIncidencias/index.php"
  ],
  "Generador CodigosQR" => [
      "texto" => "Generador de Códigos QR",
      "ruta"  => "/ReporteIncidenciasUAM/apps/generarCodigosQR/index.php"
  ],
  "Historial Incidencias" => [
      "texto" => "Historial de Incidencias",
      "ruta"  => "/ReporteIncidenciasUAM/apps/historialIncidencias/index.php"
  ],
  "Estadisticas Incidencias" => [
      "texto" => "Estadísticas de Incidencias",
      "ruta"  => "/ReporteIncidenciasUAM/apps/estadisticasIncidencias/index.php"
  ],
  "Gestion Personal" => [
      "texto" => "Gestión de Personal",
      "ruta"  => "/ReporteIncidenciasUAM/apps/gestionPersonal/index.php"
  ],
  "Usuarios" => [
      "texto" => "Administración de Usuarios",
      "ruta"  => "/ReporteIncidenciasUAM/apps/usuarios/index.php"
  ]
];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
  <div class="container-fluid">

    <a class="navbar-brand d-flex align-items-center gap-2" href="/ReporteIncidenciasUAM/index.php">
      <img src="/ReporteIncidenciasUAM/img/logo.png" width="76" height="47" alt="">
      <span>Departamento Sistemas</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">

      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">        
        <li class="nav-item">
          <span class="navbar-text text-white fw-semibold">
            <?php echo $_SESSION['nombre'].' '.$_SESSION['apellido'] ;?>
          </span>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            Módulos
          </a>

          <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
            <?php  foreach($modulosMenu as $nombre => $modulo):?>
              <?php if(isset($_SESSION['permisos'][$nombre])): ?>
                <li><a class="dropdown-item" href="<?= $modulo['ruta'] ?>"><?= $modulo['texto'] ?></a></li>
              <?php endif; ?>
            <?php endforeach; ?>
            <li><a class="dropdown-item text-danger fw-semibold" href="/ReporteIncidenciasUAM/logout.php">Cerrar Sesion</a></li>
          </ul>
        </li>        
      </ul>

    </div>
  </div>
</nav>