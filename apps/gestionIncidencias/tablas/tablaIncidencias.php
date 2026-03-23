<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../../login.php");
    exit;
}

if(!isset($_SESSION['permisos']['Gestion Incidencias'])){
    header("Location: ../../../login.php");
    exit;
}

require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;


$sql = "SELECT 
    i.idincidencia,
    a.nombre AS aula,
    t.nombre AS tipo,
    i.descripcion,
    e.nombre AS equipo,
    i.fechaReporte,
    i.idestado,
    i.idpersonal,
    i.realizoReporte,
    p.nombre AS atendiendoNombre,
    p.apellidoP AS atendiendoApellido
    FROM incidencia i    
    INNER JOIN tipoIncidencia t ON t.idtipoIncidencia = i.idtipoIncidencia
    INNER JOIN equipo e ON e.idequipo = i.idequipo
    INNER JOIN aula a ON a.idaula = e.idaula 
    LEFT JOIN personal p ON p.idpersonal = i.idpersonal
    WHERE i.idestado NOT IN (2) ORDER BY t.nombre DESC,i.fechaReporte ASC";
    
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$tabla = "";
$totalFilas=0;

while ($row = $result->fetch_assoc()) {
    $totalFilas++;
    $esUrgente = strtolower($row['tipo']) === 'urgente';
    $claseUrgente = $esUrgente ? ' fila-urgente' : '';
    if ($row['idestado'] == 1) {
        $estado = "<button class='btn-estado btn-atender' onclick='atenderIncidencia({$row['idincidencia']})'>
                    <i class='bi bi-person-raised-hand'></i>Atender
                    </button>";
    } elseif ($_SESSION['id_personal'] == $row['idpersonal'])  {       
        $estado = "<button class='btn-estado btn-solucionar' onclick='abrirModalSolucion({$row['idincidencia']})'>
                    <i class='bi bi-check-all'></i>Solucionada
                    </button>";
    }else{
        $atendiendoNombre = htmlspecialchars($row['atendiendoNombre'] ?? '');
        $atendiendoApellido = htmlspecialchars($row['atendiendoApellido'] ?? '');
        $estado = "Atendiendo: $atendiendoNombre $atendiendoApellido";
    }
    $tabla .= "
    <tr class='$claseUrgente'>
        <td>{$row['idincidencia']}</td>
        <td>{$row['aula']}</td>
        <td>{$row['tipo']}</td>
        <td>{$row['realizoReporte']}</td>
        <td>{$row['descripcion']}</td>
        <td>{$row['equipo']}</td>
        <td>{$row['fechaReporte']}</td>
        <td>$estado</td>
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
    <th><span class="col-badge"><i class="bi bi-calendar-event"></i>Fecha Reporte</span></th>
    <th><span class="col-badge"><i class="bi bi-activity"></i>Estado</span></th>
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