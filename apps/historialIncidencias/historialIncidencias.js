async function enviarPost(data = {}) {
  try {
    const response = await fetch(
      "tablas/tablaHistorialIncidencias.php",
      {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(data)
      }
    );

    const html = await response.text();
    document.getElementById("cargaTablaHistorialIncidencias").innerHTML = html;
    actualizarConteo();
  } catch (e) {
    console.error("Error cargando incidencias", e);
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

function abrirEstadisticas() {
    window.location.href = "../estadisticasIncidencias/index.php";
}

function actualizarConteo() {
    const data = document.querySelector('#totalRegistrosData');
    const footer = document.querySelector('#conteoRegistros');
    if (data && footer) {
        const total = data.dataset.total;
        footer.innerHTML = `Mostrando <strong>${total}</strong> registro${total != 1 ? 's' : ''}`;
    }
}