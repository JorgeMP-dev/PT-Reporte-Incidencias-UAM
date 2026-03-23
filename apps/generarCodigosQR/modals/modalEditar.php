<?php
    $sql = "SELECT idaula, nombre FROM aula ORDER BY nombre";
    $result = $conn->query($sql);
?>
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Equipo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formEditar">
        <input type="hidden" id="idEquipoEditar" name="id">
        <div class="modal-body">   
            <div class="row mb-3">
                <div class="col-md-12 text-center">
                    <strong>Ubicacion
                        <select class="form-control text-center btn btn-outline-secondary" name="seleccionAulaEditar" id="seleccionAulaEditar">
                        <?php while($aula = $result->fetch_assoc()): ?>
                            <option value="<?= $aula['idaula'] ?>">
                                <?= htmlspecialchars($aula['nombre']) ?>
                            </option>
                        <?php endwhile; ?>
                        </select>
                    </strong>
                </div>                
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Codigo Inventario
                    <input type="text" id="codigoEditar" class="form-control" name="codigoEditar" required>
                    </strong>
                </div>
                <div class="col-md-6">
                    <strong>Nombre
                    <input type="text" id="nombreEditar" class="form-control" name="nombreEditar" required>
                    </strong>
                </div> 
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-success" onclick="guardarCambiosEquipo()"><i class="bi bi-floppy"></i> Guardar</button>
            <button class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cancelar</button>
        </div>
      </form>              
    </div>
  </div>
</div>