var error = false;

function ValidarPass (pass, confirmacion) {
	const regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&-_])[A-Za-z\d@$!%*?&-_]{8,}$/;
	
	if (!regex.test (pass))   return 0;	// La contraseña no se cumple con el formato solicitado
	if (pass != confirmacion) return 1; // Las contraseñas no coinciden
	return 2;							// Contraseña válida
}

function ValidarEmail (email) {
	const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

	// Email no válido
	if (!regex.test(email) || (!email || email.trim() === '')) return false;
	return true;
}

function FormatoNoAceptado ($campo) {

	// Validar formato de archivo subido en input
	if (!$campo.getAttribute ('accept') || !$campo.files.length) return false;

	const formatos_aceptados = 
	$campo.getAttribute ('accept').split (',')
	.map ((formato) => formato.toLowerCase().trim ());

	const formato_archivo = '.' + $campo.files [0].name.split ('.').pop ().toLowerCase ();
	return (!formatos_aceptados.includes (formato_archivo));
}

function ArchivoNoSeleccionado ($campo) {

	// Validar input para selección de archivos
	if (($campo.dataset.opcional && $campo.dataset.opcional == 'true') ||
		 $campo.type != 'file') return false;
	
	if ($campo.files.length == 0) return true;
	return false;
}

function CampoVacio ($campo) {
	if (!$campo) return false;
	
	// Validar campos vacíos
	if ($campo.tagName.toLowerCase () != 'input' &&
		$campo.tagName.toLowerCase () != 'textarea' ||
		$campo.type == 'file') {
		return false;
	}
	// Eliminar posibles espacios en blanco del campo
	const contenido = $campo.value;
	const texto = 	  contenido.replace (/^\s+/, '');
	
	if (texto == "") return true;
	return false;
}

function FechaFueraDeRango ($campo) {
	if ($campo.type != 'date') return;

	if (!$campo.value) return { 
		error: 'Es necesario indicar la fecha en este campo' 
	};
	const fecha_campo = new Date ($campo.value);
	const fecha_min = 	new Date ($campo.min);
	const fecha_max = 	new Date ($campo.max);

	if (fecha_campo > fecha_max) return { 
		error: 'La fecha no debe ser posterior al ' + $campo.max
	};
	if (fecha_campo < fecha_min) return { 
		error: 'La fecha no debe ser anterior al ' + $campo.min
	};
	
	return false;
}

function FueraDeRangoNumerico ($campo) {
	if ($campo.min && $campo.value < parseInt($campo.min)) return { error: 'El valor mínimo para este campo es ' + $campo.min };
	if ($campo.max && $campo.value > parseInt($campo.max)) return { error: 'El valor máximo para este campo es ' + $campo.max };
	return false;
}

function ValidarSeleccionRadio (lista_radio) {
	let hay_error = true;
	
	if (typeof lista_radio[Symbol.iterator] != 'function') return false;
	
	// Validar que haya seleccionada alguna opción de la lista
	lista_radio.forEach ((radio) => {
		if (radio.checked) {
			hay_error = false; return;
		}
	});
	
	return hay_error ? {
		error: "Es necesario seleccionar la respuesta correcta", 
		input: lista_radio[0]
	} : null;
}

function ValidarMultiplesEntradas (lista_input) {
	if (typeof lista_input [Symbol.iterator] != 'function') return false;

	let error_validacion = null;
	let total_errores = 0;

	for (let i = 0; i < lista_input.length; i++) {
		
		// Validar que no haya campos vacíos
		if (CampoVacio (lista_input [i])) {
			total_errores++;
			error_validacion = {
				error: "Es necesario llenar este campo", 
				input: lista_input [i],
				num_errores: total_errores
			};
		}
		
		// Validar selección de archivos (en inputs de tipo file)
		if (ArchivoNoSeleccionado (lista_input [i])) {
			total_errores++;
			error_validacion = {
				error: "Es necesario seleccionar un archivo",
				input: lista_input [i],
				num_errores: total_errores
			};
		}
		
		// Validar formato de archivos aceptados (en inputs de tipo file)
		if (FormatoNoAceptado (lista_input [i])) {
			total_errores++;
			error_validacion = {
				error: "El formato del archivo subido no es válido",
				input: lista_input [i],
				num_errores: total_errores
			};
		}

		// Validar rangos (min, max) en los input de tipo numérico
		const error_rango = FueraDeRangoNumerico (lista_input[i]);

		// Validar rango de fechas
		const error_fecha = FechaFueraDeRango (lista_input [i]);

		const msg = error_rango ? error_rango ['error'] : 
					error_fecha ? error_fecha ['error'] : 
					false;

		if (msg) {
			total_errores++;
			error_validacion = {
				error: msg, 
				input: lista_input[i],
				num_errores: total_errores
			};
		}
	}
	if (error_validacion) return error_validacion;
	return null;
}

function IndicarError (
	$campo, 
	mensaje, 
	$pregunta = null, 
	nuevo_error = false, 
	tooltip = null
) {
	// Obtener campo (o componente) en el que se colocará el tooltip
	const $campo_principal = ($campo.dataset.alineacion && 
							 $campo.dataset.alineacion == 'horizontal') ?
							 $campo.closest ('.d-flex').parentElement : 
							 $campo;
							 
	if ($campo && nuevo_error && tooltip) {
		// Ya existe un error de validación previo
		const objetivo = $pregunta ? $pregunta : $campo;
		objetivo.scrollIntoView ({ behavior: 'instant', block: "center" });
		
		MostrarTooltip ($campo_principal, mensaje, 3000, tooltip);		
		return;
	}
	
	$campo.classList.add('is-invalid');
				
	// Crear instancia de tooltip
	const nuevo_tooltip = MostrarTooltip ($campo_principal, mensaje, 3000);
	
	error = $pregunta ? 
			{input: $campo, tooltip: nuevo_tooltip, pregunta: $pregunta} :
			{input: $campo, tooltip: nuevo_tooltip};
	
	error ['input'].onchange = function (e) {
		if (error ['tooltip']) error ['tooltip'].remove ();
		this.classList.remove ('is-invalid');
		error = false;
	}
	const objetivo = $pregunta ? $pregunta : $campo;
	objetivo.scrollIntoView ({behavior: 'instant', block: "center" });
}