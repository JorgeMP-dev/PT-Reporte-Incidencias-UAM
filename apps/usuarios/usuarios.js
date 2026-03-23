async function enviarPost(data = {}) {
  try {
    blockUI();
    const response = await fetch(
      "tablas/tablaUsuarios.php",
      {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(data)
      }
    );
    const html = await response.text();
    document.getElementById("cargaTablaUsuarios").innerHTML = html;
    actualizarConteo()
  } catch (e) {
    console.error("Error cargando equipos", e);
  } finally {
    unblockUI();
  }
}

function cargaTabla(buscar = "") {
  enviarPost({
    buscar: buscar
  });
}

document.addEventListener("DOMContentLoaded", () => {
    const buscador = document.getElementById("buscador");

    cargaTabla();
    let timeout = null;
    buscador.addEventListener("keyup", () => {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        cargaTabla(buscador.value);
      }, 300);
    });
  });

async function guardarUsuario(){
    const form = document.getElementById("formAñadirUsuario");    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }    
    const data = new FormData(form);
    
    if(data.get("password") !== data.get("confirmar")){
        alert("Las contraseñas no coinciden");
        return;
    }
    const response = await fetch("controladores/guardarUsuario.php", {
        method: "POST",
        body: data
    });

    const respuesta = await response.text();
    if (respuesta === "ok") {
        alert("Registro Insertado con Exito");
        const modalElement = document.getElementById("modalAñadir");
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        modal.hide();
        form.reset();
        cargaTabla(); 
    }else{
        alert(respuesta);
    }
}

function editarUsuario(id){
    fetch("controladores/obtenerUsuario.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + id
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("idUsuarioEditar").value = data.idusuario;
        document.getElementById("nombreEditar").value = data.nombre+" "+data.apellidoP;
        document.getElementById("usuarioEditar").value = data.usuario;
        document.getElementById("estadoEditar").value = data.estado;
        const modal = new bootstrap.Modal(
            document.getElementById("modalEditar")
        );
        modal.show();
    });
}

async function guardarCambiosUsuario(){
    const form = document.getElementById("formEditarUsuario");
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }    
    const data = new FormData(form);
    const response = await fetch("controladores/editarUsuario.php", {
        method: "POST",
        body: data
    });
    const respuesta = await response.text();

    if(respuesta === "ok"){
         alert("Registro Editado con Exito");
        const modalElement = document.getElementById("modalEditar");
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        modal.hide();
        form.reset();
        cargaTabla(); 
    }else{
        alert(respuesta);
    }
}

function abrirModalAñadir(){
    const modalElement = document.getElementById("modalAñadir");
    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
    modal.show();
}

function abrirModalPermisos(idUsuario, usuario){
    document.getElementById("idUsuarioPermiso").value = idUsuario;
    document.getElementById("nombreUsuarioPermiso").innerText = usuario;
    fetch("controladores/cargarPermisos.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "idUsuario=" + idUsuario
    })
    .then(res => res.text())
    .then(data => {
       document.getElementById("contenedorPermisos").innerHTML = data;
    });
    const modalElement = document.getElementById("modalPermisos");
    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
    modal.show();
}

function guardarPermisos(){
    let idusuario = document.getElementById("idUsuarioPermiso").value;
    let permisos = [];
    document.querySelectorAll(".permiso-select").forEach(select => {
        if(select.value !== ""){
            permisos.push({
                idmodulo: select.dataset.modulo,
                idtipoPermiso: select.value
            });
        }
    });
    fetch("controladores/guardarPermisos.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            idusuario: idusuario,
            permisos: permisos
        })
    })
    .then(res => res.text())
    .then(data => {
       alert("Permisos Actualizados");
       const modalElement = document.getElementById("modalPermisos");
       const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
       modal.hide();
       form.reset();
    });

}

function actualizarConteo() {
    const data = document.querySelector('#totalRegistrosData');
    const footer = document.querySelector('#conteoRegistros');
    if (data && footer) {
        const total = data.dataset.total;
        footer.innerHTML = `Mostrando <strong>${total}</strong> registro${total != 1 ? 's' : ''}`;
    }
}