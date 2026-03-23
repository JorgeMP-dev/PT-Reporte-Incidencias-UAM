<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../../login.php");
    exit;
}

if(!isset($_SESSION['permisos']['Gestion Personal'])){
    header("Location: ../../../login.php");
    exit;
}

require '../../../lib/var/varlib.php';
require '../../../'.$_RUTADB;

$tipo = $_POST['tipo'];

if($tipo === "ayudantes"){
    $sql = "SELECT p.nombre,
                   p.apellidoP,
                   p.apellidoM,
                   p.correo,
                   p.telefono,
                   t.nombre AS rol,
                   p.numeroEconomico,
                   p.idpersonal
            FROM personal p
            INNER JOIN tipoPersonal t ON t.idtipoPersonal = p.idtipoPersonal
            WHERE p.idtipoPersonal IN (1,2)
            AND p.estado = 'ACTIVO'";
}else{
    $sql = "SELECT p.nombre,
                   p.apellidoP,
                   p.apellidoM,
                   p.correo,
                   p.telefono,
                   t.nombre AS rol,
                   p.numeroEconomico,
                   p.idpersonal
            FROM personal p
            INNER JOIN tipoPersonal t ON t.idtipoPersonal = p.idtipoPersonal
            WHERE p.idtipoPersonal NOT IN (1,2)
            AND p.estado = 'ACTIVO'";
}
    
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$tabla = "";
$totalFilas= 0;

while ($row = $result->fetch_assoc()) {
    $totalFilas++;
    $nombreCompleto = $row['nombre']." ".$row['apellidoP'];
    $botonEditar ='<button class="btn-accion btn-editar" onclick="editarPersonal('.$row["idpersonal"].')">
                        <i class="bi bi-pencil-square"></i>
                   </button></i>';
    $botonBaja = '<button class="btn-accion btn-baja" onclick="abrirModalBaja('.$row["idpersonal"].')">
                        <i class="bi bi-person-x"></i>
                  </button>';
    $tabla .= "
    <tr>
        <td>{$nombreCompleto}</td>
        <td>{$row['rol']}</td>
        <td>{$row['numeroEconomico']}</td>
        <td>{$row['correo']}</td>
        <td>{$row['telefono']}</td>";
    if($tipo === "ayudantes"){
        $horario = "<button class='btn-horario-full' onclick='cargarTablaHorario({$row['idpersonal']})'><i class='bi bi-calendar-check'></i></button>";
        $tabla .= " <td>$horario</td> 
        <td>$botonEditar</td>
        <td>$botonBaja</td>
        </tr>";
    }else{
        $tabla .= "<td>$botonEditar</td>
        </tr>";
    }    
}
?>  
<span id="totalRegistrosData" data-total="<?= $totalFilas ?>" style="display:none;"></span>
<table class="table100 w-100">
    <thead>
    <tr>
      <th><span class="col-badge"><i class="bi bi-person"></i>Nombre</span></th>
      <th><span class="col-badge"><i class="bi bi-tag"></i>Rol</span></th>
      <th><span class="col-badge"><i class="bi bi-hash"></i>
        <?= $tipo === "ayudantes" ? "No. Económico / Matrícula" : "No. Económico" ?>
      </span></th>
      <th><span class="col-badge"><i class="bi bi-envelope"></i>Correo</span></th>
      <th><span class="col-badge"><i class="bi bi-telephone"></i>Teléfono</span></th>
      <?php if($tipo === "ayudantes"): ?>
        <th><span class="col-badge"><i class="bi bi-calendar-week"></i>Horario</span></th>
      <?php endif; ?>
      <th><span class="col-badge"><i class="bi bi-pencil"></i>Editar</span></th>
      <?php if($tipo === "ayudantes"): ?>
        <th><span class="col-badge"><i class="bi bi-person-x"></i>Baja</span></th>
      <?php endif; ?>
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