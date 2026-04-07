<?php
require_once ('BD.php');
require_once ('usuario.php');
require_once ('../dompdf/vendor/autoload.php');

use Dompdf\Dompdf;

if (isset ($_POST ['accion'])) {
    switch ($_POST ['accion']) {
        case 'generar_pdf': GenerarPDF (); break;
        case 'generar_certificado_ci': GenerarCertificadoCI (); break;
    }
}

function clampString ($string, $max_char, $sufijo = '...') {
    if (strlen($string) > $max_char) {
        $clamped = wordwrap($string, $max_char - strlen ($sufijo));
        $clamped = explode("\n", $clamped)[0];
        return $clamped . $sufijo;
    }
    return $string;
}

function GenerarCertificadoCI () {
    if (
        !isset ($_POST ['curso']) || 
        !isset ($_POST ['nombre']) ||
        !isset ($_POST ['fecha'])
    ) return;

    // Convertir imágenes a Base 64
    $img_aftermarket =  '<img id="aftermarket" src="data:img/png; base64, '.base64_encode (file_get_contents('../../uploads/placeholder/entidad_B_Aftermarket_CMYK.png')).'">';
    $img_sello =        '<img src="data:img/png; base64, '.base64_encode (file_get_contents('../../uploads/placeholder/icono_sello.png')).'" width="120px"/>';
    $img_entidad =        '<img src="data:img/png; base64, '.base64_encode (file_get_contents('../../uploads/placeholder/mail_logo.png')).'" width="200">';

    $meses = array (
        '01' => 'enero',
        '02' => 'febrero',
        '03' => 'marzo',
        '04' => 'abril',
        '05' => 'mayo',
        '06' => 'junio',
        '07' => 'julio',
        '08' => 'agosto',
        '09' => 'septiembre',
        '10' => 'octubre',
        '11' => 'noviembre',
        '12' => 'diciembre'
    );

    $curso =      $_POST ['curso'];
    $fecha =      date_create($_POST ['fecha']);
    $fecha_dia =  date_format($fecha, "d");
    $fecha_mes =  $meses [date_format($fecha, "m")];
    $fecha_anno = date_format($fecha, "Y");
    $empleado =   new Usuario ();

    $empleado->setNombre ($_POST ['nombre']);
    $nombre_curso = clampString ($curso, 120);

    // Reemplazar placeholders del documento
    $certificado = file_get_contents ('../../uploads/html/certificado.html');
    $certificado = str_replace ('{IMG_SELLO}', $img_sello, $certificado);
    $certificado = str_replace ('{IMG_entidad}', $img_entidad, $certificado);
    $certificado = str_replace ('{IMG_AFTERMARKET}', $img_aftermarket, $certificado);
    $certificado = str_replace ('{NOMBRE_CURSO}', $nombre_curso, $certificado);
    $certificado = str_replace ('{NOMBRE_EMPLEADO}', $empleado->getNombre (), $certificado);
    $certificado = str_replace ('{FECHA_CURSO}', 'El día '.$fecha_dia.' de '.$fecha_mes.' de '.$fecha_anno, $certificado);

    $pdf = $certificado;   

    // Configurar y generar PDF
    $dompdf = new DOMPDF ();
    $dompdf->set_option  ('enable_html5_parser', TRUE);
    $dompdf->set_option  ('isFontSubsettingEnabled', true);
    $dompdf->set_paper   ('A4', 'landscape');
    $dompdf->load_html   ($pdf, 'UTF-8');
    $dompdf->render      ();

    $output = $dompdf->output ();

    // Devolver archivo PDF codificado en base64
    echo base64_encode ($output);
}

function GenerarPDF () {
    if (
        !isset ($_POST ['titulo']) || 
        !isset ($_POST ['tipo']) ||
        !isset ($_POST ['inicio']) ||
        !isset ($_POST ['fin']) 
    ) return;

    $meses = [
        'January' => 	'enero',
        'February' => 	'febrero',
        'March' => 		'marzo',
        'April' => 		'abril',
        'May' => 		'mayo',
        'June' => 		'junio',
        'July' => 		'julio',
        'August' => 	'agosto',
        'September' => 	'septiembre',
        'October' =>  	'octubre',
        'November' => 	'noviembre',
        'December' => 	'diciembre'
    ];
    
    $titulo =   $_POST ['titulo'];
    $tipo =     $_POST ['tipo'];
    $inicio =   $_POST ['inicio'];
    $fin =      $_POST ['fin'];
    $nomina =   isset ($_POST ['nomina']) ? 
                $_POST ['nomina'] : null;

    $zona_horaria = new DateTimeZone('America/Mexico_City');

    // Filtrar registros de cursos aprobados
    $filtro =   isset ($_POST ['filtrar']) ?
                $_POST ['filtrar'] : '';

    $fecha_inicio =  DateTime::createFromFormat ('Y-m-d', $inicio, $zona_horaria);
    $mes_inicio = $meses [strftime ('%B', $fecha_inicio->getTimestamp ())];

    $fecha_fin =     DateTime::createFromFormat ('Y-m-d', $fin, $zona_horaria);
    $mes_fin = $meses [strftime ('%B', $fecha_fin->getTimestamp ())];

    $fecha_inicio_str = $fecha_inicio->format('d') . ' de ' . $mes_inicio . ' de ' . $fecha_inicio->format('Y'); 
    $fecha_fin_str =    $fecha_fin->format('d') . ' de ' . $mes_fin . ' de ' . $fecha_fin->format('Y'); 


    $registros = null;
    $headers =   null;
    $BD =        new BDcursos ();
    
    switch ($tipo) {
        case 'certificados':
            // Generar reporte de empleados certificados 
            $registros = $BD->ReporteEmpleadosCertificados ($inicio, $fin); 
            $headers =   array ('No.', 'Empleado', 'Nombre del curso', 'Fecha', 'Vigencia');
            break;
        case 'aprobados':
            $registros = $BD->ReporteEmpleadosAprobados ($inicio, $fin, $nomina, $filtro); 
            $headers = array ('No.', 'Nombre', 'Curso', 'Versión', 'Fecha', 'Intentos', 'Calificación');
            break;
    }

    $pdf = '
    <html>
    <style>

        * {  font-family: Arial, Helvetica, sans-serif; }
        h2 { 
            text-align: center; 
            margin-bottom: 30px; 
            margin-top: 0;
            width: 100% !important;
        }

        @page { margin: 100px 25px; }

        #header { 
            position: fixed; 
            top: -50; 
            left: 0px; 
            right: 0px; 
        }

        #aftermarket {
            position: absolute;
            top: 20;
            left: 0;
        }
        
        #logo {
            position: absolute; 
            left: 0; 
            top: -50;
        }
            
        #header h6 { 
            porition: absolute;
            top: 0;
            left: 0;
            margin-top: 40px;
            margin-left: 5px;
        }

        body {
            margin-top: 30px;
        }

        footer {
            position: fixed;
            left: 50%;
            transform: translate(-50%, -50%);
            bottom: -80px;
        }

        #tabla-registros {
            font-family: Arial, Helvetica, sans-serif; 
            font-size: .8rem; 
            border-collapse: collapse; 
            margin: 0 auto;
            margin-top: 10px; 
            width: 100% !important;
        }

        #tabla-registros th {
            border: 1px solid #000; 
            padding: 5px;
        }

        #tabla-registros td {
            border: 1px solid #000; 
            padding: 5px; 
            text-align: center;
        }
        .paginacion::after {
            content: counter(page);
        }
    </style>
    <div id="header">
        <table>
            <tr style="position: relative">
                <td>
                    <img id="aftermarket" src="data:image/png;base64,'.base64_encode(file_get_contents('../../uploads/placeholder/entidad_B_Aftermarket_CMYK.png')).'" height="100">
                </td>
                <td style="position: relative">
                    <img id="logo" src="data:image/png;base64,'.base64_encode(file_get_contents('../../uploads/placeholder/entidad_logo.png')).'" width="180">
                    <h6>:Learning Management System</h6>
                </td>
            </tr>
        </table>
    </div>
    <body>
    <h2>'.$titulo.'</h2>
    <h4 style="margin-left: 30px">Periodo de consulta:</h4> 
    <h5 style="margin-left: 30px">Del '.$fecha_inicio_str.', al '.$fecha_fin_str.'.</h5>
    <small><b style="margin-left: 30px;">*</b> Las calificaciones colaborativas se identifican con una "C" al final.</small>
    <table id="tabla-registros">
        <tr>';
        foreach ($headers as $header) {
            $pdf .= '<th>'.$header.'</th>';
        }
    $pdf .= '</tr>';

    $indice = 1;

    if (count ($registros) == 0) {
        $pdf .=
        '<tr>
            <td colspan="'.count ($headers).'">No se encontraron registros en el periodo seleccionado</td>
        </tr>';
    }

    foreach ($registros as $registro) {
        $puntaje_max = $registro ['Puntaje_max'] == 0 ? 1 : $registro ['Puntaje_max'];
        $puntuacion_colab = $registro ['Colaborativo'] == '1' ? '<b> C</b>' : '';
        $pdf .= $tipo == 'certificados' ?
        '<tr>
            <td>'.$indice.'</td>
            <td>'.$registro ['nombre'].'</td>
            <td>'.$registro ['Nombre'].'</td>
            <td style="text-align: center;">'.$registro ['Fecha'].'</td>
            <td style="text-align: center;">'.$registro ['Validez'].'</td>
        </tr>' :
        '<tr>
            <td>'.$indice.'</td>
            <td>'.$registro ['nombre'].'</td>
            <td style="text-align: left;">'.$registro ['Nombre'].'</td>
            <td style="text-align: center;">'.$registro ['No_version'].'</td>
            <td style="text-align: center;">'.$registro ['Aprobado'].'</td>
            <td style="text-align: center;">'.$registro ['Intentos'].'</td>
            <td style="text-align: center;">'.round (($registro ['Puntaje'] / $puntaje_max) * 100, 2).$puntuacion_colab.'</td>
        </tr>';
        $indice++;
    }

    $pdf .= '
    </table>
    </body>
    <footer>
        <span class="paginacion">Página </span>
    </footer>
    </html>';

    // Configurar y generar PDF
    $dompdf = new DOMPDF ();
    $dompdf->set_option ('enable_html5_parser', TRUE);
    $dompdf->set_paper ("letter", "a4");
    $dompdf->load_html ($pdf, 'UTF-8');
    $dompdf->render ();

    $output = $dompdf->output ();

    // Devolver archivo PDF codificado en base64
    echo base64_encode ($output);
}

?>