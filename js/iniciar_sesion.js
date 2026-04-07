let $btn_iniciar = 				null;
let $autenticacion = 			null;
let bs_collapse_autenticacion = null;

function EnviarPassEmail () {
	return new Promise (async (resolve, reject) => {
		const $in_nomina =	document.querySelector ('input[name="no_nomina"]');
		const $in_email =	document.querySelector ('input[name="email"]');
		const $btn_accion =	document.querySelector ('#modal-continuar');

		if (!$in_email || !$in_nomina) {
			resolve ('completado'); return;
		}
		// Realizar proceso de validación únicamente si existen los campos en el modal
		const campo_nomina_vacio = 	CampoVacio ($in_nomina);
		const email_valido = 		ValidarEmail ($in_email.value);

		// Validar campos de entrada
		if (campo_nomina_vacio || !email_valido) {
			if (campo_nomina_vacio) {
				IndicarError (
					$in_nomina.parentElement, 
					"Es necesario llenar este campo"
				); 
			} else {
				IndicarError (
					$in_email.parentElement, 
					"El formato de correo electrónico no es válido"
				);
			}
			// Deshabilitar temporalmente el botón
			$btn_accion.disabled = true;
			setTimeout (() => { $btn_accion.disabled = false; }, 3000);
			resolve ('reintentar'); return;
		}
		
		const tmp_pass = GenerarPass ();
		const formData = new FormData ();

		formData.append ('recuperar_pass', 'true');
		formData.append ('nomina', $in_nomina.value);
		formData.append ('email', $in_email.value);
		formData.append ('tmp_pass', tmp_pass);

		// Validar datos para recuperación de contraseña
		await fetch ('modulos/GestionUsuarios.php', {
			method: 'POST',
			body: formData
		})
		.then ((res) => res.text ())
		.then (async (resultado) => {
			if (resultado == 'false') {
				// No se pudo recuperar la informacion del usuario (datos incorrectos)
				MostrarMensaje (
					"No fue posible recuperar la contraseña", 
					"La combinación de número de nómina y correo electrónico no fue encontrada en el sistema.", 
					null, icono_error
				); resolve ('reintentar');
			} 
			else {
				// Enviar contraseña por correo
				const formData = new FormData ();
				formData.append ('recuperar', 'ok');
				formData.append ('correo', $in_email.value);
				formData.append ('pass', tmp_pass);
				formData.append ('nomina', $in_nomina.value);

				await fetch ('http://10.25.1.24/BMXQ_PR_OEE/EnvioEmailCursosPass.php', {
					method: 'POST',
					body: formData
				})
				.then ((res) => res.text())
				.then (() => {
					MostrarMensaje (
						"Contraseña recuperada", 
						"Se te ha enviado un correo con tu contraseña. Revisalo e intenta acceder nuevamente con tu contraseña.", 
						null, icono_success
					); resolve ('reintentar');
				})
				.catch (() => {
					MostrarMensaje (
						"Error al enviar contraseña", 
						"Se ha generado un error al intentar enviar la contraseña temporal. Por favor, inténtalo de nuevo más tarde", 
						null, icono_success
					); reject ('error');
				});
			}
		});
	});
}

function RecuperarPass () {
	MostrarMensaje (
		'Recuperar contraseña', 
		'<div class="input-group mb-3">' +
			'<span class="input-group-text">' +
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="30"><path fill="#555" d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"></path></svg>' +
			'</span>' +
			'<input name="no_nomina" class="form-control" type="number" placeholder="Ingresa tu número de nómina">' +
		'</div>' +
		'<div class="input-group mb-4">' +
			'<span class="input-group-text">' +
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30"><path fill="#555" d="M256 64C150 64 64 150 64 256s86 192 192 192c17.7 0 32 14.3 32 32s-14.3 32-32 32C114.6 512 0 397.4 0 256S114.6 0 256 0S512 114.6 512 256l0 32c0 53-43 96-96 96c-29.3 0-55.6-13.2-73.2-33.9C320 371.1 289.5 384 256 384c-70.7 0-128-57.3-128-128s57.3-128 128-128c27.9 0 53.7 8.9 74.7 24.1c5.7-5 13.1-8.1 21.3-8.1c17.7 0 32 14.3 32 32l0 80 0 32c0 17.7 14.3 32 32 32s32-14.3 32-32l0-32c0-106-86-192-192-192zm64 192a64 64 0 1 0 -128 0 64 64 0 1 0 128 0z"/></svg>' +
			'</span>' +
			'<input name="email" class="form-control" type="email" placeholder="Ingresa tu correo electrónico">' +
		'</div>' +
		'<p class="m-0">Se te enviará un correo electrónico con tu contraseña, <b>¿Deseas continuar?</b></p>', 
		() => EnviarPassEmail (), null, true, true, true, true
	);
}

function IniciarSesion () {
    const $input_nomina = document.querySelector ('input[name="num_nomina"]');
    const $input_pass =   document.querySelector ('input[name="pass"]');
	const url = 		  redireccionar != '' ? redireccionar : null;
    
    // Crear formulario de inicio de sesión
    const formulario =  document.createElement ('form');
    formulario.action = 'index.php';
    formulario.method = 'POST';
    
    const parametros = {
        iniciar_sesion: 'true',
        num_nomina:     $input_nomina.value,
        pass:           $input_pass.value,
		redir:			url
    };
    
    for (const [clave, valor] of Object.entries (parametros)) {
        const input = document.createElement ('input');
        
        input.setAttribute ('type', 'hidden');
        input.setAttribute ('name', clave);
        input.setAttribute ('value', valor);
        
        formulario.appendChild (input);
    }
    // Enviar formulario
    document.body.appendChild (formulario);
    AgregarPreloader ();
    formulario.submit ();
}

function HabilitarBtnIS (input) {
	if (!$btn_iniciar) {
		$btn_iniciar = document.querySelector ('input[name="pass"]');
	}
	// Habilitar o deshablitar botón dependiendo del estado del input
	$btn_iniciar.disabled = input.value === '' ? true : false;
}

function DeterminarTipoUsuario (empleado) {
	return !empleado ? 'vacio' : 
			empleado ['Tipo_usuario'] ? 
			'usuario' : 'empleado';
}

async function ValidarNomina (input) {
    // Recuperar componentes de la innterfaz (botón e input)
    if (!$btn_iniciar) $btn_iniciar =       document.querySelector ('#btn-iniciar'); 
    if (!$autenticacion) $autenticacion =   document.querySelector ('#autenticacion');
	
	const btn_texto = $btn_iniciar.querySelector ('p');

    // Sección colapsable de contraseña
    if (!bs_collapse_autenticacion) {
        bs_collapse_autenticacion = new bootstrap.Collapse (
            $autenticacion, { toggle: false }
        );
    }
	
	// Reemplazar texto del botón
	const cambiarTexto = (btn, msg, width) => {
		if (!btn) return;

		const nuevo = 
		'<p class="ms-auto my-auto" ' + 
			'style="width: ' + width + '; ' + 
			'white-space: nowrap; ' + 
			'text-overflow: clip; ' +
			'overflow: hidden; ' +
			'transition: all ease .5s; ' +
			'animation: fadeInBtn .8s;">' + 
				msg + 
		'</p>';
		btn_txt = btn.querySelectorAll ('p');
		btn_txt.forEach ((boton) => boton.remove ());
		btn.insertAdjacentHTML ('afterbegin', nuevo);
	};
	
	if (input.value === '' && btn_texto.textContent != 'Iniciar sesión') { 
		// Campo de nómina vacío
		setTimeout (()=> {
			btn_texto.textContent = 'Iniciar sesión';
			btn_texto.style.width = '100px';
		}, 500);
		return;
	}
	
	if ($btn_iniciar && btn_texto.textContent != 'Esperando número de nómina...') {
		// No se ha ingresado algún número de nómina válido
		cambiarTexto (
			$btn_iniciar, 'Esperando número de nómina...', '250px'
		);
	}
	    
    const formData = new FormData ();
    formData.append ('tipo_usuario', input.value);
    
    // Consultar número de nómina en BD
    await fetch ('modulos/GestionUsuarios.php', {
        method: 'POST',
        body: formData
    })
    .then ((resp) => resp.json())
    .then ((empleado) => {
		$tipo = DeterminarTipoUsuario (empleado);
		switch ($tipo) {
			case 'empleado': 
				// Se trata de un empleado
				$btn_iniciar.disabled = false;
				cambiarTexto (
					$btn_iniciar, 'Iniciar sesión', '100px'
				);
			break;
			case 'usuario': 
				// Se trata de un usuario con privilegios (requiere autenticación)
				cambiarTexto (
					$btn_iniciar, 'Iniciar sesión', '100px'
				);
				bs_collapse_autenticacion.show();
			break;
			default: 
				// No se encontró ningún empleado ni usuario
				$btn_iniciar.disabled = true;
				bs_collapse_autenticacion.hide (); 
				return;
		}
    })
	.catch(() => {
		// Sin conexión con la Base de datos
		MostrarMensaje (
			'Error', 
			'No fue posible validar el número de nómina debido a un error ' + 
			'de conexión. Por favor, inténtalo de nuevo más tarde.', 
			() => window.location.reload (), icono_error
		);
	});
}