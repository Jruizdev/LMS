const modal_configuracion = new bootstrap.Modal (
    '#modalConfiguracion', { keyoard: false }
);
const modal_visor = new bootstrap.Modal (
    '#modalReporte', { keyoard: false }
);

async function GenerarReporte (boton, titulo, tipo, fecha_inicio, fecha_fin, nomina = null) {

    // Comprobar si hay algún campo de criterio de búsqueda
    const in_busqueda = document.querySelector ('.tag');
    const filtrado_cursos = in_busqueda ? in_busqueda.textContent : '';
    
    const btn_rollback = btnModoCarga (boton);
    const formData =     new FormData ();

    formData.append ('accion', 'generar_pdf');
    formData.append ('titulo', titulo);
    formData.append ('tipo', tipo);
    formData.append ('inicio', fecha_inicio.value);
    formData.append ('fin', fecha_fin.value);
    formData.append ('nomina', nomina);
    formData.append ('filtrar', filtrado_cursos);

    fecha_inicio.max =  ObtenerFechaActual ();
    fecha_fin.max =     ObtenerFechaActual ();

    const validar_minmax = (fecha) => { 
        const fecha_frmt =  new Date (fecha.value);
        const fecha_min =   fecha.min ? new Date (fecha.min) : false;
        const fecha_max =   fecha.max ? new Date (fecha.max) : false;
        
        if (fecha_min && fecha_frmt < fecha_min) return {
            error: 'La fecha de inicio no puede ser anterior a ' + fecha.min + '.'
        }
        if (fecha_max && fecha_frmt > fecha_max) return {
            error: 'La fecha de inicio no puede ser posterior a ' + fecha.max + '.'
        }
        return false;
    };

    const validar_periodo = () => {
        const fecha_inicio_frmt = new Date (fecha_inicio.value);
        const fecha_fin_frmt =    new Date (fecha_fin.value);
        
        const error_fecha_inicio =  validar_minmax (fecha_inicio);
        const error_fecha_fin =     validar_minmax (fecha_fin);

        if (error_fecha_inicio) return error_fecha_inicio;
        if (error_fecha_fin)    return error_fecha_fin;
        if (fecha_inicio_frmt > fecha_fin_frmt) return {
            error: 'La fecha de inicio no puede ser posterior a la fecha de fin.'
        }
        if (fecha_fin_frmt < fecha_inicio_frmt) return {
             error: 'La fecha final de consulta no puede ser anterior a la fecha de inicio.'
        }
    };

    // Validar fechas míminas y fechas máximas
    const error_periodo = validar_periodo ();

    if (error_periodo) {
        // Hubo un error con la validación de las fechas
        modal_configuracion.hide ();
        btnModoNormal (boton, btn_rollback);
        MostrarMensaje (
            'Hubo un error al intentar generar el reporte', 
            error_periodo ['error'], 
            null, icono_error
        );
        return;
    }

    await fetch ('./modulos/util/PDF.php', {
        method: 'POST',
        body: formData
    })
    .then ((resp) => resp.text ())
    .then ((archivo) => {

        modal_configuracion.hide ();

        if (dispositivoMovil ()) {
            // Descargar archivo en dispositivos móviles
            descargarPDF (archivo, titulo + '.pdf');
            return;
        }
        
        // Visualizar archivo en computadora
        const visor_pdf =           document.querySelector ('#modalReporte iframe');
        visor_pdf.style.maxHeight = "100%";
        visor_pdf.src =             "data:application/pdf;base64," + archivo;

        modal_visor.show ();
    })
    .finally (() => {
        // Regresar botón a su estado normal
        btnModoNormal (boton, btn_rollback);
    });
}
