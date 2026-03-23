<div class="modal fade" id="modalSolucion" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Solucionar Incidencia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="idIncidenciaSolucion">
        <label>Solución aplicada (opcional):</label>
        <textarea id="textoSolucion" class="form-control"></textarea>
      </div>
      <div class="modal-footer">        
        <button class="btn btn-success" onclick="guardarSolucion()"><i class="bi bi-floppy"></i> Guardar</button>
        <button class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Cancelar</button>
      </div>
    </div>
  </div>
</div>