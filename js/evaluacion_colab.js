const max_colaboradores = 5;
let tabla_colaboracion = null;
let timeout_cambio_nombre = null;

function ActualizarTablaColaboracion () {
    if (!tabla_colaboracion) {
        tabla_colaboracion =	document.querySelector ('.tabla-colaboracion tbody');
    }
}

function ObtenerRegistrosDisponibles () {
    ActualizarTablaColaboracion ();

    // Número de filas agregadas a la tabla de colaboración
    const regstros_disponibles = tabla_colaboracion.querySelectorAll ('tr.empleado');
    return regstros_disponibles.length;
}

function ObtenerColaboradores () {
    ActualizarTablaColaboracion ();
    const nominas = [];
    const nombres = [];
    const colaboradores = tabla_colaboracion.querySelectorAll ('tr:not(.deshabilitado).empleado');
    
    colaboradores.forEach ((colaborador) => {
        const nomina = colaborador.querySelector ('input').value;
        const nombre = colaborador.querySelector ('[data-columna="nombre"] p').textContent;

        // Omitir campos de nómina vacíos 
        if (nomina == '') return;

        nominas.push (nomina);
        nombres.push (nombre);
    });

    return {
        nominas: nominas,
        nombres: nombres
    }
}

function EmpleadoEnlistado (no_nomina) {
    const lista_empleados = ObtenerColaboradores ()['nominas'];
    const coincidencias = lista_empleados.filter (element => element === no_nomina).length;

    // El empleado ya fué agregado a la lista
    return coincidencias > 1;
}

async function BuscarNomina (campo) {
    const nomina = campo.value;
    const fila_nombre = campo.closest ('tr');
    const columna_nombre = fila_nombre.querySelector ('[data-columna="nombre"]');
    const nombre_empleado = columna_nombre.querySelector ('p');

    const MarcarRegistro = (tr, marcado) => {
        const columnas_registro = tr.querySelectorAll ('td');
        tr.classList.add ('deshabilitado');
        columnas_registro.forEach ((columna) => {
            columna.classList.add (marcado);
        });
    }
    const DesmarcarRegistro = (tr) => {
        const columnas_registro = tr.querySelectorAll ('td');
        tr.classList.remove ('deshabilitado');
        columnas_registro.forEach ((columna) => {
            columna.classList.remove ('table-warning', 'table-danger');
        });
    }

    const CambiarColumnaNombre = (texto) => {
        nombre_empleado.style.opacity = 0;
        clearTimeout (timeout_cambio_nombre);

        // Realizar cambio suavizado de texto de la columna nombre
        timeout_cambio_nombre = setTimeout (() => {
            nombre_empleado.textContent = texto;
            nombre_empleado.style.opacity = 1;
        }, 300);
    };

    //nombre_empleado.textContent = 'Buscando empleado...';
    CambiarColumnaNombre ('Buscando empleado...');

    await fetch ('modulos/GestionUsuarios.php?obtener_empleado=' + nomina, {
        method: 'GET'
    })
    .then ((resp) => resp.json())
    .then ((empleado) => {
        if (!empleado) {
            // No se encontró el empleado
            MarcarRegistro (fila_nombre, 'table-danger');
            CambiarColumnaNombre ('No se encontró el empleado');
            return;
        }
        // Mostrar nombre del empleado encontrado
        DesmarcarRegistro (fila_nombre);
        CambiarColumnaNombre (empleado ['nombre']);

        // Asegurar que el empleado no se encuentre previamente en la lista
        if (EmpleadoEnlistado (nomina)) {
            MarcarRegistro (fila_nombre, 'table-warning');
            CambiarColumnaNombre ('Éste empleado ya se encuentra en la lista');
        }
    });
}

function AgregarEmpleadoEvaluacion () {
    ActualizarTablaColaboracion ();

    // Asegurar que el número de colaboradores se mantenga dentro del límite
    if (ObtenerRegistrosDisponibles () >= max_colaboradores) {
        MostrarMensaje (
            'No es posible agregar más empleados', 
            'El número máximo permitido de empleados por evaluación es de ' + max_colaboradores + '.', 
            null, 
            icono_error
        ); return;
    }

    const nuevo_registro = document.createElement ('tr');
    nuevo_registro.style.animation = 'nuevaFilaTablaBottom .4s';
    nuevo_registro.classList.add ('empleado');
    nuevo_registro.innerHTML =
    '<td><input class="form-control" type="number" min="0" oninput="BuscarNomina (this)"></td>' +
    '<td class="col-8" data-columna="nombre"><p class="my-auto text-start text-truncate"></p></td>' +
    '<td class="text-center">' +
        '<button title="Quitar empleado de la lista" onclick="QuitarEmpleadoEvaluacion (this)">' +
            '<svg class="my-auto" width="30" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill=var(--entidad-primario) d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM184 232l144 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-144 0c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg>' +
        '</button>' +
    '</td>';
    tabla_colaboracion.appendChild (nuevo_registro);
    nuevo_registro.querySelector ('input').focus ();
    
    window.scrollTo({
        top: document.body.scrollHeight,
        behavior: 'smooth'
    });
}

function QuitarEmpleadoEvaluacion (btn) {
    const fila = btn.closest ('tr');
    fila.remove ();
}