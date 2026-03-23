<?php
$sql = "SELECT * FROM tipopersonal ORDER BY idtipopersonal DESC";
$result = $conn->query($sql);
?>

<div class="modal fade" id="modalEditarPersonal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Personal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formEditarPersonal">
        <input type="hidden" id="idPersonalEditar" name="id">
        <div class="modal-body">                
            <div class="row mb-3">
            <div class="col-md-4">
                <label>Nombre
                <input type="text" id="nombreEditar"  name="nombre" class="form-control" required>
                </label>
            </div>
            <div class="col-md-4">
                <label>Apellido Paterno
                <input type="text" id="apellidoPEditar" name="apellidoP" class="form-control" required>
                </label>
            </div>
            <div class="col-md-4">
                <label>Apellido Materno
                <input type="text" id="apellidoMEditar" name="apellidoM" class="form-control" required>
                </label>
            </div>
            </div>
            <div class="row mb-3">
            <div class="col-md-4">
                <label>Tipo
                <select class="form-control text-center btn btn-outline-secondary " id="seleccionRolEditar" name="rol">
                    <?php while($row = $result->fetch_assoc()): ?>
                        <option value="<?= $row['idtipoPersonal'] ?>">
                            <?= htmlspecialchars($row['nombre']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                </label>
            </div>            
            <div class="col-md-4">
                <label>Correo
                <input type="email" id="correoEditar" name="correo" class="form-control" required>
                </label>
            </div>
            <div class="col-md-4">
                <label>Telefono
                <input type="tel" id="telefonoEditar" class="form-control" name="telefono" pattern="[0-9]{10}" maxlength="10" >
                </label>
            </div>
            </div>
            <div class="row mb-3">                   
            <div class="col-md-4">
                <label>Numero Economico / Matricula
                <input type="text" id="numeroEconomicoEditar" name="numeroEconomico" class="form-control" required>
                </label>
            </div>
            <div class="col-md-4" id="divCodigoUnico" >
                <label>Codigo Unico
                <input type="text" id="codigoUnicoEditar" name="codigoUnico" class="form-control" >
                </label>
            </div>     
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="guardarCambiosPersonal()">
            <i class="bi bi-floppy"></i> Guardar Cambios
          </button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
            <i class="bi bi-x-lg"></i> Cancelar
          </button>
        </div>
      </form>              
    </div>
  </div>
</div>