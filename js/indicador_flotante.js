var collapse_indicador = null;

function EliminarIndicadoresFlotantes () {
    const $ventanas_flotantes = document.querySelectorAll ('.ventana-flotante');
    $ventanas_flotantes.forEach (($ventana) => $ventana.remove ());
}

function MostrarIndicacionFlotante (msg_corto, msg_detalle, tipo_msg) {

    const $barra_nav =       document.querySelector ('nav');
    const $contenedor =      document.createElement ('div');
    const ventana_flotante = $temp_ventana_flotante.content.cloneNode (true);
    const $mensaje_corto =   ventana_flotante.querySelector ('[data-tipo="msg-corto"]');
    const $detalle =         ventana_flotante.querySelector ('[data-tipo="msg-detalle"]');
    const $alturaBarraNav =  $barra_nav.offsetHeight;
    const $btn_cerrar =      ventana_flotante.querySelector ('button.btn-x');

    const collapse = ventana_flotante.querySelector ('#detalleMsg');
    const iniciarTemporizador = ($contenedor) => {
        $contenedor.classList.add ('temp-msg');
        const time_out = setTimeout (() => {
            $contenedor.remove();
        }, 10500);
        return time_out;
    };
    const detenerTemporizador = ($contenedor, time_out) => {
        $contenedor.classList.remove ('temp-msg');
        clearTimeout (time_out);
    };
    const icono = (tipo_msg) => {
        switch (tipo_msg) {
            case 'error': 
                // Ícono de error
                return '<svg class="ms-2" style="max-width: 2.5rem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#58151c" d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480L40 480c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24l0 112c0 13.3 10.7 24 24 24s24-10.7 24-24l0-112c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>';
            case 'success': 
                // Ícono de éxito
                break;
            default:
                return '';
        }
    };
    // Iniciar temporizador al mostrar ventana flotante
    let timeOut = iniciarTemporizador ($contenedor);
    
    collapse.addEventListener ('hide.bs.collapse', event => {
        timeOut = iniciarTemporizador ($contenedor);
    });
    collapse.addEventListener ('show.bs.collapse', event => {
        detenerTemporizador ($contenedor, timeOut);
     });

    // Limpiar posibles ventanas flotantes anteriores
    EliminarIndicadoresFlotantes ();

    $contenedor.classList.add ('ventana-flotante');

    $mensaje_corto.innerHTML =  msg_corto + icono (tipo_msg);
    $detalle.innerHTML =        msg_detalle;

    // Botón para eliminar ventana flotante
    $btn_cerrar.onclick = () => $contenedor.remove ();

    // Mostrar ventana flotante en la parte inferior de la barra de navegación
    $contenedor.appendChild (ventana_flotante);
    $contenedor.style.top =         ($alturaBarraNav + 10) + 'px';
    $contenedor.style.left =        "50%";
    $contenedor.style.transform =   "translateX(-50%)";

    // Agregar ventana flotante
    $barra_nav.insertAdjacentElement ('afterend', $contenedor);
    document.body.appendChild (ventana_flotante);

    collapse_indicador = new bootstrap.Collapse (
        collapse, {toggle: false}
    );
} 

function AgregarListenersErrores (inputs) {
    if (typeof inputs[Symbol.iterator] != 'function') return;

    // Agregar listeners para indicar campos que deben ser revisados y llenados
    inputs.forEach (({input, msg}) => {
        document.querySelector ('#' + input).onclick = (e) => {
            const campo = document.querySelector ('[data-tipo="' + input + '"]');
            collapse_indicador.hide ();
            scrollAElemento (campo);
            campo.focus ();
        };
    });
}