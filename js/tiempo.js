function ObtenerFechaActual () {

    // Devolver fecha actual, formateada en YYYY-MM-DD
    const fecha = new Date ();
    const yyyy = fecha.getFullYear ();
    const mm = String (fecha.getMonth () + 1).padStart (2, '0');
    const dd = String (fecha.getDate ()).padStart (2, '0');

    return `${yyyy}-${mm}-${dd}`;
};