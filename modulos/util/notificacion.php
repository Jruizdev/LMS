<?php
require_once ('../phpoffice/vendor/autoload.php');
require_once ('BD.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$archivo = null;
$nombre_archivo = null;

if (isset ($_GET ['notificar'])) {
    switch ($_GET ['notificar']) {
        case 'rh-cursos-pendientes': $archivo = NotificarCursosPendientesRH (); break;
    }
}

function ObtenerFrecuencia () {
    $frecuencia = isset ($_GET ['frecuencia']) ? $_GET ['frecuencia'] : null;
    echo $frecuencia;
}

function ObtenerCorreosCPSemanal () {
    $bd =                   new BDcursos ();
    $lista_correos =        [];
    $correos_notificacion = $bd->ObtenerCorreosNotificacion ('cursos-pendientes-semanal');

    foreach ($correos_notificacion as $correo) {
        array_push ($lista_correos, $correo ['email']);
    }
    echo implode (', ', $lista_correos);
}

function ObtenerCorreosCPMensual () {
    $bd =                   new BDcursos ();
    $lista_correos =        [];
    $correos_notificacion = $bd->ObtenerCorreosNotificacion ('cursos-pendientes-mensual');

    foreach ($correos_notificacion as $correo) {
        array_push ($lista_correos, $correo ['email']);
    }
    echo implode (', ', $lista_correos);
}

function NotificarCursosPendientesRH () {
    global $nombre_archivo;

    $bd =                   new BDcursos ();
    $registros =            $bd->ObtenerReportePendientes ();
    $correos_notificacion = $bd->ObtenerCorreosNotificacion ('cursos-pendientes');
    $spreadsheet =          new Spreadsheet ();
    $sheet =                $spreadsheet->getActiveSheet ();

    // Cabeceras de la tabla
    $sheet->setCellValue ('B2', 'Nombre del curso');
    $sheet->setCellValue ('C2', 'Versión');
    $sheet->setCellValue ('D2', 'Nombre del empleado');
    $sheet->setCellValue ('E2', 'Departamento');
    $sheet->setCellValue ('F2', 'Número de nómina');
    $sheet->setCellValue ('G2', 'Fecha');

    // Arrays de formato
    $cabecera = [
        'fill' => [
            'fillType'   => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E26B0A'],
        ],
        'font' => [ 'color' => ['rgb' => 'FFFFFF']]
    ];
    $colorGrisClaro = [
        'fill' => [
            'fillType'   => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'F2F2F2'],
        ],
    ];
    $bordes = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN, 
                'color' => ['argb' => 'FF000000'], 
            ],
        ],
    ];

    // Formato de las columnas y las celdas
    foreach (range ('B', 'G') as $columnID) {
        $sheet->getColumnDimension ($columnID)->setAutoSize (true);
    }
    $sheet->getStyle ('C:C')->getAlignment ()->setHorizontal (Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle ('F:F')->getAlignment ()->setHorizontal (Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle ('G:G')->getAlignment ()->setHorizontal (Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle ('B2:G2')->getAlignment ()->setHorizontal (Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle ('B2:G2')->getFont ()->setBold (true);

    foreach ($registros as $i => $registro) {
        $fecha = new DateTime ($registro ['Fecha']);
        $sheet->setCellValue ('B'.($i + 3), $registro ['Nombre']);
        $sheet->setCellValue ('C'.($i + 3), $registro ['Version']);
        $sheet->setCellValue ('D'.($i + 3), $registro ['nombre']);
        $sheet->setCellValue ('E'.($i + 3), $registro ['departamento']);
        $sheet->setCellValue ('F'.($i + 3), $registro ['no_nomina']);
        $sheet->setCellValue ('G'.($i + 3), $fecha->format ('d/m/Y'));

        if ($i % 2 == 0) {
            $sheet->getStyle('B'.($i + 3).':G'.($i + 3))->applyFromArray ($colorGrisClaro);
        }
    }
    $no_registros = count ($registros);
    $rango_tabla = 'B2:G'.($no_registros + 2);
    $sheet->getStyle ($rango_tabla)->applyFromArray ($bordes);
    $sheet->getStyle ('B2:G2')->applyFromArray ($cabecera);
    $sheet->setTitle ('Cursos Pendientes');

    $writer = new Xlsx ($spreadsheet);

    // Usar buffer de salida en lugar de archivo temporal
    ob_start         ();
    $writer->save    ('php://output');
    $archivo_excel = ob_get_contents ();
    ob_end_clean     ();

    // Convertir a base64
    $base64 =           base64_encode ($archivo_excel);
    $nombre_archivo =  date ("Y-m-d").'_BMXQ_LMS_Cursos_Pendientes.xlsx';

    return $base64;
}

function ObtenerNombreArchivo () {
    global $nombre_archivo;
    echo $nombre_archivo;
}
function ObtenerArchivo () {
    global $archivo;
    echo $archivo;
}

?>
<!DOCTYPE html>
<html>
    <head></head>
    <body><span id="estatus">Enviando correo...</span></body>
    <script>
        const estatus =      document.querySelector ('#estatus');
        var frecuencia =     '<?php ObtenerFrecuencia () ?>';
        var destinatarios =  frecuencia == 'mensual' ? 
                             '<?php ObtenerCorreosCPMensual () ?>' :
                             '<?php ObtenerCorreosCPSemanal () ?>';
        var asunto =         'Seguimiento de Cursos Pendientes';
        var body =           null;
        var archivo =        "<?php ObtenerArchivo () ?>";
        var nombre_archivo = "<?php ObtenerNombreArchivo () ?>";
        var tipo_mime =      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

        if (archivo) NotificarCursosPendientes ();

        function ObtenerCuerpoCorreo () {
            return new Promise (async (resolve, reject) => {
                let url = '../../uploads/html/aviso_pendientes.html';

                if (frecuencia == 'mensual') 
                    url = '../../uploads/html/aviso_pendientes_mensual.html';

                await fetch (url)
                .then ((resp) => resp.text())
                .then ((mensaje) => {
                    body = mensaje;
                    resolve (true);
                });
            });
        }
        
        async function NotificarCursosPendientes () {
            await ObtenerCuerpoCorreo ()
            .then (async () => {
                const formData = new FormData ();
                formData.append ('destinatarios', destinatarios);
                formData.append ('asunto', asunto);
                formData.append ('mensaje', body);
                formData.append ('archivo', archivo);
                formData.append ('nombre_archivo', nombre_archivo);
                formData.append ('tipo_mime', tipo_mime);
                formData.append ('accion', 'enviar-archivo');

                await fetch ('http://10.25.1.24/BMXQ_PR_OEE/EnvioArchivos.php', {
                    method: 'POST',
                    body: formData
                })
                .then ((resp) => resp.text ())
                .then ((resultado) => {
                    estatus.textContent = resultado;
                });
            });
        }
    </script>
</htm>