function AgregarCursoInteres (btn, empleado) {
    
    const componente_curso = btn.closest ('[data-componente="curso-disponible"]');
    const id_curso =         componente_curso.dataset.id_curso;
    const version =          componente_curso.dataset.version;
    const nombre =           componente_curso.dataset.nombre;

    const ActualizarPaginas = (num_pags) => {
        const indicadores_paginas = document.querySelectorAll ('.pagina');

        // Remover última página
        if (indicadores_paginas.length > num_pags && num_pags >= 1) {
            indicadores_paginas [indicadores_paginas.length - 1].remove ();
        }
    };

    const NotificarAJefe = async () => {
        const formNotificacion = new FormData ();
        formNotificacion.append ('consultar', 'info_jefe');
        formNotificacion.append ('no_nomina', empleado);

        await fetch ('modulos/Consultar.php', {
            method: 'POST',
            body: formNotificacion
        })
        .then ((res) => res.json ())
        .then ((info) => {
            const correo = info ['Correo_notificacion'];
            const nombre_empleado = info ['nombre'];

            // Enviar correo de notificación al Jefe
            NotificarJefeAutoasignacion (nombre_empleado, empleado, nombre, correo); 
        });
    }

    const ActualizarCursosDisponibles = async () => {
        const contentedor_cursos_disp = document.querySelector ('.cursos-disponibles');
        const num_registros =           contentedor_cursos_disp.parentElement.parentElement.dataset.registros ?
                                        contentedor_cursos_disp.parentElement.parentElement.dataset.registros : '';
        const pag_actual =              contentedor_cursos_disp.parentElement.parentElement.dataset.pag_actual ?
                                        contentedor_cursos_disp.parentElement.parentElement.dataset.pag_actual : '';
        const formDataDisp =            new FormData ();
       
        formDataDisp.append ('consultar', 'cursos-disponibles');
        formDataDisp.append ('no_nomina', empleado);
        formDataDisp.append ('respuesta', 'json');
        formDataDisp.append ('num_registros', num_registros);
        formDataDisp.append ('pag_actual', pag_actual);

        await fetch ('modulos/Consultar.php', {
            method: 'POST',
            body: formDataDisp
        })
        .then ((res) => res.json ())
        .then ((cursos) => {
            const num_pag =             Math.ceil (cursos ['total_ci'] / num_registros);
            const cursos_disponibles =  Array.from (cursos ['lista_ci']);
            const elementos_disp =      [];
        
            // Actualizar indicadores de página
            ActualizarPaginas (num_pag);

            if (!cursos_disponibles.length && pag_actual >= num_pag && num_pag > 1) {
                window.location.replace ('catalogo_cursos.php?pag=' + num_pag);
            }

            cursos_disponibles.forEach ((curso_disponible) => {
                const nuevo = document.createElement ('div');

                nuevo.setAttribute ('data-componente',  'curso-disponible');
                nuevo.setAttribute ('data-nombre',       curso_disponible ['Nombre']);
                nuevo.setAttribute ('data-descripcion',  curso_disponible ['Descripcion']);
                nuevo.setAttribute ('data-fecha',        curso_disponible ['Fecha']);
                nuevo.setAttribute ('data-id_curso',     curso_disponible ['Id_curso']);
                nuevo.setAttribute ('data-version',      curso_disponible ['Version']);
                nuevo.setAttribute ('data-portada',      curso_disponible ['Portada'] != null ? curso_disponible ['Portada'] : '');
                elementos_disp.push (nuevo);
            });
            
            if (!cursos ['lista_ci'].length) {
                const contenedor_vacio = document.querySelector ('[data-tipo="catalogo"]');
                contenedor_vacio.innerHTML = 
                '<div class="d-flex flex-column align-items-center p-4">' +
                    '<svg class="w-6 h-6 text-gray-800 dark:text-white mb-3" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="80" viewBox="0 0 24 24">' +
                        '<path stroke="#CCC" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>' +
                    '</svg>' +
                    '<h4 class="text-muted"><b>No hay más cursos disponibles</b></h4>' +
                    '<h6 class="text-muted">Por el momento no hay más cursos por mostrar</h6>' +
                '</div>';
                return;
            }

            contentedor_cursos_disp.innerHTML = '';
            AgregarCursosDisponibles (elementos_disp, contentedor_cursos_disp);
        });
    };

    const ActualizarCursosPendientes = async () => {
        const contenedor_cursos_p = document.querySelector ('.cursos-pendientes');

        // No hay ventana de Cursos Pendientes por actualizar
        if (!contenedor_cursos_p) return;

        const formDataCP = new FormData ();
        formDataCP.append ('consultar', 'cursos_pendientes');
        formDataCP.append ('no_nomina', '2572');
        formDataCP.append ('respuesta', 'json');

        await fetch ('modulos/Consultar.php', {
            method: 'POST',
            body: formDataCP
        })
        .then ((res) => res.json ())
        .then ((cursos) => {
            const cursos_pendientes = Array.from (cursos);
            const elementos_cp =      [];

            cursos_pendientes.forEach ((curso_pendiente) => {
                const nuevo = document.createElement ('div');
                nuevo.setAttribute ('data-componente',  'elemento-lista-CP');
                nuevo.setAttribute ('data-nombre',       curso_pendiente ['Nombre']);
                nuevo.setAttribute ('data-descripcion',  curso_pendiente ['Descripcion']);
                nuevo.setAttribute ('data-version',      curso_pendiente ['Version']);
                nuevo.setAttribute ('data-id_curso',     curso_pendiente ['Id_curso']);
                nuevo.setAttribute ('data-asignacion',   curso_pendiente ['Asignacion']);
                nuevo.setAttribute ('data-portada',      curso_pendiente ['Portada']);
                
                if (curso_pendiente ['Fecha_limite'] != null) 
                    nuevo.setAttribute ('data-fecha_limite', curso_pendiente ['Fecha_limite']);

                elementos_cp.push (nuevo);
            });

            //const contenedor_cursos_p = document.querySelector ('.cursos-pendientes');
            const cp_actuales =         contenedor_cursos_p.querySelectorAll ('[data-componente="elemento-lista-CP"]');
            const max_reg =             parseInt (contenedor_cursos_p.dataset.num_reg);
            
            if (cp_actuales.length < max_reg) {
                contenedor_cursos_p.innerHTML = '';
                AgregarCursosPendientes (elementos_cp, contenedor_cursos_p);
            }
        });
    };

    const AutoAsignarCurso = () => {
        return new Promise (async (resolve, ) => {
            const formData = new FormData ();
            formData.append ('asignar_ci_emp', empleado);
            formData.append ('cursos_i', id_curso);
            formData.append ('versiones', version);
            formData.append ('fecha_limite', '');
            formData.append ('asignacion', empleado);

            await fetch ('modulos/GestionUsuarios.php', {
                method: 'POST',
                body: formData
            })
            .then (() => {})
            .then (() => {
                ActualizarCursosDisponibles ();
                ActualizarCursosPendientes ();
                NotificarAJefe ();
            });
            resolve (true);
        });
    };

    MostrarMensaje (
        'Agregar curso', 
        '<p>Se agregará el curso "<b>'+ nombre +'</b>" a tu lista de Cursos Pendientes,</p>' +
        '<p class="mb-0"><b>¿Deseas continuar?</b></p>', 
        AutoAsignarCurso, 
        icono_pregunta, 
        true, true, false, true
    );
}