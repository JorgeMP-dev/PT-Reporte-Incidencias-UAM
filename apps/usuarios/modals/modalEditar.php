<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formEditarUsuario">
        <div class="modal-body">
          <input type="hidden" id="idUsuarioEditar" name="idusuarioEditar">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" id="nombreEditar" class="form-control" disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" id="usuarioEditar" name="usuarioEditar" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nueva Contraseña</label>
            <input type="password" id="passwordEditar" name="passwordEditar" class="form-control">
            <small class="text-muted">Dejar vacío si no desea cambiarla</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Estado</label>
            <select id="estadoEditar" name="estadoEditar" class="form-control">
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="guardarCambiosUsuario()">
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