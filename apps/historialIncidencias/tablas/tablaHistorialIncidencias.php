<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../../login.php");
    exit;
}

if(!isset($_SESSION['permisos']['Historial Incidencias'])){
    header("Location: ../../../login.php");
    exit;
}

require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;

$aula   = isset($_POST['aula']) ? intval($_POST['aula']) : 0;
$buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';

$sql = "SELECT 
    i.idincidencia,
    a.nombre AS aula,
    t.nombre AS tipo,
    t.idtipoIncidencia,
    i.descripcion,
    e.nombre AS equipo,
    i.fechaReporte,
    i.idestado,
    i.realizoReporte,
    p.nombre AS atendioNombre,
    p.apellidoP AS atendioApellido,
    i.solucion,
    i.fechaSolucion
    FROM incidencia i    
    INNER JOIN tipoIncidencia t ON t.idtipoIncidencia = i.idtipoIncidencia
    INNER JOIN equipo e ON e.idequipo = i.idequipo
    INNER JOIN aula a ON a.idaula = e.idaula 
    LEFT JOIN personal p ON p.idpersonal = i.idpersonal
    WHERE i.idestado=2 ";

$params = [];
$types  = "";

if ($aula > 0) {
    $sql .= " AND a.idaula = ?";
    $params[] = $aula;
    $types .= "i";
}

if ($buscar !== '') {
    $sql .= " AND (
        a.nombre LIKE ?
        OR t.nombre LIKE ?
        OR i.descripcion LIKE ?
        OR i.realizoReporte LIKE ?
        OR e.nombre LIKE ?
        OR p.nombre LIKE ?
        OR p.apellidoP LIKE ?
        OR i.solucion LIKE ?
    )";
    $like = "%$buscar%";
    for ($i=0; $i<8; $i++) {
        $params[] = $like;
        $types .= "s";
    }
}

$sql .= " ORDER BY i.fechaReporte DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$tabla = "";
$totalFilas= 0;

while ($row = $result->fetch_assoc()) {
    if($row['idtipoIncidencia']=='1')
        $colorTipoIncidencia = "<span class='badge-tipo badge-urgente'>" . htmlspecialchars($row['tipo']) . "</span>";
    else
        $colorTipoIncidencia = "<span class='badge-tipo badge-normal'>" . htmlspecialchars($row['tipo']) . "</span>";
    $atendio = $row['atendioNombre']." ".$row['atendioApellido'];
    $totalFilas++;
    $tabla .= "
    <tr>
        <td>{$row['idincidencia']}</td>
        <td>{$row['aula']}</td>
        <td>$colorTipoIncidencia</td>
        <td>{$row['realizoReporte']}</td>
        <td>{$row['descripcion']}</td>
        <td>{$row['equipo']}</td>
        <td>$atendio</td>
        <td>{$row['solucion']}</td>       
        <td>{$row['fechaReporte']}</td>
        <td>{$row['fechaSolucion']}</td>
    </tr>";
}
?>
<span id="totalRegistrosData" data-total="<?= $totalFilas ?>" style="display:none;"></span>
<table class="table100 w-100">
    <thead>
    <tr>
    <th><span class="col-badge">ID</span></th>
    <th><span class="col-badge"><i class="bi bi-building"></i>Aula</span></th>
    <th><span class="col-badge"><i class="bi bi-tag"></i>Tipo</span></th>
    <th><span class="col-badge"><i class="bi bi-person"></i>Reportó</span></th>
    <th><span class="col-badge"><i class="bi bi-chat-text"></i>Descripción</span></th>
    <th><span class="col-badge"><i class="bi bi-pc-display"></i>Equipo</span></th>
    <th><span class="col-badge"><i class="bi bi-person-check"></i>Atendió</span></th>
    <th><span class="col-badge"><i class="bi bi-check2-circle"></i>Solución</span></th>
    <th><span class="col-badge"><i class="bi bi-calendar-event"></i>Fecha Reporte</span></th>
    <th><span class="col-badge"><i class="bi bi-calendar-check"></i>Fecha Resolución</span></th>
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