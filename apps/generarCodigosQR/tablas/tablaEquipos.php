<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../../login.php");
    exit;
}

if(!isset($_SESSION['permisos']['Generador CodigosQR'])){
    header("Location: ../../../login.php");
    exit;
}

require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;
require '../../../lib/phpqrcode/qrlib.php';

$aula   = isset($_POST['aula']) ? intval($_POST['aula']) : 0;
$buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';

$sql = "SELECT 
    e.idequipo,
    e.codigoInventario AS codigo,
    e.nombre,
    a.nombre AS aula
    FROM equipo e    
    INNER JOIN aula a ON a.idaula = e.idaula ";

$params = [];
$types  = "";

if ($aula > 0) {
    $sql .= "WHERE a.idaula = ?";
    $params[] = $aula;
    $types .= "i";
}

if ($buscar !== '') {
    if ($aula == 0) {
        $sql .= " WHERE ";
    } else {
        $sql .= " AND ";
    }
    $sql .= "(
        a.nombre LIKE ?
        OR e.codigoInventario LIKE ?
        OR e.nombre LIKE ?
    )";
    $like = "%$buscar%";
    for ($i=0; $i<3; $i++) {
        $params[] = $like;
        $types .= "s";
    }
}

$sql .= " ORDER BY e.codigoInventario ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$tabla = "";
$totalFilas= 0;

while ($row = $result->fetch_assoc()) {    
    $totalFilas++;
    $botonEditar ='<button class="btn-accion btn-editar" onclick="editarEquipo('.$row["idequipo"].')">
                        <i class="bi bi-pencil-square"></i>
                   </button></i>';
    $nombreUbicacionEquipo = $row['aula']." : ".$row['nombre'];
    $rutaQR = "https://192.168.1.183/ReporteIncidenciasUAM/apps/reporteIncidencias/index.php?equipo=".$row['idequipo'];
    $boton = "<button class='btn-qr-ver' 
    onclick=\"mostrarQR('$rutaQR','$nombreUbicacionEquipo')\"><i class='bi bi-qr-code'></i></button>";
    $tabla .= "
    <tr>
        <td>{$row['aula']}</td>
        <td>{$row['codigo']}</td>
        <td>{$row['nombre']}</td>
        <td>$boton</td>
        <td>$botonEditar</td>
    </tr>";
}
?>
<span id="totalRegistrosData" data-total="<?= $totalFilas ?>" style="display:none;"></span>

<table class="table100 w-100">
  <thead>
    <tr>
      <th><span class="col-badge"><i class="bi bi-building"></i>Aula</span></th>
      <th><span class="col-badge"><i class="bi bi-hash"></i>Código</span></th>
      <th><span class="col-badge"><i class="bi bi-pc-display"></i>Nombre</span></th>
      <th><span class="col-badge"><i class="bi bi-qr-code"></i>QR</span></th>
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