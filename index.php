<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
  header("Location: login.php");
  exit;
}
require ('lib/var/varlib.php');
require ($_RUTADB);
 ?>
<?php echo $_ENCABEZADOSHTML ;?>
<?php echo $_DEPENDENCIASLIB ;?>
<body class="d-flex flex-column min-vh-100">
    <?php require('nav.php') ;?>    
    <main class="flex-fill">
    </main>
    <?php require('footer.php') ;?> 
</body>

</html>