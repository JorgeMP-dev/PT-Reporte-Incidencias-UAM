async function exportarPDF() {
    const btnExportar = document.getElementById("btnExportar");
    const btnGenerar  = document.getElementById("btnGenerar");

    btnExportar.disabled = true;
    btnGenerar.disabled  = true;
    btnExportar.innerHTML = '<i class="bi bi-hourglass-split"></i> Generando...';

    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF({ orientation: "portrait", unit: "mm", format: "a4" });

    const pageW   = pdf.internal.pageSize.getWidth();
    const pageH   = pdf.internal.pageSize.getHeight();
    const margin  = 12;
    const usableW = pageW - margin * 2;

    aumentarResolucionGraficas(3);

    // ── Paleta monocromática (tus colores) ────────────────────────
    const colorNegro      = [20,  20,  20 ];
    const colorGrisOscuro = [60,  60,  60 ];
    const colorGrisMedio  = [120, 120, 120];
    const colorGrisClaro  = [210, 210, 210];
    const colorFondoCard  = [245, 245, 245];

    // ── Colores de marca ──────────────────────────────────────────
    const COLOR_PRIMARY  = [108, 122, 224];
    const COLOR_DARK     = [45,  50,  80 ];
    const COLOR_GRAY     = [139, 145, 176];
    const COLOR_LIGHT_BG = [247, 248, 252];
    const COLOR_WHITE    = [255, 255, 255];

    let y = 0;

    // =========================================================================
    // ENCABEZADO (fondo negro, logo izquierda) — TUS CAMBIOS
    // =========================================================================
    const altoHeader = 32;
    pdf.setFillColor(...colorNegro);
    pdf.rect(0, 0, pageW, altoHeader, 'F');

    // — Logo —
    try {
        const logoData = await imagenABase64("../../img/logo.png");
        pdf.addImage(logoData, "PNG", margin, 5, 32, 20);
    } catch (e) {
        pdf.setFillColor(50, 50, 50);
        pdf.roundedRect(margin, 5, 22, 22, 2, 2, 'F');
        pdf.setFontSize(10);
        pdf.setFont("helvetica", "bold");
        pdf.setTextColor(255, 255, 255);
        pdf.text("DS", margin + 11, 19, { align: "center" });
    }

    // — Título y subtítulo —
    const xTexto = margin + 35;
    pdf.setTextColor(255, 255, 255);
    pdf.setFontSize(15);
    pdf.setFont("helvetica", "bold");
    pdf.text("Reporte de Incidencias", xTexto, 14);

    pdf.setFontSize(9);
    pdf.setFont("helvetica", "normal");
    pdf.setTextColor(255, 255, 255);
    pdf.text("Departamento Sistemas", xTexto, 22);

    // — Rango de fechas (derecha) —
    const fecha1 = document.getElementById("fechaInicio").value;
    const fecha2 = document.getElementById("fechaFin").value;
    pdf.setFontSize(8);
    pdf.setTextColor(255, 255, 255);
    pdf.text(`Periodo: ${formatearFecha(fecha1)}  –  ${formatearFecha(fecha2)}`, pageW - margin, 22, { align: "right" });

    y = altoHeader + 8;

    // Fecha de generación
    const ahora = new Date();
    pdf.setFontSize(7.5);
    pdf.setFont("helvetica", "italic");
    pdf.setTextColor(20, 20, 20);
    pdf.text(
        `Generado el ${ahora.toLocaleDateString('es-MX')} a las ${ahora.toLocaleTimeString('es-MX')}`,
        margin, y
    );
    y += 9;

    // ── TARJETAS KPI (tus colores monocromáticos) ─────────────────
    const kpis = [
        {
            label: "Total Incidencias",
            valor: document.getElementById("totalIncidencias").innerText,
            sub:   document.getElementById("totalIncidenciasSpan").innerText,
            color: colorGrisMedio
        },
        {
            label: "Aula con más reportes",
            valor: document.getElementById("aulaTopNombre").innerText,
            sub:   document.getElementById("aulaTopTotal").innerText,
            color: colorGrisMedio
        },
        {
            label: "Personal Activo",
            valor: document.getElementById("totalPersonal").innerText,
            sub:   document.getElementById("totalPersonalSpan").innerText,
            color: colorGrisMedio
        }
    ];

    const kpiW = (usableW - 6) / 3;
    const kpiH = 22;

    kpis.forEach((kpi, i) => {
        const x = margin + i * (kpiW + 3);
        pdf.setFillColor(...COLOR_LIGHT_BG);
        pdf.roundedRect(x, y, kpiW, kpiH, 3, 3, "F");
        pdf.setFillColor(...kpi.color);
        pdf.roundedRect(x, y, kpiW, 3, 1.5, 1.5, "F");
        pdf.rect(x, y + 1.5, kpiW, 1.5, "F");
        pdf.setTextColor(...COLOR_GRAY);
        pdf.setFontSize(6.5);
        pdf.setFont("helvetica", "bold");
        pdf.text(kpi.label.toUpperCase(), x + kpiW / 2, y + 8, { align: "center" });
        pdf.setTextColor(...COLOR_DARK);
        pdf.setFontSize(12);
        pdf.setFont("helvetica", "bold");
        const valorText = String(kpi.valor).length > 18
            ? String(kpi.valor).substring(0, 17) + "…"
            : kpi.valor;
        pdf.text(valorText, x + kpiW / 2, y + 15, { align: "center" });
        pdf.setTextColor(...COLOR_GRAY);
        pdf.setFontSize(6);
        pdf.setFont("helvetica", "normal");
        pdf.text(kpi.sub || "", x + kpiW / 2, y + 20, { align: "center" });
    });

    y += kpiH + 8;

    // ── Encabezado de continuación (fondo negro — tus cambios) ────
    function agregarEncabezadoContinuacion() {
        pdf.setFillColor(...colorNegro);
        pdf.rect(0, 0, pageW, 10, "F");
        pdf.setTextColor(...COLOR_WHITE);
        pdf.setFontSize(8);
        pdf.setFont("helvetica", "bold");
        pdf.text("Estadísticas de Incidencias — Departamento de Sistemas", pageW / 2, 7, { align: "center" });
    }

    // ── Tabla de datos (tu función dibujarTabla — corregida) ──────
    function dibujarTabla(chart, columnas) {
        if (!chart || !chart.data || !chart.data.labels || chart.data.labels.length === 0) return;

        const labels  = chart.data.labels;
        const valores = chart.data.datasets[0].data;
        const filaH   = 5.5;
        const encH    = 6;

        // Si la tabla entera no cabe, nueva página
        const tablaH = encH + labels.length * filaH;
        if (pageH - y - margin < tablaH + 10) {
            pdf.addPage();
            agregarEncabezadoContinuacion();
            y = 16;
        }

        let yTablaInicio = y;

        // Encabezado de tabla — fondo negro como tu dibujarTabla original
        pdf.setFillColor(...colorNegro);
        pdf.rect(margin, y, usableW, encH, 'F');
        pdf.setFontSize(7);
        pdf.setFont("helvetica", "bold");
        pdf.setTextColor(255, 255, 255);
        pdf.text(columnas[0], margin + 3, y + 4);
        pdf.text(columnas[1], margin + usableW - 3, y + 4, { align: "right" });
        y += encH;

        // Filas
        labels.forEach((label, i) => {
            // Salto de página en mitad de tabla
            if (pageH - y - margin < filaH + 10) {
                pdf.setDrawColor(...colorGrisClaro);
                pdf.setLineWidth(0.2);
                pdf.rect(margin, yTablaInicio, usableW, y - yTablaInicio);
                pdf.addPage();
                agregarEncabezadoContinuacion();
                y = 16;
                yTablaInicio = y;
                // Repetir encabezado en página nueva
                pdf.setFillColor(...colorNegro);
                pdf.rect(margin, y, usableW, encH, 'F');
                pdf.setFontSize(7);
                pdf.setFont("helvetica", "bold");
                pdf.setTextColor(255, 255, 255);
                pdf.text(columnas[0] + " (cont.)", margin + 3, y + 4);
                pdf.text(columnas[1], margin + usableW - 3, y + 4, { align: "right" });
                y += encH;
            }

            pdf.setFillColor(...(i % 2 === 0 ? colorFondoCard : [255, 255, 255]));
            pdf.rect(margin, y, usableW, filaH, 'F');
            pdf.setFontSize(7);
            pdf.setFont("helvetica", "normal");
            pdf.setTextColor(...colorGrisMedio);
            const labelTrunc = String(label).length > 45
                ? String(label).substring(0, 43) + "…"
                : String(label);
            pdf.text(labelTrunc, margin + 3, y + 4);
            pdf.text(String(valores[i]), margin + usableW - 3, y + 4, { align: "right" });
            y += filaH;
        });

        // Borde exterior
        pdf.setDrawColor(...colorGrisClaro);
        pdf.setLineWidth(0.2);
        pdf.rect(margin, yTablaInicio, usableW, y - yTablaInicio);

        y += 6;
    }

    // ── Agrega título + gráfica + tabla ───────────────────────────
    async function agregarGrafica(canvasId, titulo, altoCm, chart, columnas) {
        const altoMm = altoCm * 10;

        if (pageH - y - margin < altoMm + 14) {
            pdf.addPage();
            agregarEncabezadoContinuacion();
            y = 16;
        }

        // Título de sección (tu estilo COLOR_PRIMARY con fondo COLOR_LIGHT_BG)
        pdf.setFillColor(...COLOR_LIGHT_BG);
        pdf.roundedRect(margin, y, usableW, 8, 2, 2, "F");
        pdf.setTextColor(...COLOR_PRIMARY);
        pdf.setFontSize(8);
        pdf.setFont("helvetica", "bold");
        pdf.text(titulo, margin + 3, y + 5.5);
        y += 10;

        // Imagen del canvas
        const canvas = document.getElementById(canvasId);
        try {
            const imgData = canvas.toDataURL("image/png", 1.0);
            pdf.addImage(imgData, "PNG", margin, y, usableW, altoMm);
            y += altoMm + 5;
        } catch {
            pdf.setTextColor(...COLOR_GRAY);
            pdf.setFontSize(8);
            pdf.text("Sin datos en este período", margin, y + 5);
            y += 12;
            return;
        }

        // Tabla debajo de la gráfica
        if (chart && columnas) {
            dibujarTabla(chart, columnas);
        }

        y += 4;
    }

    // ── GRÁFICAS ──────────────────────────────────────────────────
    await agregarGrafica("graficaAulas",    "Aulas con más incidencias",      5.5, chartAulas,    ["Aula",     "Incidencias" ]);
    await agregarGrafica("graficaEquipos",  "Equipos más afectados (Top 10)", 6.5, chartEquipos,  ["Equipo",   "Incidencias" ]);
    await agregarGrafica("graficaPersonal", "Estadísticas de Personal",       6.5, chartPersonal, ["Personal", "Resoluciones"]);

    // ── PIE DE PÁGINA ─────────────────────────────────────────────
    const totalPaginas = pdf.internal.getNumberOfPages();
    for (let p = 1; p <= totalPaginas; p++) {
        pdf.setPage(p);
        pdf.setDrawColor(...colorGrisClaro);
        pdf.line(margin, pageH - 10, pageW - margin, pageH - 10);
        pdf.setTextColor(...colorGrisMedio);
        pdf.setFontSize(7);
        pdf.setFont("helvetica", "normal");
        pdf.text("Departamento de Sistemas", margin, pageH - 6);
        pdf.text(`Página ${p} de ${totalPaginas}`, pageW - margin, pageH - 6, { align: "right" });
    }

    restaurarResolucionGraficas();

    const nombreArchivo = `estadisticas_incidencias_${fecha1}_${fecha2}.pdf`;
    pdf.save(nombreArchivo);

    btnExportar.disabled = false;
    btnGenerar.disabled  = false;
    btnExportar.innerHTML = '<i class="bi bi-file-earmark-pdf"></i> Exportar PDF';
}

// ── Utilidades ────────────────────────────────────────────────────────────
function formatearFecha(fechaISO) {
    if (!fechaISO) return "";
    const [y, m, d] = fechaISO.split("-");
    return `${d}/${m}/${y}`;
}

function imagenABase64(src) {
    return new Promise((resolve, reject) => {
        const img = new Image();
        img.crossOrigin = "anonymous";
        img.onload = () => {
            const c = document.createElement("canvas");
            c.width  = img.naturalWidth;
            c.height = img.naturalHeight;
            c.getContext("2d").drawImage(img, 0, 0);
            resolve(c.toDataURL("image/png"));
        };
        img.onerror = reject;
        img.src = src;
    });
}

function aumentarResolucionGraficas(factor = 3) {
    [chartAulas, chartEquipos, chartPersonal].forEach(chart => {
        if (!chart) return;
        chart.options.devicePixelRatio = factor;
        chart.resize();
        chart.update();
    });
}

function restaurarResolucionGraficas() {
    [chartAulas, chartEquipos, chartPersonal].forEach(chart => {
        if (!chart) return;
        chart.options.devicePixelRatio = 1;
        chart.resize();
        chart.update();
    });
}