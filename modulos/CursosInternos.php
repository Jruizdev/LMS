<?php
require_once ('Consultar.php');
require_once ('AsignacionCursos.php');
require_once ('Evaluacion.php');
require_once ('util/curso.php');
require_once ('util/BD.php');

if (isset ($_GET  ['busqueda']))			  BuscarCurso ($_GET ['busqueda']);
if (isset ($_POST  ['buscar-curso-abierto'])) BuscarCursoAbierto ($_POST ['busqueda']);
if (isset ($_POST ['ultimo_ci'])) 		  	  ObtenerUltimoCI ();
if (isset ($_POST ['pendientes'])) 		  	  ObtenerCursosPendientes ($_POST ['pendientes']);
if (isset ($_POST ['desasignar'])) 		  	  DesasignarCurso ();
if (isset ($_POST ['recuperar_versiones']))   RecuperarVersiones ($_POST ['recuperar_versiones']);
if (isset ($_POST ['actualizar_portada'])) 	  ActualizarPortadaCurso ($_POST ['id_curso']);
if (isset ($_POST ['revertir_portada'])) 	  RevertirPortadaCurso ($_POST ['id_curso']);

function BuscarCursoAbierto ($criterio) {
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->BuscarCursoAbierto ($criterio);

	echo json_encode ($resultado);
}

function ConsultarIdCursoAbierto ($id_curso) {
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->ConsultarIdCursoAbierto ($id_curso);
	
	return $resultado [0]['Abierto'];
}

function ObtenerCursosAbiertos () {
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->ObtenerCursosAbiertos ();

	return $resultado;
}

function RevertirPortadaCurso ($id_curso) {
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->RevertirPortada ($id_curso);

	echo json_encode ($resultado);
}

function ActualizarPortadaCurso ($id_curso) {
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->ActualizarPortadaAnterior ($id_curso);

	echo json_encode ($resultado);
}

function ObtenerCursoDependiente ($id_curso) {
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->ObtenerCursoDependiente ($id_curso);

	return [
		'id' => $resultado ? $resultado ['id'] : 'null',
		'curso' => $resultado ? $resultado ['curso'] : 'null'
	];
}

function HayVersionAnterior ($id_Curso) {
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->RecuperarVersiones ($id_Curso);
	return count ($resultado) > 0 ? 'true' : 'false';
} 

function RecuperarVersiones ($id_curso) {
	$BDcursos = new BDcursos ();

	$resultado = $BDcursos->RecuperarVersiones ($id_curso);
	echo json_encode ($resultado);
}

function RemoverCursoPendiente ($id_curso, $no_version, $colaboradores) {
	$BDcursos = new BDcursos ();
	$resultado = $BDcursos->RemoverCIPendiente (
		$id_curso, $no_version, $colaboradores
	);
	return $resultado > 0 ? true : false;
}

//function RegistrarCursoAprobado ($id_curso, $version, $no_nomina, $puntaje, $puntaje_max) {
function RegistrarCursoAprobado ($id_curso, $version, $colaboradores, $puntaje, $puntaje_max) {
	$BDcursos = new BDcursos ();

	// Registrar curso aprobado en la BD
	$resultado = $BDcursos->RegistrarCIAprobado (
		$id_curso, $colaboradores, $version, $puntaje, $puntaje_max
	);
	return $resultado > 0 ? true : false;
}

function EliminarCursoInterno ($id_curso) {
	$BDcursos = new BDcursos ();
	
	$resultado = $BDcursos->EliminarCursoInterno ($id_curso);
	return true;
}

function PublicarCursoInterno ($id_int, $id_curso, $version_actual) {
	$BDcursos = new BDcursos ();

	$resultado = $BDcursos->AgregarCursoInterno (
		$id_int,
		$id_curso,
		$version_actual
	);
	return $resultado;
} 

function GuardarBorradorBD ($no_nomina, $curso, $id_int = null, $id_curso = null) {
	$BDcursos = new BDcursos ();

	$resultado = $BDcursos->GuardarBorrador (
		$id_int,
		$id_curso,
		htmlspecialchars ($curso->nombre),
		htmlspecialchars ($curso->descripcion),
		htmlspecialchars ($curso->objetivo),
		'INT',
		json_encode ($curso->contenido, true),
		json_encode ($curso->banco_preg, true),
		$curso->departamento,
		htmlspecialchars ($curso->comentarios),
		$no_nomina,
		$curso->tags,
		$curso->portada,
		$curso->curso_previo
	); 
	return $resultado;
}

function DescartarBorradorBD ($id_int, $id_curso) {
	$BDcursos = new BDcursos ();
	
	$resultado = $BDcursos->DescartarBorrador ($id_int, $id_curso);
	return $resultado;
}

?>