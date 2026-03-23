<div class="modal fade" id="modalPersonal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> <i class="bi bi-person-add"></i> Nuevo Personal </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formPersonal">
        <div class="modal-body">                
            <div class="row mb-3">
            <div class="col-md-4">
                <label>Nombre
                <input type="text" id="nombre" class="form-control" required>
                </label>
            </div>
            <div class="col-md-4">
                <label>Apellido Paterno
                <input type="text" id="apellidoP" class="form-control" required>
                </label>
            </div>
            <div class="col-md-4">
                <label>Apellido Materno
                <input type="text" id="apellidoM" class="form-control" required>
                </label>
            </div>
            </div>
            <div class="row mb-3">
            <div class="col-md-4">
                <label>Tipo
                <select class="form-control " id="seleccionRol" required>
                    <option value="">Seleccione una opción...</option>
                </select>
                </label>
            </div>
            <div class="col-md-4">
                <label>Correo
                <input type="email" id="correo" class="form-control" required>
                </label>
            </div>
            <div class="col-md-4">
                <label>Telefono
                <input type="tel" id="telefono" class="form-control" pattern="[0-9]{10}" maxlength="10" >
                </label>
            </div>
            </div>
            <div class="row mb-3">
            <div class="col-md-4">
                <label>Numero Economico / Matricula
                <input type="text" id="numeroEconomico" class="form-control" required>
                </label>
            </div>
            <div class="col-md-4 d-none" id="divCodigoUnico" >
                <label>Codigo Unico
                <input type="text" id="codigoUnico" class="form-control" >
                </label>
            </div>     
            </div>

            <div id="seccionHorario" class="d-none">
            <hr>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="seccion-titulo"><i class="bi bi-calendar-week me-1"></i> Horario</span>
                <button type="button" class="btn-añadir-dia" onclick="agregarHorario()">
                    <i class="bi bi-calendar2-plus"></i> Añadir Día
                </button>
            </div>
            <div id="contenedorHorarios"></div>
            </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="guardarPersonal()">
            <i class="bi bi-floppy"></i> Guardar
          </button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
            <i class="bi bi-x-lg"></i> Cancelar
          </button>
        </div>
      </form>              
    </div>
  </div>
</div>