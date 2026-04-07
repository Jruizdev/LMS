async function GenerarCertificado (id_curso, no_nomina, nombre = null) {
    const formData = new FormData ();
    formData.append ('consultar', 'certificado_ci');
    formData.append ('id_curso', id_curso);
    formData.append ('no_nomina', no_nomina);

    await fetch ('modulos/Consultar.php', {
        method: 'POST',
        body: formData
    })
    .then ((res) => res.json ())
    .then (async (info) => {
        const formData = new FormData ();
        formData.append ('accion', 'generar_certificado_ci');
        formData.append ('curso',   info ['Curso']);
        formData.append ('nombre',  info ['Nombre']);
        formData.append ('fecha',   info ['Fecha']);

        // Eliminar posibles modal previamente abiertos
        const modal_previos = document.querySelectorAll ('.modal-certificado');
        modal_previos.forEach ((modal_previo) => modal_previo.remove ());

        // Mostrar modal para visualizar certificado
        const modal =        $temp_mod_certificado.content.cloneNode (true).querySelector ('.modal');
        const bs_modal =     new bootstrap.Modal (modal);
        const btns_cerrar =  modal.querySelectorAll ('button[data-componente="btn-cerrar"]');
        const controller =   new AbortController();
        const signal =       controller.signal;

        modal.addEventListener ('hidden.bs.modal', () => modal.remove ());

        btns_cerrar.forEach ((btn) => {
            btn.addEventListener ('click', (e) => {
                // Remover modal del documento
                e.preventDefault ();
                btn.blur ();
                controller.abort ();
                bs_modal.hide ();
            });
        });

        document.body.appendChild (modal);
        bs_modal.show ();

        // Generar certificado PDF con la información recibida
        await fetch ('modulos/util/PDF.php', {
            method: 'POST',
            body: formData,
            signal: signal
        })
        .then ((res) => res.text ())
        .then ((archivo) => {

            if (dispositivoMovil ()) {
                // Descargar archivo en dispositivos móviles
                descargarPDF (archivo, 'certificado.pdf');
                bs_modal.hide ();
                return;
            }

            const visor = modal.querySelector ('.modal-body');
            const frame = document.createElement ('iframe');

            frame.src = "data:application/pdf;base64," + archivo;
            visor.innerHTML = '';
            visor.appendChild (frame);
        })
        .catch (() => {
            bs_modal.hide ();
            MostrarMensaje (
                'Error al generar el certificado', 
                'No fue posible generar el certificado. Por favor, inténtalo de nuevo más tarde.', 
                 null, icono_error
            );
        });
    })
    .catch (() => {
        bs_modal.hide ();
        MostrarMensaje (
            'Error al obtener el certificado', 
            'No fue posible recuperar la información del curso aprobado. Por favor, inténtalo de nuevo más tarde.', 
            null, icono_error
        );
    });
}