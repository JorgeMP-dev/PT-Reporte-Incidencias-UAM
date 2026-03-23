<?php
$sql = "SELECT 
            p.idpersonal,
            p.nombre,
            p.apellidoP,
            p.apellidoM,
            tp.nombre AS tipo,
            tp.idtipoPersonal
        FROM personal p
        INNER JOIN tipoPersonal tp ON tp.idtipoPersonal = p.idtipoPersonal
        LEFT JOIN usuario u ON u.idpersonal = p.idpersonal
        WHERE u.idpersonal IS NULL and tp.idtipoPersonal IN (1,2,4) 
        AND p.estado='ACTIVO'
        ORDER BY p.nombre ASC";

$result = $conn->query($sql);
?>

<div class="modal fade" id="modalAñadir" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-person-plus"></i> Añadir Usuario
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formAñadirUsuario">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Personal</label>
            <select class="form-control" name="idpersonal" required>
              <option value="">Seleccione...</option>
              <?php while($row = $result->fetch_assoc()): ?>
                <option value="<?= $row['idpersonal'] ?>">
                  <?= htmlspecialchars($row['nombre']." ".$row['apellidoP']." ".$row['apellidoM']." - ".$row['tipo']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Nombre de Usuario</label>
            <input type="text" name="usuario" class="form-control" required minlength="4">
          </div>
          
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required minlength="5">
          </div>

          <div class="mb-3">
            <label class="form-label">Confirmar Contraseña</label>
            <input type="password" name="confirmar" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Estado</label>
            <select class="form-control" name="estado">
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="guardarUsuario()">
            <i class="bi bi-floppy"></i> Guardar
          </button>
          <button class="btn btn-danger" data-bs-dismiss="modal">
            <i class="bi bi-x-lg"></i> Cancelar
          </button>
        </div>

      </form>

    </div>
  </div>
</div>