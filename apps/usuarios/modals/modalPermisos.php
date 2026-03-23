<div class="modal fade" id="modalPermisos">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">
          Permisos de <span id="nombreUsuarioPermiso"></span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="idUsuarioPermiso">
        <div id="contenedorPermisos">          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="guardarPermisos()">
            <i class="bi bi-floppy"></i> Guardar Cambios
          </button>
          <button class="btn btn-danger" data-bs-dismiss="modal">
            <i class="bi bi-x-lg"></i> Cancelar
          </button>
      </div>
    </div>
  </div>
</div>