async function cargarTabla(tipo){
    document.getElementById("tableHeader").classList.remove("d-none");
    try {
        const response = await fetch("tablas/tablaPersonal.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: "tipo=" + tipo
        });

        const html = await response.text();

        document.getElementById("cargaTabla").innerHTML = html;
        actualizarConteo()
    } catch (e) {
        console.error("Error cargando tabla", e);
    }
}

document.addEventListener("DOMContentLoaded", function(){
    const selectRol = document.getElementById("seleccionRol");
    const seccionHorario = document.getElementById("seccionHorario");
    const seccionCodigoUnico = document.getElementById("divCodigoUnico");
    const codigoUnico = document.getElementById("codigoUnico");

    btnAyudantes.addEventListener("click", function(){
        cargarTabla('ayudantes');
        seccionHorario.classList.remove("d-none");
        seccionCodigoUnico.classList.remove("d-none");
        codigoUnico.required = true;
        llenarSelects("ayudantes");
    });

    btnProfesores.addEventListener("click", function(){
        cargarTabla('profesores');
        seccionHorario.classList.add("d-none");
        seccionCodigoUnico.classList.add("d-none");
        codigoUnico.required = false;
        document.getElementById("contenedorHorarios").innerHTML = "";
        llenarSelects("profesores");
    });


});

function agregarHorario(){

    const contenedor = document.getElementById("contenedorHorarios");

    const fila = document.createElement("div");
    fila.classList.add("row", "mb-2", "align-items-end", "horario-item");

    fila.innerHTML = `
        <div class="col-md-4">
            <label>Día
                <select class="form-control dia">
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miercoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                </select>
            </label>
        </div>
        <div class="col-md-3">
            <label>Hora Inicio
                <input type="time" class="form-control horaInicio" required>
            </label>
        </div>
        <div class="col-md-3">
            <label>Hora Fin
                <input type="time" class="form-control horaFin" required>
            </label>
        </div>
        <div class="col-md-2 d-flex align-items-end pb-1">
            <button type="button" class="btn-eliminar-horario"
                    onclick="this.closest('.horario-item').remove()" title="Eliminar">
                <i class="bi bi-trash3"></i>
            </button>
        </div>
    `;
    contenedor.appendChild(fila);
}

async function guardarPersonal(){
    const form = document.getElementById("formPersonal");

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const data = new FormData();

    data.append("nombre", document.getElementById("nombre").value);
    data.append("apellidoM", document.getElementById("apellidoM").value);
    data.append("apellidoP", document.getElementById("apellidoP").value);
    data.append("rol", document.getElementById("seleccionRol").value);
    data.append("correo", document.getElementById("correo").value);
    data.append("telefono", document.getElementById("telefono").value);
    data.append("numeroEconomico", document.getElementById("numeroEconomico").value);
    data.append("codigoUnico", document.getElementById("codigoUnico").value);

    document.querySelectorAll(".horario-item").forEach((item, index) => {
        data.append(`horarios[${index}][dia]`, item.querySelector(".dia").value);
        data.append(`horarios[${index}][inicio]`, item.querySelector(".horaInicio").value);
        data.append(`horarios[${index}][fin]`, item.querySelector(".horaFin").value);

    });
        
    const response = await fetch("controladores/guardarPersonal.php", {
        method: "POST",
        body: data
    });

    const respuesta = await response.text();
    if (respuesta === "ok") {
        alert("Registro Creado con Exito");
        const modalElement = document.getElementById("modalPersonal");
        const modal = bootstrap.Modal.getInstance(modalElement);
        const rol = document.getElementById("seleccionRol").value;
        modal.hide();
        form.reset();
        if(rol == 1 || rol == 2)
            cargarTabla("ayudantes"); 
        else 
            cargarTabla("profesores"); 
    }else{
        alert(respuesta);
    }
}

async function cargarTablaHorario(idpersonal){
    try {
        const response = await fetch("tablas/tablaHorario.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "idpersonal=" + idpersonal
        });
        const html = await response.text();
        document.getElementById("tablaHorario").innerHTML = html;
        const modal = new bootstrap.Modal(document.getElementById("modalHorario"));
        modal.show();
    } catch (error) {
        console.error("Error cargando horario:", error);
    }
}

function editarPersonal(id){
    fetch("controladores/obtenerPersonal.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + id
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("idPersonalEditar").value = data.idpersonal;
        document.getElementById("nombreEditar").value = data.nombre;
        document.getElementById("apellidoPEditar").value = data.apellidoP;
        document.getElementById("apellidoMEditar").value = data.apellidoM;
        document.getElementById("numeroEconomicoEditar").value = data.numeroEconomico;
        document.getElementById("seleccionRolEditar").value = data.idtipoPersonal;
        document.getElementById("correoEditar").value = data.correo;
        document.getElementById("telefonoEditar").value = data.telefono;
        document.getElementById("codigoUnicoEditar").value = data.codigoUnico;
        var modal = new bootstrap.Modal(document.getElementById('modalEditarPersonal'));
        modal.show();
    });
}

async function guardarCambiosPersonal(){
    const form = document.getElementById("formEditarPersonal");
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    if(!confirm("¿Seguro que deseas actualizar este registro?")){
        return;
    }    

    const data = new FormData(form);
    const response = await fetch("controladores/editarPersonal.php", {
        method: "POST",
        body: data
    });
    const respuesta = await response.text();
    if (respuesta === "ok") {
        alert("Cambios aplicados con exito");
        const modalElement = document.getElementById("modalEditarPersonal");
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        const rol = document.getElementById("seleccionRolEditar").value;
        modal.hide();
        form.reset();     
        if(rol == 1 || rol == 2)
            cargarTabla("ayudantes"); 
        else 
            cargarTabla("profesores"); 
    }else{
        alert(respuesta);
    }
}

function abrirModalPersonal(){    
    const modalElement = document.getElementById("modalPersonal");
    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
    modal.show();
}

function llenarSelects(tipoTabla){
    fetch("controladores/obtenerTipoPersonal.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "tipoTabla=" + tipoTabla
    })
    .then(res => res.json())
    .then(data => {
        const select = document.getElementById("seleccionRol");
        select.innerHTML = '<option value="">Seleccione una opción ...</option>';
        data.forEach(tipo => {
            const option = document.createElement("option");
            option.value = tipo.idtipoPersonal;
            option.textContent = tipo.nombre;
            select.appendChild(option);
        });
    });
}

function abrirModalBaja(id) {
    document.getElementById("idPersonalBaja").value = id;
    document.getElementById("motivoBaja").value = "";
    const modal = new bootstrap.Modal(document.getElementById("modalBaja"));
    modal.show();
}


async function darBajaPersonal(id) {
    const idBaja = document.getElementById("idPersonalBaja").value;
    const motivo = document.getElementById("motivoBaja").value.trim();
    const form = document.getElementById("formBajaPersonal");
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    if(!confirm("¿Seguro que deseas dar de baja al personal?")){
        return;
    }    
    const response = await fetch("controladores/darBajaPersonal.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "idpersonal=" + idBaja + "&motivo=" + encodeURIComponent(motivo)
    });
    const respuesta = await response.text();
    if (respuesta.trim() === "ok") {
        alert("Personal dado de baja correctamente.");
        const modalElement = document.getElementById("modalBaja");
        const modal = bootstrap.Modal.getInstance(modalElement);
        modal.hide();
        form.reset();
        cargarTabla("ayudantes");        
    } else {
        alert("Error al dar de baja");
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