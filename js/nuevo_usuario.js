const mensajes = {
	error_1:   	'La contraseña debe contener al menos 8 caracteres, incluyendo: Letras, números y caracteres especiales.',
	error_2:   	'Las contraseñas ingresadas no coinciden.',
	error_3:	'El formato del correo electrónico proporcionado no es válido. Por favor, verifica este campo para continuar.',
	success_1: 	'El usuario fue creado correctamente.'
};

function CrearUsuario (email) {
	const $formulario = 	 document.querySelector ('#form-nuevo-usuario');
	//const $in_pass = 		 document.querySelector ('input[name="pass"]'); 
	//const $in_confirmacion = document.querySelector ('input[name="confirmacion"]');

	// Campo para indicar si se utilizará un email interno o externo
	const $in_email_externo = 	document.createElement ('input');
	$in_email_externo.type = 	'hidden';
	$in_email_externo.name = 	'email_externo';
	$in_email_externo.value = 	!email;
	$formulario.appendChild ($in_email_externo);

	// Recuperar correo electrónico del empleado
	if (!email) email = document.querySelector ('input[name="email"]').value;

	// Validar formato del correo electrónico proporcionado
	const email_valido = ValidarEmail (email);

	if (!email_valido ) {
		MostrarMensaje ("Error", mensajes ['error_3'], null, icono_error);
		return;
	}

	const pass_temporal = GenerarPass ();

	// Agregar campo de contraseña temporal al formulario
	const $in_pass = document.createElement ('input');
	$in_pass.type =  'hidden';
	$in_pass.name =  'pass';
	$in_pass.value = pass_temporal;
	$formulario.appendChild ($in_pass);

	// Agregar campo de correo electrónico
	const $in_email = document.createElement ('input');
	$in_email.type = 'hidden';
	$in_email.name = 'email';
	$in_email.value = email;
	$formulario.appendChild ($in_email);
	
	// Enviar petición para crear usuario
	EnviarFormulario ($formulario);
}

function EnviarFormulario ($formulario) {
	MostrarMensaje (
		"Operación realizada", mensajes ['success_1'], 
		() => $formulario.submit(), icono_success
	); 
}

function ObtenerInputPass () {
	return document.querySelector ('input[name="pass"]');
}
function ObtenerInpurConfirmacion () {
	return document.querySelector ('input[name="confirmacion"]');
}

function GenerarPass () {
	const letras = 				 "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	const numeros = 			 "0123456789";
	const caracteresEspeciales = "@$!%*?&-_";
	
	let pass = [
		// Seleccionar al menos una letra, un número y un caracter especial
		letras 				 [Math.floor (Math.random () * letras.length)],
		numeros 			 [Math.floor (Math.random () * numeros.length)],
		caracteresEspeciales [Math.floor (Math.random () * caracteresEspeciales.length)]
	];
	
	const todos_caracteres = letras + numeros + caracteresEspeciales;
	
	for (let i = pass.length; i < 8; i++) {
		
		// Completar con caracteres aleatorios hasta alcanzar los 8 caracteres
		pass.push (todos_caracteres [Math.floor (Math.random () * todos_caracteres.length)]);
	}
	
	// Mezclar los caracteres para evitar que los primeros tres sean predecibles
	pass = pass.sort(() => Math.random() - 0.5);
	
	// Retornar contraseña válida
	return String.fromCharCode (...pass.map(char => char.charCodeAt(0)));
}