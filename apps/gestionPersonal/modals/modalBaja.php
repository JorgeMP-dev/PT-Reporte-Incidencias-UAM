<div class="modal fade" id="modalBaja" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title">
          <i class="bi bi-person-x"></i> Dar de baja personal
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
     <form id="formBajaPersonal">
      <div class="modal-body">
        <input type="hidden" id="idPersonalBaja">

        <div class="mb-3">
          <label class="form-label">Motivo de la baja</label>
          <textarea class="form-control" id="motivoBaja" rows="3" 
                    placeholder="Escriba el motivo de la baja..." required></textarea>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="darBajaPersonal()">
            <i class="bi bi-floppy"></i> Confirmar Baja
          </button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
            <i class="bi bi-x-lg"></i> Cancelar
          </button>
        </div>
    </form> 
    </div>
  </div>
</div>