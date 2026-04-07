<?php

require_once ('util/BD.php');

if (isset ($_POST ['reasignar_empleados'])) {
	$id_curso = $_POST ['id_curso'];
	$version = 	$_POST ['version'];

	ReasignarEmpleados ($id_curso, $version);
}

function ReasignarEmpleados ($id_curso, $version_curso) {
	global $bd_cursos;

	$bd_cursos = new BDcursos ();

	$curso = 	[$id_curso];
	$version = 	[$version_curso];
	$nominas = 	array ();
	
	$empleados_reasignar = $bd_cursos->ObtenerEmpleadosReasignar (
		$id_curso
	);
	
	foreach ($empleados_reasignar as $empleado) {
		array_push ($nominas, $empleado ['no_nomina']);
	}

	AsignarCIEmpleado ($curso, $nominas, $version);
}

function AsignarCIEmpleado (
	$cursos_array =    null, 
	$empleados_array = null, 
	$versiones_array = null,
	$fecha_limite =	   null,
	$asignacion = 	   null
) {
	global $bd_cursos;
	$bd_cursos = new BDcursos ();
	
	$empleados = 		isset ($empleados_array) ? $empleados_array : explode (',', $_POST ['asignar_ci_emp']);
	$cursos = 	 		isset ($cursos_array) ? $cursos_array : explode (',', $_POST ['cursos_i']);
	$versiones = 		isset ($versiones_array) ? $versiones_array : explode (',', $_POST ['versiones']);
	$fecha_limite =		isset ($fecha_limite) ? $fecha_limite : $_POST ['fecha_limite'];
	$asignacion =		isset ($asignacion) ? $fecha_limite : $_POST ['asignacion'];

	$cursos_versiones = array();
	
	for ($i = 0; $i < count ($cursos); $i++) {
		array_push (
			$cursos_versiones, [
				'id_curso' => $cursos[$i], 'version' => $versiones[$i]
			]
		);
	}
	$cursos_json = json_encode ($cursos_versiones);
	$resultado =   $bd_cursos->AsignarCursosInternos ($empleados, $cursos_json, $fecha_limite, $asignacion);

	echo json_encode ($resultado);
}

function DesasignarCurso () {
	if ($_POST ['desasignar'] != 'curso_interno' ||
		!isset ($_POST ['id_curso']) ||
		!isset ($_POST ['version']) ||
		!isset ($_POST ['no_nomina'])) return;
	
	$id_curso =  $_POST ['id_curso'];
	$version = 	 $_POST ['version'];
	$no_nomina = $_POST ['no_nomina'];
	
	$resultado = RemoverCursoPendiente (
		$id_curso, $version, array ($no_nomina)
	);
	echo $resultado ? 'true' : 'false';
}

function RegistrarCertificado ($registro) {

	$no_nomina = 	$registro ['empleado']['no_nomina'];
	$id_curso = 	$registro ['curso']['id_curso'];
	$fecha =		$registro ['fecha'];
	$certificado = 	$registro ['certificado'];
	$validez = 		explode (' ', $registro ['curso']['vigencia']);
	$unidades =		[
		'Años' => 'years', 
		'Meses' => 'months', 
		'Días' => 'days'
	];
	$fecha_expiracion = $validez [0] != 'N/A' ?
		date_add (
			date_create ($fecha), 
			date_interval_create_from_date_string (
				$validez [0]." ".$unidades [$validez [1]]
			)
		) : null;
	
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->RegistrarCertificado (
		$fecha,
		$no_nomina,
		$id_curso,
		$certificado,
		$fecha_expiracion ? 
			date_format ($fecha_expiracion, 'Y-m-d') : 
			null
	);
	echo ($resultado && $resultado > 0) ? 'true' : 'false';
}

?>