async function enviarPost(data = {}) {
  try {
    const response = await fetch(
      "tablas/tablaIncidencias.php",
      {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(data)
      }
    );

    const html = await response.text();

    document.getElementById("cargaTablaIncidencias").innerHTML = html;
    actualizarConteo();
  } catch (e) {
    console.error("Error cargando incidencias", e);
  }
}

function cargaTabla() {
  enviarPost({});
}

document.addEventListener("DOMContentLoaded", () => {
  cargaTabla();
  setInterval(() => {
     cargaTabla();
  }, 30000);
});

async function atenderIncidencia(id){
  if (!confirm("¿Seguro que desea atenderla?")) return;
  try {
    const response = await fetch("controladores/atenderIncidencia.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "id=" + id
    });
    const data = await response.text();
    if (data === "ok") {
      cargaTabla(); 
    } else if(data === "alerta"){
       alert('⚠️ La incidencia ya fue tomada por otro ayudante.');
       cargaTabla(); 
    }else{
      alert("Error al actualizar");
    }
  } catch (e) {
    alert("Error de conexión");
  }
}

function abrirModalSolucion(id){
  document.getElementById("idIncidenciaSolucion").value = id;
  var modal = new bootstrap.Modal(document.getElementById('modalSolucion'));
  modal.show();
}

async function guardarSolucion(){
  if(!confirm("¿Seguro que deseas solucionar la incidencia?")){
        return;
  }   
  const id = document.getElementById("idIncidenciaSolucion").value;
  let solucion = document.getElementById("textoSolucion").value;
  if(solucion.trim() === ""){
    solucion="No se registró solución";
  }
  const respuesta = await fetch("controladores/solucionarIncidencia.php",{
    method:"POST",
    headers:{"Content-Type":"application/x-www-form-urlencoded"},
    body: new URLSearchParams({
      id:id,
      solucion:solucion
    })
  });
  const mensaje = await respuesta.text();

  if(mensaje === "ok"){
      const modalElement = document.getElementById("modalSolucion");
      const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
      modal.hide();
      cargaTabla();
  }else{
    alert("Error al guardar solucion");
  }
}

function actualizarConteo() {
    const data = document.querySelector('#totalRegistrosData');
    const footer = document.querySelector('#conteoRegistros');
    if (data && footer) {
        const total = data.dataset.total;
        footer.innerHTML = `Mostrando <strong>${total}</strong> registro${total != 1 ? 's' : ''}`;
    }
}