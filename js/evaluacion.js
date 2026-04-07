$tmp_preguntas = document.querySelector ('#tmp-preguntas');

if ($tmp_preguntas) {
	$tmp_preguntas.remove();
}

function EnviarEvaluacion (curso_id, version, no_nomina) {
	
	// Crear formulario mediante el cual se enviarán los resultados
	const formulario_evaluacion = 	document.createElement ('form');
	formulario_evaluacion.method = 	'POST';
    formulario_evaluacion.action = 	'consultar_evaluacion.php';
	
	// Obtener todas las preguntas y sus opciones de respuesta
	const $contenedores_opciones = 	document.querySelectorAll ('ul.opciones');
	const $preguntas_evaluacion = 	document.querySelectorAll ('.pregunta-evaluacion');
	
	for (let i = 0; i < $preguntas_evaluacion.length; i++) {
		// Establecer respuesta en blanco por defecto
		let resp_seleccionada = {
			texto: '',
			indice: -1
		};
		const $opciones_resp = 	$contenedores_opciones [i].querySelectorAll ('li.opcion-evaluacion');
		const id_preg = 		$preguntas_evaluacion [i].querySelector ('.id-pregunta span').textContent;
		
		// Obtener respuestas seleccionadas por el usuario
		$opciones_resp.forEach (($opcion, i) => {
			const $radio = 		$opcion.querySelector ('input[type="radio"]');
			const $respuesta = 	$opcion.querySelector ('label');
			
			if ($radio.checked) {
				resp_seleccionada = {
					texto: $respuesta.textContent,
					indice: i
				};
			} 
		});
		// Agregar respuesta seleccionada al arreglo de preguntas
		evaluacion [i] = {
			...evaluacion [i], 
			respuesta_usuario: resp_seleccionada,
			id_pregunta: id_preg
		};
	}

	// Obtener los colaboradores de la evaluación
	const colaboradores_eval = ObtenerColaboradores ();
	
	// Información de la evaluación realizada
	const evaluacion_json = {
		no_nomina: 		no_nomina,
		id_curso: 		curso_id,
		version: 		version,
		preguntas: 	 	evaluacion, 
		puntaje_min:   	puntaje_min,
		puntaje_total: 	puntaje_total,
		colaboradores: 	colaboradores_eval
	};

	const hiddenInput = document.createElement ("input");
    hiddenInput.type = 	"hidden";
    hiddenInput.name = 	"evaluacion";
    hiddenInput.value = JSON.stringify (evaluacion_json);
	
	// Enviar formulario al visualizador de cursos revisión
	formulario_evaluacion.appendChild (hiddenInput);
	document.body.appendChild (formulario_evaluacion);
	formulario_evaluacion.submit ();
}