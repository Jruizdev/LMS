<?php
require_once ('util/BD.php');

function ObtenerEmailsCP () {
    $BDcursos = new BDcursos ();

    // Recuperar correos de los usuarios con cursos pendientes
    $emails = $BDcursos->ObtenerEmailsCP ();
    $cadena_emails = '';

    foreach ($emails as $email) {
        if ($email ['email'] == '') continue;
        $cadena_emails .= $email ['email'].',';
    }
    // Convertir lista de emails en una cadena de texto
    $cadena_emails = substr ($cadena_emails, 0, -1);
    return $cadena_emails;
}
?>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body></body>
    <script>
        var lista_email = "<?php echo ObtenerEmailsCP () ?>";

        if (lista_email != '') {
            RecordarCursosPendientes ();
        }

        async function RecordarCursosPendientes () {
            const formData = new FormData ();
            formData.append ('correos', lista_email);
            formData.append ('actualizacion', 'recordatorio');

            // Llamar servicio para enviar correo de recordatorio a los empleados con cursos pendientes
            await fetch ('http://10.25.1.24/BMXQ_PR_OEE/EnvioEmailCursos.php', {
                method: 'POST',
                body: formData
            })
            .then (() => {
                console.log ("Se ha enviado el recordatorio");
            })
            .catch (() => {
                console.error ("No fue posible enviar el recordatorio");
            });
        }
    </script>
</html>