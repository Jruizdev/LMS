<?php
require_once ('util/BD.php');

if (isset ($_POST ['consultar'])) {
	switch ($_POST ['consultar']) {
		case 'certificado_ci': InfoCertificadoCI (); break;
		case 'cursos_pendientes': ObtenerCursosPendientes ($_POST ['no_nomina']); break;
		case 'cursos-disponibles': ObtenerCIDisponibles ($_POST ['no_nomina'], 1, 4); break;
		case 'info_jefe': ObtenerInfoJefe ($_POST ['no_nomina']); break;
	}
}

function ObtenerInfoJefe ($no_nomina) {
	global $bd_cursos;

	if (!isset ($no_nomina)) return;
	$bd_cursos = new BDcursos ();
	$resultado = $bd_cursos->ObtenerInfoJefe (
		$no_nomina
	);

	echo json_encode ($resultado);
}

function InfoCertificadoCI () {
	global $bd_cursos;

	if (!isset ($_POST ['id_curso']) || !isset ( $_POST ['no_nomina'])) return;
	$id_curso =  $_POST ['id_curso'];
	$no_nomina = $_POST ['no_nomina'];

	$bd_cursos = new BDcursos ();
	$resultado = $bd_cursos->ObtenerInfoCertificadoCI (
		$id_curso, $no_nomina
	);

	echo json_encode ($resultado);
}

function RecuperarPassUsuario ($nomina, $email, $tmp_pass) {
	global $bd_cursos;

	$bd_cursos = new BDcursos ();
	$resultado = $bd_cursos->RecuperarPassUsuario ($nomina, $email, $tmp_pass);

	echo $resultado > 0 ? 'true' : 'false';
}

function ObtenerEstadisticas ($no_nomina) {
	global $bd_cursos;

	$bd_cursos = new BDcursos ();
	$resultado = $bd_cursos->ObtenerEstadisticas ($no_nomina);
	
	return $resultado;
}

function ConsultarEmpleado ($no_nomina) {
	global $bd_cursos, $bd_entidad;

	$bd_cursos = new BDcursos (); 
	$bd_entidad =  new BDentidad();
	
	// Obtener información del empleado
	$info = $bd_entidad->ConsultarUsuario (
		$no_nomina
	);
	// Obtener tipo de usuario
	$tipo_usuario = $bd_cursos->ConsultarTipoUsuario (
		$no_nomina
	);
	// Obtener cursos pendientes del empleado
	$c_pendientes = $bd_cursos->ObtenerCursosPendientes (
		$no_nomina
	);
	// Obtener cursos aprobados por el empleado
	$c_aprobados = $bd_cursos->ObtenerCursosAprobados (
		$no_nomina
	);
	// Obtener certificaciones del empleado
	$c_externos = $bd_cursos->CertificacionesEmpleado (
		$no_nomina
	);
	// Obtener cursos creados por el empleado
	$c_creados = $bd_cursos->ObtenerCursosCreados (
		$no_nomina
	);
	
	$resultado = json_encode ([
		'informacion' => [$info, $tipo_usuario],
		'cursos_pendientes' => $c_pendientes,
		'cursos_aprobados' => $c_aprobados,
		'certificaciones' => $c_externos,
		'cursos_creados' => $c_creados
	], JSON_PRETTY_PRINT);
	
	echo $resultado;
}

function BuscarEmpleados ($criterio) {
	global $bd_entidad;
	
	// Recuperar todos los empleados que cumplan con el criterio de búsqueda (número de nómina o nombre)
	$bd_entidad = new BDentidad();
	$empleados = $bd_entidad->BuscarEmpleados ($criterio);

	echo json_encode ($empleados);
}

function RecibirEmpleadosArea ($id_centro_gastos) {
	global $bd_entidad;
	
	// Consultar todos los empleados de un área
	$bd_entidad = new BDentidad();
	$empleados_area = $bd_entidad->ConsultarEmpleadosArea ($id_centro_gastos);
	
	echo json_encode ($empleados_area);
}

function ObtenerTodosCE ($pag_actual, $tam_pag, $busqueda = null) {
	$BDcursos = new BDcursos ();
	
	$total_ce =	 $BDcursos->ObtenerTotalCE ($busqueda);
	$resultado = $BDcursos->ObtenerTodosCE ($pag_actual - 1, $tam_pag, $busqueda); 
	
	return array (
		'total_ce' => $total_ce ['Total'],
		'lista_ce' => $resultado
	);
}

function ObtenerTodosCI ($pag_actual, $tam_pag, $busqueda = null) {
	$BDcursos = new BDcursos ();
	$total_ci =	 $BDcursos->ObtenerTotalCI ($busqueda);
	$resultado = $BDcursos->ObtenerTodosCI ($pag_actual - 1, $tam_pag, $busqueda);
	
	return array (
		'total_ci' => $total_ci ['Total'],
		'lista_ci' => $resultado
	);
}

function ObtenerCIDisponibles ($no_nomina, $pag_actual, $tam_pag, $busqueda = null) {
	$BDcursos = new BDcursos ();

	if (isset ($_POST ['num_registros']) && $_POST ['num_registros'] != '') 
		$tam_pag = $_POST ['num_registros'];

	if (isset ($_POST ['pag_actual']) && $_POST ['pag_actual'] != '') 
		$pag_actual = $_POST ['pag_actual'];

	$total_ci =	 $BDcursos->ObtenerTotalCIDisponibles ($no_nomina, $busqueda);
	$resultado = $BDcursos->ObtenerTodosCIDisponibles ($no_nomina, $pag_actual - 1, $tam_pag, $busqueda);
	
	if (isset ($_POST ['respuesta']) && $_POST ['respuesta'] == 'json') {
		echo json_encode ([
			'total_ci' => $total_ci ['Total'],
			'lista_ci' => $resultado
		]);
		//echo json_encode ($resultado);
		exit;
	}
	return array (
		'total_ci' => $total_ci ['Total'],
		'lista_ci' => $resultado
	);
}

function ObtenerEmpleadosCertificados ($pag_actual, $tam_pag, $busqueda = null) {
	$BDcursos = new BDcursos ();

	$total_uc =	 $BDcursos->ObtenerTotalUC ($busqueda);
	$resultado = $BDcursos->ObtenerEmpleadosCertificados ($pag_actual - 1, $tam_pag, $busqueda);
	
	return array (
		'total_uc' => $total_uc ['Total'],
		'lista_uc' => $resultado
	);
}

function ObtenerCursosExternos ($no_nomina, $pag_actual, $pag_tam, $busqueda = '') {
	if (!isset ($no_nomina)) return;
	$BDcursos = new BDcursos ();

	$total_cert = $BDcursos->ObtenerTotalCertEmpleado ($no_nomina, $busqueda);
	$resultado = $BDcursos->CertificacionesEmpleado (
		$no_nomina, $pag_actual - 1, $pag_tam, $busqueda
	); 
	
	return array (
		'total_cert' => $total_cert ['Total'],
		'lista_cert' => $resultado
	);
}

function BuscarCursoExterno () {
	if (!isset($_POST ['criterio_busqueda'])) return;
	
	$criterio = $_POST ['criterio_busqueda'];
	
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->BuscarCursoExterno (
		$criterio
	);
	echo json_encode ($resultado);
}

function ObtenerUltimoCI () {
	if (!isset ($_POST ['ultimo_ci']) || 
		!isset ($_POST ['id_curso'])) return;
		
	$id_int = 	 	   $_POST ['ultimo_ci'];
	$id_curso =  	   $_POST ['id_curso'];
	$excluir_edicion = (isset ($_POST ['anterior']) && $_POST ['anterior'] == 'true');
				  
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->ObtenerUltimoCI (
		$id_curso, 
		$id_int,
		$excluir_edicion
	);

	echo $resultado ? $resultado ['Contenido'] : '';
}

function ComprobarCursoSecuencial ($id_curso) {
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->ComprobarCursoSecuencial ($id_curso);
	return count ($resultado) > 0;
}

function ConsultarCursoAprobado ($id_curso, $no_version, $no_nomina) {
	
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->ObtenerCIAprobado (
		$id_curso, $no_version, $no_nomina
	);
	return $resultado;
}

function BuscarCurso ($criterio) {
	$tipo_usuario = $_GET ['tipo'];
	$no_nomina = 	$_GET ['no_nomina'];
	
	$BDcursos = new BDcursos ();
	
	/*
		Si la búsqueda la realiza un administrador, se buscará en todos los cursos.
		Si búsqueda la hace un instructor, únicamente se buscará en su cursos creados.
	*/
	$resultado = $tipo_usuario == 'admin' ? 
				 $BDcursos->BuscarCurso ($criterio) :
				 $BDcursos->BuscarCursoInst ($criterio, $no_nomina);
	
	echo json_encode ($resultado);
}

function ObtenerEmpleadosAprobados (
	$no_nomina = null, 
	$pagina_actual = null, 
	$tam_pag = null,
	$busqueda = null
) {
	$BDcursos = new BDcursos ();

	$total_ua =  $BDcursos->ObtenerTotalUA ($busqueda);
	$resultado = $BDcursos->ObtenerEmpleadosAprobados (
		$no_nomina, $pagina_actual - 1, $tam_pag, $busqueda
	);

	return array (
		'total_ua' => $total_ua ['Total'],
		'lista_ua' => $resultado
	);
}

function ObtenerBorradores ($no_nomina) {
	$BDcursos = new BDcursos ();
	$lista_borradores = array ();
	
	$resultado = $BDcursos->ObtenerBorradores ($no_nomina);
	
	foreach ($resultado as $elemento) {
		$borrador = new CursoInterno (
			$elemento ['Nombre'],
			$elemento ['Descripcion'],
			$elemento ['Objetivo'],
			'INT',
			$elemento ['Tags'],
			isset ($elemento ['Portada']) ? $elemento ['Portada'] : ''
		);
		$borrador->setIdInt 	($elemento ['Id_int']);
		$borrador->setIdCurso 	($elemento ['Id_curso']);
		$borrador->setFecha 	($elemento ['Ultima_mod']);
		
		array_push ($lista_borradores, $borrador);
	}
	return $lista_borradores;
}

function ObtenerCursosCreados (
	$no_nomina, 
	$pag_actual = null, 
	$pag_size = null, 
	$busqueda = null
) {
	$BDcursos = 	new BDcursos ();
	$lista_cursos = array();
	
	$total_CC =  $BDcursos->ObtenerTotalCC ($no_nomina, $busqueda);
	$resultado = $BDcursos->ObtenerCursosCreados ($no_nomina, $pag_actual - 1, $pag_size, $busqueda);
	
	foreach ($resultado as $elemento) {
		$curso = new CursoInterno (
			$elemento ['Nombre'],
			$elemento ['Descripcion'],
			$elemento ['Objetivo'],
			'INT',
			$elemento ['Tags'],
			$elemento ['Portada']
		);
		$curso->setFecha ($elemento ['Fecha']);
		$curso->setIdCurso ($elemento ['Id_curso']);
		$curso->setVersionActual ($elemento ['Version']);
		
		array_push ($lista_cursos, $curso);
	}

	return array (
		'total_cc' => $total_CC,
		'lista_cc' => $lista_cursos
	);
}

function ObtenerCursosAprobados ($no_nomina, $pag_actual = null, $tam_pag = null, $busqueda = null) {
	$BDcursos = new BDcursos ();
	$lista_cursos = array();

	$total_ca = $BDcursos->ObtenerTotalCAEmpleado ($no_nomina, $busqueda);
	$resultado = $BDcursos->ObtenerCursosAprobados ($no_nomina, $pag_actual - 1, $tam_pag, $busqueda);
	
	return array (
		'total_ca' => $total_ca ['Total'],
		'lista_ca' => $resultado
	);
}

function ObtenerEmpleadosCP ($no_nomina, $pagina_actual, $tam_pag, $busqueda = null) {
	$BDcursos = new BDcursos ();

	$lista_cusos = array ();

	// Obtener todos los cursos pendientes
	$total_ucp = $BDcursos->ObtenerTotalUCP ($no_nomina, $busqueda);
	$resultado = $BDcursos->ObtenerEmpleadosCP ($no_nomina, $pagina_actual - 1, $tam_pag, $busqueda);
	
	return array (
		'total_ucp' => $total_ucp ['Total'],
		'lista_ucp' => $resultado
	);
}

function ObtenerCursosPendientes ($no_nomina, $pag_actual = null, $tam_pag = null) {
	$BDcursos = new BDcursos ();
	$lista_cursos = array();
	
	$total_cp =	 $BDcursos->ObtenerTotalCPEmpleado ($no_nomina);
	$resultado = $BDcursos->ObtenerCursosPendientes ($no_nomina, $pag_actual - 1, $tam_pag);
	
	if (isset ($_POST ['respuesta']) && $_POST ['respuesta'] == 'json') {
		echo json_encode ($resultado);
		exit;
	}
	return array (
		'total_cp' => $total_cp ['Total'],
		'lista_cp' => $resultado
	);
}

?>