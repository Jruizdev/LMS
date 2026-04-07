async function NotificarJefeAutoasignacion (nombre, nomina, curso, correo) {
    // Enviar correo a jefe notificando autoasignación de curso de un empleado
    const formCorreo = new FormData ();
    formCorreo.append ('nombre', nombre);
    formCorreo.append ('nomina', nomina);
    formCorreo.append ('curso', curso);
    formCorreo.append ('correo', correo);

    await fetch ('http://10.25.1.24/BMXQ_PR_OEE/EnvioAutoasignacion.php', {
        method: 'POST',
        body: formCorreo
    });
}

async function NotificarUsuarios (correos, cursos, actualizacion = 'false') {

    // Enviar correo electrónico notificando a los usuarios de la asignación
    const formCorreo = new FormData ();
    formCorreo.append ('cursos', cursos);
    formCorreo.append ('correos', correos);
    formCorreo.append ('actualizacion', actualizacion);

    await fetch ('http://10.25.1.24/BMXQ_PR_OEE/EnvioEmailCursos.php', {
        method: 'POST',
        body: formCorreo
    });
}