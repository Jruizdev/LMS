const max_opciones_pp =	 4; 	  // Número máximo de opciones de respuesta por pregunta
let   num_preg =		 0;	  	  // Numero actual de preguntas
let   id_preg =			 0;	  	  // Identificador incremental de las preguntas del banco
let   max_preguntas = 	 null; 	  // Número máximo de preguntas que se mostrarán por evaluación
let   calificacion_min = null; 	  // Calificación mínima aprobatoria por evaluación
let   banco_preg = 		 null; 	  // Banco de preguntas

function DefinirMaxPreguntas (valor) {
	// Asignar el número máximo de preguntas por evaluación
	max_preguntas = valor;
}

function DefinirCalificacionMin (valor) {
	// Asignar la calificación mínima de la evaluación para aprobar el curso
	calificacion_min = valor;
}

function AgregarOpcionPregunta ($elemento, $componente = null) {
	
	const $pregunta = $elemento.closest ('.pregunta');
	
	// Limitar máximo número de opciones por pregunta
	if (ObtenerNumOpciones ($pregunta) >= max_opciones_pp) {
		MostrarMensaje (
			"No se pudo agregar la opción",
			"Únicamente se pueden agregar " + max_opciones_pp + 
			" opciones de respuesta por cada pregunta.",
			null, icono_error, false
		);  return false;
	}
	
	// Configurar opción de respuesta
	const $comp_opciones = 	$pregunta.querySelector ('.opciones');
	const $opcion = 		$temp_opcion.content.cloneNode (true);
	const $opcion_input =	$opcion.querySelector ('input[type="Text"]');
	const $opcion_radio = 	$opcion.querySelector ('input[type="radio"]');

	// Convertir caracteres codificados a texto
	const convertir_texto = (codigo_html) => {
		const span = 	 document.createElement("span");
		span.innerHTML = codigo_html;
		return span.textContent;
	};
	
	if ($componente) {
		// Recuperar opción si se trata de un borrador
		$opcion_input.setAttribute ("value", convertir_texto ($componente.dataset.texto));
		
		if($componente.dataset.checked == "true") {
			$opcion_radio.setAttribute ("checked", "true");
		}
		$componente.remove ();
	}
	
	$opcion_radio.name = "op_" + $pregunta.dataset.id_preg;
	
	// Agregar opción a la pregunta correspondiente
	$comp_opciones.appendChild ($opcion);

	return true;
}

function MarcarOpcionDefault ($pregunta, $opcion) {
	if ($opcion.querySelector ('input[type="radio"]').checked) {
		const $primera = $pregunta.querySelector ('.opcion:not(.eliminar)'); 
		$primera.querySelector ('input[type="radio"]').checked = true;
		return true;
	} 	return false;
}

function EliminarOpcionPegunta ($opcion) {
	const $pregunta = $opcion.closest ('.pregunta');
	$opcion = $opcion.parentElement.parentElement;
	
	// Evitar que el usuario elimine todas las opciones, al menos debe existir 1
	const opciones_restantes = ObtenerNumOpciones ($pregunta);
	if (opciones_restantes <= 1) {
		MostrarMensaje (
			"No se puede eliminar esta opción",
			"Debe existir al menos una opción de respuesta por cada pregunta.",
			null, icono_error, false
		);  return false;
	}
	
	// Esperar animación para el eliminado del componete
	$opcion.classList.add('eliminar');
	setTimeout (() => {
		$opcion.remove();
	}, 450);
	
	MarcarOpcionDefault ($pregunta, $opcion);
	return true;
}

function ObtenerNumOpciones ($pregunta) {
	const $opciones = 		$pregunta.querySelectorAll ('.opcion:not(.eliminar)');
	let opcion_correcta = 	false;

	// Retornar número total de opciones creadas por pregunta
	return $opciones.length;
}

function EliminarPregunta ($pregunta) {
	
	if(num_preg <= 1) {
		MostrarMensaje (
			"No se puede eliminar esta pregunta",
			"El banco de preguntas debe de contener al menos una pregunta.",
			null, icono_error, false 
		);  return false;
	}
	
	// Eliminar errores de validación asociados a la pregunta
	if (error ['pregunta'] && error ['pregunta'] == $pregunta) error = null;
	
	$pregunta.classList.add ('eliminar');
	setTimeout (() => {
		
		// Esperar animación para eliminar componente
		$pregunta.parentElement.remove();
		ActualizarEnumeracion ();
		num_preg--;
	}, 400);
	return true;
}

function CrearElementoPregunta () {
	return $temp_pregunta.content.cloneNode (true);
}

function CrearElementoVentanaPreg () {
	return $temp_ventana_eliminable.content.cloneNode (true);
}

function ObtenerContenedorPreg () {
	return document.querySelector ('#preguntas');
}

function AgregarPreguntaAVentana (
	$titulo, $pregunta, $ventana, $ventana_body, $contenedor
) {
	$ventana_body.appendChild ($pregunta);
	$titulo.textContent = "Pregunta " + num_preg;

	// Agregar pregunta al contenedor (banco de preguntas)
	$contenedor.insertAdjacentElement ('afterbegin', $ventana.querySelector ('div'));
}

async function AgregarNuevaPregunta ($componente_preg = null) {
	// Aumentar el núemero total de preguntas
	num_preg++;
	id_preg++;
	
	// Configurar nuevo elemento de pregunta
	const $elemento_pregunta = 	CrearElementoPregunta ();
	const $ventana = 			CrearElementoVentanaPreg ();
	const $contenedor = 		ObtenerContenedorPreg () ;
	const $pregunta = 			$elemento_pregunta.querySelector ('.pregunta');
	const $pregunta_input =		$pregunta.querySelector ('input[data-tipo="pregunta"]');
	const $puntaje_input =		$pregunta.querySelector ('input[data-tipo="puntaje"]');
	const $ventana_body = 		$ventana.querySelector ('div[data-tipo="body"]');
	const $titulo = 			$ventana.querySelector ('h4[data-tipo="titulo"]');
	
	$pregunta.dataset.id_preg = id_preg;
	
	if ($componente_preg && 
	   $componente_preg.dataset.texto && 
	   $componente_preg.dataset.puntaje) {

		const decodificarHTML = (texto_html) => {
			var textarea = document.createElement('textarea');
			textarea.innerHTML = texto_html;
			return textarea.value;
		};

		// Pregunta recuperada de borrador
		$pregunta_input.setAttribute (
			"value", decodificarHTML ($componente_preg.dataset.texto)
		);
		$puntaje_input.setAttribute (
			"value", $componente_preg.dataset.puntaje
		);
	}
	
	// Agregar opciones de respuesta a la pregunta
	const $opciones = ($componente_preg) ? 
					  $componente_preg.querySelectorAll ('div[data-componente="opcion"]') :
					  [];
	
	// Opción por defecto
	if ($opciones.length == 0) AgregarOpcionPregunta ($pregunta);
	
	// Opciones recuperadas de borrador
	$opciones.forEach (($componente_opcion) => {
		AgregarOpcionPregunta ($pregunta, $componente_opcion);
	});
	
	AgregarPreguntaAVentana (
		$titulo, $pregunta, $ventana, $ventana_body, $contenedor
	);
	return num_preg;
}

function ActualizarEnumeracion () {
	const $preguntas = document.querySelectorAll ('.ventana-eliminable');
	
	// Actualizar enumeración de las preguntas
	for (let i = 0; i < $preguntas.length; i++) {
		const $titulo = $preguntas[i].querySelector ('h4[data-tipo="titulo"]');
		$titulo.textContent = "Pregunta " + ($preguntas.length - i);
	}
}

function ObtenerVentanasEliminables () {
	return document.querySelectorAll ('.ventana-eliminable');	
}

function numPregValido (min_num_preg) {
	// Retornar si el número de preguntas del banco es mayor que el mínimo
	return num_preg >= min_num_preg;
}

function ValidarBancoPreg () {

	// Eliminar posibles indicadores flotantes de error anteriores
	EliminarIndicadoresFlotantes ();

	// Obtener elementos (ventanas) de preguntas
	const $preguntas = ObtenerVentanasEliminables ();

	let total_error_opciones =   0;
	let total_error_campos = 	 0;
	let ultimo_error = 			 null;
	let mostrarErrorEncontrado = null;
	
	for (let i = 0; i < $preguntas.length; i++) {
		const $inputs = 	$preguntas [i].querySelectorAll ('input');
		const $opciones = 	$preguntas [i].querySelectorAll ('input[type="radio"]');

		let error_validacion = 	null;
		let error_opciones = 	null;
		
		// Validar que se haya seleccionado una opción de respuesta
		error_validacion = ValidarSeleccionRadio ($opciones);
		
		if (error_validacion) {
			ultimo_error = error_validacion;
			mostrarErrorEncontrado = () => IndicarError (
				error_validacion.input, 
				error_validacion.error, 
				$preguntas [i], true, null
			);
			total_error_opciones++;
		}

		// Validar campos del banco de preguntas
		error_opciones = ValidarMultiplesEntradas ($inputs);
		
		if (error_opciones) {
			ultimo_error = error_opciones;
			mostrarErrorEncontrado = () => IndicarError (
				error_opciones.input, 
				error_opciones.error, 
				$preguntas [i], true, null
			);
			total_error_campos++;
		}
		
	} 
	if (ultimo_error) {

		// Indicar el error de validación al usuario
		mostrarErrorEncontrado ();

		// Múltiples errores de validación de campos
		if (total_error_campos > 1) {
			MostrarIndicacionFlotante (
				'<span>Se detectó más de una pregunta con <b>campos vacíos</b>, en el banco de preguntas.</span>',
				'<p>Recuerda que, por cada pregunta, debes llenar los campos correspondientes a:</p>' +
				'<ul>' + 
					'<li>La redacción de la pregunta</li>' +
					'<li>El puntaje de respuesta correcta</li>' +
					'<li>La redacción de las opciones de respuesta</li>' +
				'</ul>' +
				'<p>Revisa que todos los campos se encuentren llenos e inténtalo de nuevo.</p>',
				'error'
			); return ultimo_error;
		}

		// Múltiples errores de validación de respuesta correcta
		if (total_error_opciones > 1) {
			MostrarIndicacionFlotante (
				'<span>Se detectó más de una pregunta <b>sin respuesta seleccionada</b> en el banco de preguntas.</span>',
				'<p>Es probable que más de una pregunta del banco de preguntas no tenga una respuesta seleccionada.</p>' +
				'<p>Por favor, asegúrate de haber indicado la respuesta correcta en todas las preguntas, e inténtalo de nuevo.</p>',
				'error'
			);
		} return ultimo_error;
	} 	  return null;
}

function escaparHTML (texto) {
    // Crear un elemento temporal para usar su capacidad de escapar contenido
    const div = 		document.createElement ('div');
    div.textContent = 	texto;
    return div.innerHTML; // Devuelve el contenido escapado como HTML seguro
}

function ObtenerBanco () {
	let   preguntas = 	[];
	let   max_puntaje = 0;
	const $preguntas = 	document.querySelectorAll ('.ventana-eliminable');
	const max_preg = 	document.querySelector ('#max-preg').value;
	const calif_min = 	document.querySelector ('#calif-min').value;
	
	$preguntas.forEach (($pregunta) => {
		
		// Obtener todos los elementos de pregunta del editor
		const pregunta = 	escaparHTML (
			$pregunta.querySelector ('input[data-tipo="pregunta"]').value.trim()
		);
		const puntaje = 	$pregunta.querySelector ('input[data-tipo="puntaje"]').value;
		const $opciones = 	$pregunta.querySelectorAll ('.opcion');
		let respuesta_correcta = null;
		let opciones = 			 [];
		
		$opciones.forEach (($opcion, i) => {
			
			// Configurar opciones de respuesta para cada pregunta
			const opcion = escaparHTML (
				$opcion.querySelector ('input[type="text"]').value.trim()
			);
			const opcion_correcta = $opcion.querySelector ('input[type="radio"]').checked;
			const nueva_opcion = 	{ texto: opcion };

			AgregarAJson (opciones, nueva_opcion);
			
			// Obtener respuesta correcta
			if (opcion_correcta) respuesta_correcta = {
				texto: nueva_opcion ['texto'],
				indice: i
			};
		});
		
		const nueva_pregunta = {
			pregunta: 	pregunta, 
			puntaje: 	puntaje,
			opciones: 	opciones, 
			respuesta: 	respuesta_correcta
		};

		AgregarAJson (preguntas, nueva_pregunta);
		
		// Establecer puntaje máximo (suma de puntajes de las preguntas)
		max_puntaje += parseInt (puntaje);
	});
	
	return {
		// Devolver json con la información del banco de preguntas
		max_puntaje: 	  max_puntaje,
		max_preguntas: 	  parseInt (max_preg),
		calificacion_min: parseInt (calif_min),
		banco: 			  preguntas
	};
}

function AgregarAJson (json, nuevo_elemento) {
	json.splice (
		// Agregar nuevo dato a objeto JSON
		json.length, 0, nuevo_elemento
	);
}