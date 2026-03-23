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

$idUsuario = intval($_POST['idUsuario']);

$sql = "SELECT idmodulo, idtipoPermiso FROM permiso WHERE idusuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

$permisosActuales = [];

while($row = $result->fetch_assoc()){
    $permisosActuales[$row['idmodulo']] = $row['idtipoPermiso'];
}

$sql = "SELECT * FROM modulo ORDER BY nombre";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $idmodulo = $row['idmodulo'];
    $permiso = $permisosActuales[$idmodulo] ?? "";
    echo '
    <div class="mb-3">
        <label class="form-label">'.$row['nombre'].'</label>
        <select class="form-select permiso-select" data-modulo="'.$idmodulo.'">
            <option value="" >Sin acceso</option>
            <option value="1" '.($permiso == 1 ? 'selected' : '').'>Administrador</option>
            <option value="2" '.($permiso == 2 ? 'selected' : '').'>Edición</option>
            <option value="3" '.($permiso == 3 ? 'selected' : '').'>Lectura</option>
        </select>
    </div>';
}
 ?>