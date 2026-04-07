<?php

// Librerías propias del sistema
require_once ('Consultar.php');
require_once ('AsignacionCursos.php');
require_once ('Cuenta.php');
require_once ('util/cookiescursos.php');
require_once ('util/sesion.php');
require_once ('util/BD.php');

$sesion   =  null; // Objeto que almacenará la sesión del usuario
$bd_entidad =  null; // Objeto de conexión con la BD de la empresa
$bd_cursos = null; // Objeto de conexión con la BD de la plataforma

if (isset ($_POST ['recuperar_pass']))		RecuperarPassUsuario ($_POST ['nomina'], $_POST ['email'], $_POST['tmp_pass']);
if (isset ($_POST ['iniciar_sesion'])) 	  	IniciarSesion ();
if (isset ($_POST ['autenticar'])) 		  	AutenticarUsuario ();
if (isset ($_POST ['validar_pass'])) 	  	ValidarSesionPass ($_POST ['validar_pass']);
if (isset ($_POST ['tipo_usuario']))	  	ObtenerTipoUsuario ($_POST ['tipo_usuario']);
if (isset ($_POST ['asignar_ci_emp'])) 	  	AsignarCIEmpleado ();
if (isset ($_POST ['cambiar_pass'])) 	  	CambiarPass ($_POST ['cambiar_pass']);
if (isset ($_POST ['registrar_intento'])) 	RegistrarIntentoEvaluacion ();
if (isset ($_POST ['consultar_empleado'])) 	ConsultarEmpleado ($_POST ['consultar_empleado']);
if (isset ($_GET  ['cerrar_sesion'])) 	  	CerrarSesion ();
if (isset ($_GET  ['obtener_area']))	  	RecibirEmpleadosArea ($_GET ['obtener_area']);
if (isset ($_GET  ['buscar_empleados']))  	BuscarEmpleados ($_GET  ['buscar_empleados']);
if (isset ($_GET  ['obtener_empleado'])) 	echo json_encode (UsuarioEmpleado ($_GET  ['obtener_empleado']));

function RegistrarIntentoEvaluacion () {
	global $bd_cursos;
	
	if (!isset ($_POST ['id_curso']) || 
		!isset ($_POST ['no_nomina']) || 
		!isset ($_POST ['colaboradores']) || 
		!isset ($_POST ['version'])) {
		return;
	}
	// Recuperar información del curso y el empleado
	$id_curso =  	 $_POST ['id_curso'];
	$no_nomina = 	 $_POST ['no_nomina'];
	$colaboradores = explode (',', $_POST ['colaboradores']);
	$version = 		 $_POST ['version'];
	
	$bd_cursos = new BDcursos ();
	// Registrar intento de él o los empleados en la BD
	$resultado = $bd_cursos->RegistrarIntentoEvaluacion (
		$id_curso, $colaboradores, $version
	);
	echo $resultado > 0 ? 'true' : 'false';
}

function RecibirEmpleadoRegistrado () {
	global $bd_cursos;
	$empleado = null;
	
	// No se recibió ningún empleado
	if (!isset ($_POST ['no_nomina'])) return null;
	
	// Consultar usuario registrado y obtener tipo de usuario
	$bd_cursos = new BDcursos ();
	$usuario =	 $bd_cursos->ConsultarTipoUsuario ($_POST ['no_nomina']);

	// El usuario no se encuentra activo o registrado en la plataforma
	if (!$usuario) return;
	
	$registro = UsuarioEmpleado ($_POST ['no_nomina']);
		
	if($registro) {
		// Almacenar temporalmente información del empleado
		$empleado = new Usuario (
			$registro ['no_nomina'],
			$registro ['nombre'],
			$usuario  ['Tipo_usuario'],
			$registro ['departamento'],
			$registro ['email'],
			$registro ['puesto']
		);
	}
	return $empleado;
}

function RecibirEmpleado () {
	//global $bd_cursos;
	$bd_cursos = new BDcursos ();
	$empleado = null;
	
	// No se recibió ningún empleado
	if (!isset ($_POST ['no_nomina'])) return null;
	
	$registro = UsuarioEmpleado ($_POST ['no_nomina']);
	$usuario =	$bd_cursos->ConsultarTipoUsuario ($_POST ['no_nomina']);
	
	// Asignar usuario por defecto, en caso de no encontrar el tipo de usuario
	if (!$usuario) $usuario = ['Tipo_usuario' => 0];
	
	if($registro) {
		// Obtener información de la búsqueda de empleado
		$empleado = new Usuario (
			$registro ['no_nomina'],
			$registro ['nombre'],
			$usuario  ['Tipo_usuario'],
			$registro ['departamento'],
			$registro ['email'],
			$registro ['puesto']
		);
	}
	return $empleado;
}

function UsuarioEmpleado ($no_nomina) {
	global $bd_entidad;
	
	$bd_entidad =  new BDentidad ();
	$resultado = $bd_entidad->ConsultarUsuario ($no_nomina);
	
	return $resultado;
} 

function ObtenerTipoUsuario ($no_nomina) {
	global $bd_cursos;

	if (isset($_POST ['registrar'])) return;
	
	$bd_cursos = new BDcursos ();
	$resultado = $bd_cursos->ConsultarTipoUsuario (
		$no_nomina
	);
	if (!$resultado) $resultado = UsuarioEmpleado ($no_nomina);
	echo json_encode ($resultado);
}

function IniciarSesion () {
	global $sesion, $bd_entidad, $bd_cursos;
	
	// Recuperar sesión activa, en caso de que exista
	if (SesionIniciada ()) { RecuperarSesion (); ValidarInicioSesion (); return; }
	
	// Limpiar cookies, en caso de que exista una sesión previa
	EliminarCookie ('sesion');
	
	if (!isset ($_POST ['num_nomina']) || $_POST ['num_nomina'] == "") return;

	$bd_entidad = 	new BDentidad ();
	$num_nomina = 	$_POST ['num_nomina'];
	$usuario = 		$bd_entidad->ConsultarUsuario ($num_nomina);
	
	if (!$usuario) {
		// No se encontró el usaurio en la BD
		setcookie (
			'error', 
			'Asegurese de que el número de nómina ingresado sea correcto y esté vigente.'
		);
		header ('Location: index.php'); exit;
	}
	
	// Comprobar si es un usuario con privilegios
	$bd_cursos = new BDcursos ();
	$consulta =  $bd_cursos->ConsultarTipoUsuario ($usuario ["no_nomina"]);
	
	// Acceder al sistema como usuario empleado
	$sesion = new Sesion (
		$usuario ["no_nomina"], 
		$usuario ["nombre"], 
		'empleado',
		$usuario ["departamento"],
		$usuario ["email"]
	);
	
	if($consulta) {
		$sesion->setAutenticado (false);
		switch ($consulta ['Tipo_usuario']) {
			case '1': $sesion->setTipoUsuario ('instructor'); break;
			case '2': $sesion->setTipoUsuario ('admin'); break;
		}
		AutenticarUsuario ($sesion); exit;
	}
	
	setcookie ('sesion', serialize ($sesion));
	
	// Mantener redireccionamiento de la página
	if ($_POST ['redir'] != 'null') { 
		$url = $_POST ['redir'];
		header ("Location: {$url}"); 
		exit;
	} 
	else {
		header ('Location: inicio.php'); 
		exit;
	}
}

function RecuperarSesion () {
	global $sesion;
	$sesion = isset ($_COOKIE ['sesion']) ?
			  unserialize ($_COOKIE ['sesion']) :
			  null;
}

function CerrarSesion () {
	// Eliminar información de la sesión, de las cookies
	EliminarCookie ('sesion');
}

function ValidarInicioSesion () {
	if (SesionIniciada ()) { header ('Location: inicio.php'); exit; }
}

function ValidarSesion ($url_redireccionar = null) {
	if (!SesionIniciada ()) { 
		if ($url_redireccionar == null) header ('Location: index.php'); 
		else header ("Location: index.php?id={$url_redireccionar}"); 
		exit; 
	}
	else RecuperarSesion ();
}

function SesionIniciada () {

	// Devolver si hay o no una sesión de usuario activa
	if (isset ($_COOKIE ['sesion']) && 
		unserialize ($_COOKIE ['sesion']) instanceof Sesion &&
		unserialize ($_COOKIE ['sesion'])->getAutenticado ()) return true;
	return false;
}

?>