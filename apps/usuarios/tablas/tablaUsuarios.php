<?php
session_start();
if(!isset($_SESSION['usuario'])  ){
  header("Location: ../../../login.php");
  exit;
}

if(!isset($_SESSION['permisos']['Usuarios'])){
    header("Location: ../../../login.php");
    exit;
}
require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;

$buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';

$sql = "SELECT 
    u.idusuario,
    u.usuario,
    p.nombre,
    p.apellidoP,
    p.correo,
    tp.nombre AS tipoPersonal,
    u.estado
    FROM usuario u
    INNER JOIN personal p ON p.idpersonal = u.idpersonal
    INNER JOIN tipoPersonal tp ON tp.idtipoPersonal = p.idtipoPersonal";


$params = [];
$types  = "";
if ($buscar !== '') {
    $sql .= " WHERE (
        p.nombre LIKE ?
        OR p.correo LIKE ?
        OR p.apellidoP LIKE ?
        OR tp.nombre LIKE ?
        OR u.usuario LIKE ?
        OR u.estado LIKE ?
        OR u.idusuario LIKE ?
        OR CONCAT(p.nombre, ' ', p.apellidoP) LIKE ?
    )";
    $like = "%$buscar%";
    for ($i=0; $i<8; $i++) {
        $params[] = $like;
        $types .= "s";
    }
}

$sql .= " ORDER BY idusuario ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$tabla = "";
$totalFilas=0;
while ($row = $result->fetch_assoc()) {
    $totalFilas++;
    $nombre = $row['nombre']." ".$row['apellidoP'];
    $botonEditar ='<button class="btn-accion btn-editar" onclick="editarUsuario('.$row["idusuario"].')">
                        <i class="bi bi-pencil-square"></i>
                   </button></i>';
    $botonPermisos = '<button class="btn-accion btn-permisos" onclick="abrirModalPermisos('.$row["idusuario"].', \''.$row["usuario"].'\')">
                        <i class="bi bi-shield-lock"></i>
                      </button>';                   
    $tabla .= "
    <tr>
        <td>{$row['idusuario']}</td>
        <td>$nombre</td>
        <td>{$row['tipoPersonal']}</td>
        <td>{$row['correo']}</td>
        <td>{$row['usuario']}</td>
        <td>{$row['estado']}</td>       
        <td>{$botonPermisos}</td>
        <td>{$botonEditar}</td>
    </tr>";
}
?>
<span id="totalRegistrosData" data-total="<?= $totalFilas ?>" style="display:none;"></span>
<table class="table100 w-100">
    <thead>
    <tr>
    <th><span class="col-badge">ID</span></th>
      <th><span class="col-badge"><i class="bi bi-person"></i>Nombre</span></th>
      <th><span class="col-badge"><i class="bi bi-tag"></i>Tipo</span></th>
      <th><span class="col-badge"><i class="bi bi-envelope"></i>Correo</span></th>
      <th><span class="col-badge"><i class="bi bi-person-circle"></i>Usuario</span></th>
      <th><span class="col-badge"><i class="bi bi-activity"></i>Estado</span></th>
      <th><span class="col-badge"><i class="bi bi-shield-lock"></i>Permisos</span></th>
      <th><span class="col-badge"><i class="bi bi-pencil"></i>Editar</span></th>
    </tr>
    </thead>
    <tbody>
    <?php if ($totalFilas === 0): ?>
    <tr>
      <td colspan="10" class="text-center py-4 text-muted">
        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
        No se encontraron incidencias
      </td>
    </tr>
    <?php else: ?>
      <?= $tabla ?>
    <?php endif; ?>  
    </tbody>
</table>