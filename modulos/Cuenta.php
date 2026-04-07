<?php

function UsaTmpPass ($no_nomina) {
	global $bd_cursos;

	$bd_cursos = new BDcursos ();
	$resultado = $bd_cursos->ObtenerTmpPass ($no_nomina);
	
	return $resultado ? $resultado ['Tmp_pass'] != NULL : false;
}

function CrearUsuario ($no_nomina, $tipo_usuario, $pass, $creador, $email, $email_externo) {
	global $bd_cursos;

	$bd_cursos = new BDcursos ();
	
	$tipos_usuario = array(
		"Instructor" => 	1,
		"Administrador" => 	2
	);

	// Registrar o actualizar usuario en la BD
	$resultado = $bd_cursos->RegistrarNuevoUsuario (
		$no_nomina,
		$tipos_usuario [$tipo_usuario],
		password_hash ($pass, PASSWORD_DEFAULT), // Almacenar contraseña hasheada
		$pass,
		$creador,
		$email,
		$email_externo
	);

	// Enviar contraseña por correo al empleado
	echo EnviarPassUsuario ($no_nomina, $email, $pass);
}

function EnviarPassUsuario ($no_nomina, $email, $pass) {

	$url = 'http://10.25.1.24/BMXQ_PR_OEE/EnvioEmailCursosPass.php';
	$parametros = array (
		'nomina' => $no_nomina, 
		'correo' => $email,
		'pass' =>	$pass
	);

	// Enviar petición al servidor para enviar correo al empleado
	$peticion = curl_init ($url);
	curl_setopt ($peticion, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($peticion, CURLOPT_POST, true);
	curl_setopt ($peticion, CURLOPT_POSTFIELDS, http_build_query ($parametros));
	curl_setopt ($peticion, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/x-www-form-urlencoded',
		'Custom-Header: CustomValue'
	));

	$response = curl_exec ($peticion);
	return $response;
}

function EliminarUsuario ($no_nomina) {
	global $bd_cursos;
	
	// Desabilitar usuario en la BD
	$bd_cursos = new BDcursos ();
	return $bd_cursos->EliminarUsuario ($no_nomina);
}

function CambiarPass ($nuevo_pass) {
	global $sesion, $bd_cursos;
	
	if (!isset ($nuevo_pass) || !isset ($_COOKIE ['sesion'])) return;

	$hash = password_hash ($nuevo_pass, PASSWORD_DEFAULT);
	
	// Obtener información de inicio de sesión
	$sesion = unserialize ($_COOKIE ['sesion']);
	$bd_cursos = new BDcursos ();
	$consulta = $bd_cursos->CambiarPass (
		$sesion->getNumNomina (), 
		$hash
	);

	// Remover contraseña temporal, en caso de que exista
	$bd_cursos->RemoverTmpPass (
		$sesion->getNumNomina ()
	);

	echo ($consulta > 0);
}

function ValidarAdmin ($tipo_usuario) {
	if ($tipo_usuario != 'admin') {
		header ('Location: inicio.php'); 
		exit;
	}
}

function ValidarSesionInstAdmin ($tipo_usuario) {
	if ($tipo_usuario != 'admin' && $tipo_usuario != 'instructor') {
		header ('Location: inicio.php'); 
		exit;
	}
}

function ValidarSesionPass ($pass, $sesion = null, $retorno = false) {
	global $bd_cursos;

	if ($sesion == null) $sesion = unserialize ($_COOKIE ['sesion']);   
	if (!isset ($pass) || $sesion == null) return;

	// Se realizará cel cambio de una contraseña temporal
	if (isset ($_POST ['cambio_pass']) && UsaTmpPass ($sesion->getNumNomina ())) {
		echo 'true'; exit;
	}
	
	// Validar si la contraseña recibida corresponde al usuario actual
	$bd_cursos = new BDcursos ();
	$consulta =  $bd_cursos->ValidarUsuario (
		$sesion->getNumNomina (), 
		$pass
	);
	if ($retorno) return $consulta ? true : false;
	echo $consulta ? 'true' : 'false'; exit;
}

function AutenticarUsuario ($sesion = null) {
	global $bd_cursos;
	
	// Obtener contraseña ingresada
	$pass = isset ($_POST ['pass']) ? $_POST ['pass'] : '';

	// Validar contraseña
	$consulta = ValidarSesionPass ( $pass, $sesion, true);
	
	if ($consulta) {
		$sesion->setAutenticado (true);
		setcookie ('sesion', serialize ($sesion));
	
		// Mantener redireccionamiento de la página
		if ($_POST ['redir'] != 'null') { 
			$url = $_POST ['redir'];
			header ("Location: {$url}"); 
			exit;
		} else {
			header ('Location: inicio.php'); 
			exit;
		}
	}
	
	setcookie (
		'error', 
		'La contraseña ingresada no es correcta.'
	);
	header ('Location: index.php'); exit;
}

?>