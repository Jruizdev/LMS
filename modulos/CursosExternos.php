<?php
require_once ('Consultar.php');
require_once ('AsignacionCursos.php');
require_once ('util/curso.php');
require_once ('util/BD.php');

$peticion_json = json_decode (file_get_contents ('php://input'), true);

if ($peticion_json) {
	// Se recibió un nuevo certificado por registrar
	RegistrarCertificado ($peticion_json);
}

if (isset ($_POST ['accion'])) {
	switch ($_POST ['accion']) {
		case 'crear': 				 CrearExterno (); break;
		case 'buscar': 				 BuscarCursoExterno (); break;
		case 'eliminar': 			 EliminarCursoExterno (); break;
		case 'quitar_certificacion': EliminarCertificado (); break;
	}
}

function EliminarCursoExterno () {
	if (!isset ($_POST ['id_curso'])) return;

	$BDcursos = new BDcursos ();
	$id_curso = $_POST ['id_curso'];

	$resultado = $BDcursos->EliminarCursoExterno ($id_curso);
	echo $resultado > 0;
}

function RecuperarCertificacion ($id_certificacion) {
	$BDcursos = new BDcursos ();
	$BDentidad = 	new BDentidad ();
	
	$resultado = $BDcursos->RecuperarCertificacion (
		$id_certificacion
	);
	
	$empleado = $BDentidad->ConsultarUsuario (
		$resultado ['No_nomina']
	);
	
	$certificacion = new Certificacion (
		$resultado ['Nombre'],
		$resultado ['Descripcion'],
		$resultado ['Tipo']
	);
		
	// Generar nueva instancia de certificación
	$certificacion->setNombreEmpleado ($empleado ['nombre']);
	$certificacion->setNumNomina ($resultado ['No_nomina']);
	$certificacion->setIdCertificacion ($resultado ['Id_certificacion']);
	$certificacion->setIdCurso ($resultado ['Id_curso']);
	$certificacion->setFecha ($resultado ['Fecha']);
	$certificacion->setCertificado ($resultado ['Certificado']);
	$certificacion->setValidez ($resultado ['Validez']);
	
	return $certificacion;
}

function EliminarCertificado () {
	if (!isset ($_POST ['id_certificacion'])) return;
	$id_certificacion = $_POST ['id_certificacion'];

	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->QuitarCertificacion ($id_certificacion);
	echo ($resultado && $resultado > 0);
}

function CrearExterno () {
	if (!isset ($_POST ['nombre']) ||
		!isset ($_POST ['descripcion']) ||
		!isset ($_POST ['fecha']) ||
		!isset ($_POST ['tiene_vigencia']) ||
		!isset ($_POST ['vigencia']) ||
		!isset ($_POST ['unidad'])) return;
		
	$BDcursos = new BDcursos ();
	
	// Recuperar información del curso externo
	$nombre = 			$_POST ['nombre'];
	$descripcion = 		$_POST ['descripcion'];
	$fecha = 			$_POST ['fecha'];
	$tiene_vigencia = 	$_POST ['tiene_vigencia'];
	$vigencia = 		$_POST ['vigencia'];
	$unidad = 			$_POST ['unidad'];
	
	$resultado = $BDcursos->AgregarCursoExterno (
		$nombre, 
		$descripcion, 
		$fecha,
		$tiene_vigencia == '1' ? $vigencia : null, 
		$tiene_vigencia == '1' ? $unidad : null
	);
	echo var_dump ($resultado);
}
?>