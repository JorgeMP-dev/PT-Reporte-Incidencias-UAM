async function enviarPost(data = {}) {
  try {
    blockUI();
    const response = await fetch(
      "tablas/tablaEquipos.php",
      {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(data)
      }
    );
    const html = await response.text();
    document.getElementById("cargaTablaEquipos").innerHTML = html;
    actualizarConteo()
  } catch (e) {
    console.error("Error cargando equipos", e);
  } finally {
    unblockUI();
  }
}

function cargaTabla(aula = 0, buscar = "") {
  enviarPost({
    aula: aula,
    buscar: buscar
  });
}

document.addEventListener("DOMContentLoaded", () => {
  const selectAula = document.getElementById("seleccionAula");
  const buscador = document.getElementById("buscador");
  cargaTabla();
  selectAula.addEventListener("change", () => {
    cargaTabla(selectAula.value, buscador.value);
  });    
  let timeout = null;
  buscador.addEventListener("keyup", () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      cargaTabla(selectAula.value, buscador.value);
    }, 300);
  });
});


function mostrarQR(contenidoQR,nombreEquipo) {
  const datosQR = encodeURIComponent(contenidoQR);
  document.getElementById("qrImagen").src = "controladores/generarQR.php?data=" + datosQR;
  document.getElementById("tituloModalQR").innerText = nombreEquipo;
  const modal = new bootstrap.Modal(
    document.getElementById("modalQR")
  );
  modal.show();
}

function imprimirQR() {
   window.print();
}

async function guardarEquipo(){
    const form = document.getElementById("formAñadir");    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }    
    const data = new FormData();

    data.append("aula", document.getElementById("seleccionAulaAñadir").value);
    data.append("codigo", document.getElementById("codigoAñadir").value);
    data.append("nombre", document.getElementById("nombreAñadir").value);
        
    const response = await fetch("controladores/guardarEquipo.php", {
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

function editarEquipo(id){
    fetch("controladores/obtenerEquipo.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + id
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("seleccionAulaEditar").value = data.idaula;
        document.getElementById("codigoEditar").value = data.codigoInventario;
        document.getElementById("nombreEditar").value = data.nombre;
        document.getElementById("idEquipoEditar").value = data.idequipo;
        var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
        modal.show();
    });
}

async function guardarCambiosEquipo(){
    const form = document.getElementById("formEditar");
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    if(!confirm("¿Seguro que deseas actualizar este equipo?")){
        return;
    }    

    const data = new FormData(form);
    const response = await fetch("controladores/editarEquipo.php", {
        method: "POST",
        body: data
    });
    const respuesta = await response.text();
    if (respuesta === "ok") {
        alert("Cambios aplicados con exito");
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

function imprimirQRAula() {
    const aula = document.getElementById("seleccionAula").value;
    if (aula == 0) {
        alert("Seleccione un aula específica.");
        return;
    }
    window.open( "controladores/impresionQRAula.php?aula=" + aula,"_blank");
}

function actualizarConteo() {
    const data = document.querySelector('#totalRegistrosData');
    const footer = document.querySelector('#conteoRegistros');
    if (data && footer) {
        const total = data.dataset.total;
        footer.innerHTML = `Mostrando <strong>${total}</strong> registro${total != 1 ? 's' : ''}`;
    }
}