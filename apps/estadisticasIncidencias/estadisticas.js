let chartAulas = null;
let chartEquipos = null;
let chartPersonal = null;

function cargarGraficas() {

    const f1 = document.getElementById("fechaInicio").value;
    const f2 = document.getElementById("fechaFin").value;
    if (!f1 || !f2) {
        alert("Selecciona ambas fechas.");
        return;
    }

    const btnGenerar = document.getElementById("btnGenerar");
    const btnExportar = document.getElementById("btnExportar"); 
    btnGenerar.disabled    = true;
    btnExportar.disabled    = true;

    fetch("controladores/datosEstadisticas.php", {
        method: "POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body: "inicio="+f1+"&fin="+f2
    })
    .then(res => res.json())
    .then(data => {        
        if (data.aulas && data.aulas.length > 0) {
            const aulaTop = data.aulas.reduce((max, actual) => {
                return parseInt(actual.total) > parseInt(max.total) ? actual : max;
            });
            document.getElementById("aulaTopNombre").innerText = aulaTop.nombre;
            document.getElementById("aulaTopTotal").innerText = aulaTop.total + " reportes en el período seleccionado";
        } else {
            document.getElementById("aulaTopNombre").innerText = "Sin datos";
            document.getElementById("aulaTopTotal").innerText = "";
        }
        crearGraficaAulas(data.aulas);
        crearGraficaEquipos(data.equipos);
        crearGraficaPersonal(data.personal);        
        const totalIncidencias = data.aulas ? data.aulas.reduce((sum, item) => sum + parseInt(item.total), 0): 0;
        const totalPersonal = data.personal ? data.personal.length : 0;
        document.getElementById("totalIncidencias").innerText = totalIncidencias;
        document.getElementById("totalIncidenciasSpan").innerText = "En el período seleccionado";
        document.getElementById("totalPersonal").innerText = totalPersonal;
        document.getElementById("totalPersonalSpan").innerText = "En el período seleccionado";
    }).catch(() => alert("Error al obtener los datos."))
    .finally(() => {        
        btnGenerar.disabled    = false;
        btnExportar.disabled    = false;
    });
    ;
}

function crearGraficaAulas(datos) {
    if (chartAulas) 
        chartAulas.destroy();
    
    if (!datos || datos.length === 0)
        return sinDatos("graficaAulas");

    const labels = datos.map(item => item.nombre);
    const valores = datos.map(item => item.total);

    const ctx = document.getElementById("graficaAulas").getContext("2d");

    chartAulas = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Incidencias por Aula',
                data: valores,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor:     'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: ctx => ` ${ctx.parsed.y} incidencias` }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: "rgba(0,0,0,0.05)" }
                },
                x: { grid: { display: false } }
            }
        }
    });
}

function crearGraficaEquipos(datos) {

    if (chartEquipos) 
        chartEquipos.destroy();
    
    if (!datos || datos.length === 0) 
        return sinDatos("graficaEquipos");

    const labels = datos.map(item => item.nombre);
    const valores = datos.map(item => item.total);

    const ctx = document.getElementById("graficaEquipos").getContext("2d");

   chartEquipos = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Equipos más afectados',
                data: valores,
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor:     'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y',
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: ctx => ` ${ctx.parsed.x} incidencias` }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: "rgba(0,0,0,0.05)" }
                },
                y: { grid: { display: false } }
            }
        }
    });
}

function crearGraficaPersonal(datos) {

    if (chartPersonal) 
        chartPersonal.destroy();
    
    if (!datos || datos.length === 0) 
        return sinDatos("graficaPersonal");

    const labels = datos.map(item => item.nombre);
    const valores = datos.map(item => item.total);
    const colores = generarColores(datos.length);
    const ctx = document.getElementById("graficaPersonal").getContext("2d");

    chartPersonal = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                label: 'Incidencias Atendidas por Personal',
                data: valores,
                backgroundColor: colores,
                borderColor:     '#fff',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed} resoluciones`
                    }
                }
            }
        }
    });
}


function sinDatos(canvasId) {
    const canvas = document.getElementById(canvasId);
    const ctx    = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.textAlign = "center";
    ctx.fillStyle = "#aaa";
    ctx.font      = "14px sans-serif";
    ctx.fillText("Sin datos en este período", canvas.width / 2, 60);        
    const btnExportar = document.getElementById("btnExportar"); 
    btnExportar.disabled  = true;
}

function generarColores(cantidad) {
    const coloresBase = [
        "#36A2EB","#FF6384","#4BC0C0",
        "#FF9F40","#9966FF","#FFCD56"
    ];

    if (cantidad <= coloresBase.length) {
        return coloresBase.slice(0, cantidad);
    }
    const colores = [...coloresBase];
    for (let i = coloresBase.length; i < cantidad; i++) {
        const hue = Math.round((i * 137.5) % 360); 
        colores.push(`hsl(${hue}, 65%, 55%)`);
    }
    return colores;
}



