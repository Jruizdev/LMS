<?php

function ImprimirResultados ($aprobado, $registrado, $puntaje_obtenido, $evaluacion) {

	$puntuacion_max = 		($puntaje_obtenido >= $evaluacion->puntaje_total) ? '' : 'hidden';
	$puntuacion_aprobado = 	($puntaje_obtenido < $evaluacion->puntaje_total) ? '' : 'hidden';

	echo $aprobado ? 
	'<div class="alert alert-success d-flex flex-column mt-3 mb-0 py-4" role="alert" style="animation-play-state: paused;">
		<div class="d-flex">
			<div class="d-flex flex-column w-75">
				<div class="w-100 d-flex mb-3" style="animation-play-state: paused;">
					<svg class="mx-auto" style="max-width: 100px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#386600" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"></path></svg>
				</div>
				<h5 class="m-auto"><b class="text-nowrap">Obtuviste '.$puntaje_obtenido.' puntos de '.$evaluacion->puntaje_total.'</b></h5>
			</div>
			<span class="mx-4" style="border-left: 1px solid #83a260ff; height: 100%;"></span>
			<div class="d-flex flex-column msg text-center" style="animation-play-state: paused;">
				<h4 class="mb-4 text-start"><b>CURSO APROBADO</b></h4>
				<div class="d-flex flex-column" style="animation-play-state: paused;">
					<p class="m-0 mb-3 text-break text-start" '.$puntuacion_max.'>¡Felicidades! Todas tus respuestas fueron correctas. Si deseas repasar el contenido, puedes acceder al curso nuevamente desde la sección de "Cursos aprobados".</p>
					<p class="m-0 mb-3 text-break text-start" '.$puntuacion_aprobado.'>A continuación, se te mostrarán las preguntas en las que tuviste errores, para que puedas reforzar tus conocimientos. Si deseas repasar el contenido, puedes acceder al curso nuevamente desde la sección de "Cursos aprobados".</p>
				</div>
			</div>
		</div>
	</div>'
	:
	'<div class="alert alert-danger d-flex flex-column mt-3 py-4" role="alert" style="animation-play-state: paused;">
		<div class="d-flex">
			<div class="d-flex flex-column w-75">
				<div class="w-100 d-flex mb-3" style="animation-play-state: paused;">
					<svg class="mx-auto" style="max-width: 100px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="100px"><path fill="#911919" d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM174.6 384.1c-4.5 12.5-18.2 18.9-30.7 14.4s-18.9-18.2-14.4-30.7C146.9 319.4 198.9 288 256 288s109.1 31.4 126.6 79.9c4.5 12.5-2 26.2-14.4 30.7s-26.2-2-30.7-14.4C328.2 358.5 297.2 336 256 336s-72.2 22.5-81.4 48.1zM144.4 208a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm192-32a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"></path></svg>
				</div>
				<h5 class="m-auto"><b class="text-nowrap">Obtuviste '.$puntaje_obtenido.' puntos de '.$evaluacion->puntaje_total.'</b></h5>
			</div>
			<span class="mx-4" style="border-left: 1px solid #c87676ff; height: 100%;"></span>
			<div class="d-flex flex-column msg text-center" style="animation-play-state: paused;">
				<h4 class="text-start mb-4"><b>CURSO NO APROBADO</b></h4>
				<div style="animation-play-state: paused;">
				<hr class="my-2 border-0">
					<p class="text-break text-start">A continuación, 
					se te mostrarán las preguntas en las que tuviste errores, 
					para que puedas repasar el contenido del curso y mejorar tu puntuación
					en futuros intentos.</p>
					<em><p class="mb-0 text-break text-start"><b>Nota: </b>Considera que puedes volver a realizar la
					evaluación, accediendo nuevamente al curso, desde la sección de 
					"Cursos pendientes".</p></em>
				</div>
			</div>
		</div>
	</div>';
	
	if ($aprobado && !$registrado) {
		echo '
		<div class="alert alert-danger d-flex flex-column mt-3" role="alert">
			<h5 class="mx-auto"><b>Hubo un problema la registrar la calificación</b></h5>
			<div class="d-flex msg w-100">
				<div class="d-flex flex-column w-100">
					<p class="m-0 text-break mx-auto">
						No fue posible registrar los resultados obtenidos debido a un problema con el servidor. 
						Por favor, contacte a su instructor.
					</p>
				</div>
			</div>
		</div>';
	}
}

function ImprimirRespuestasCorrectas ($preguntas_incorrectas) {
	$separador = count ($preguntas_incorrectas) > 0 ? '<hr class="mt-0 mb-5"><h4 class="fw-bold mx-auto text-start mb-3 text-uppercase">Revisión de respuestas incorrectas:</h4><hr class="my-4 border-0">' : '';
	echo '<div id="contenedor-preguntas">'.$separador.'</div>';
	
	for ($i = 0; $i < count ($preguntas_incorrectas); $i++) {
		// Obtener respuesta seleccionada por el usuario
		$respuesta_usuario = is_object ($preguntas_incorrectas [$i]->respuesta_usuario) ?
							 $preguntas_incorrectas [$i]->respuesta_usuario->indice :
							 $preguntas_incorrectas [$i]->respuesta_usuario;

		// Recuperar preguntas con respuesta correcta
		echo '
		<div
		data-tipo="pregunta-evaluacion"	
		data-resp_usuario="'.htmlspecialchars ($respuesta_usuario).'" 
		data-id_preg="'.$preguntas_incorrectas [$i]->id_pregunta.'">
			<div
			data-tipo="pregunta">'.
				$preguntas_incorrectas [$i]->pregunta.'<small style="color: #F00"> (Respuesta incorrecta)</small>
			</div>
			<ul data-tipo="opciones">';
		for ($j = 0; $j < count ($preguntas_incorrectas [$i]->opciones); $j++) {
			echo '
			<div id="op_'.$i.'" data-componente="opcion-evaluacion">'.
				$preguntas_incorrectas [$i]->opciones [$j]->texto.
			'</div>';
		} 
		echo '</ul></div>';
	}
}

function AsignarCursoSecuencial ($bd, $curso_secuencial, $nominas_colaboradores, $asignacion) {
	
	$asignado = $bd->AutoAsignarCursoSecuencial (
		$curso_secuencial ['Id_curso'], 
		$nominas_colaboradores,
		$asignacion
	);

	$portada = 
		$curso_secuencial ['Portada'] != NULL ? '<img src="uploads/portadas/'.$curso_secuencial ['Portada'].'" style="position: absolute; height: 100%; object-fit: cover;">' :
		'<svg class="m-auto" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%)" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 66.26" style="enable-background:new 0 0 122.88 66.26" xml:space="preserve" width="100"><style type="text/css">
		.st0{fill-rule:evenodd;clip-rule:evenodd;}
		</style><g><path class="st0" d="M2.73,60.82h10.51c-1-0.26-1.75-1.18-1.75-2.26V2.33c0-1.28,1.05-2.33,2.33-2.33h94.64 c1.28,0,2.33,1.05,2.33,2.33v56.22c0,1.08-0.74,2-1.75,2.26h11.12c1.5,0,2.73,1.22,2.73,2.73l0,0c0,1.5-1.22,2.73-2.73,2.73H2.73 c-1.5,0-2.73-1.22-2.73-2.73l0,0C0,62.04,1.22,60.82,2.73,60.82L2.73,60.82L2.73,60.82z M29.91,10.97h24.38v29.24 c-0.05,0.82-1.1,0.84-2.24,0.79H29.52c-1.58,0-2.87,1.29-2.87,2.87c0,1.58,1.29,2.87,2.87,2.87h23.57v-3.05h2.24v3.88 c0,0.71-0.58,1.28-1.28,1.28H29.63c-2.84,1.05-5.16-1.27-5.16-4.11V16.41C24.48,13.42,26.92,10.97,29.91,10.97L29.91,10.97z M66.73,47.29c-0.81,0-1.47-0.71-1.47-1.58s0.66-1.58,1.47-1.58h29.29c0.81,0,1.47,0.71,1.47,1.58s-0.66,1.58-1.47,1.58H66.73 L66.73,47.29z M66.52,12.78h32.27c0.69,0,1.26,0.57,1.26,1.26v4.5c0,0.68-0.57,1.26-1.26,1.26H66.52c-0.68,0-1.26-0.56-1.26-1.26 v-4.5C65.26,13.34,65.83,12.78,66.52,12.78L66.52,12.78z M66.73,29.63c-0.81,0-1.47-0.71-1.47-1.58c0-0.87,0.66-1.58,1.47-1.58 h27.28c0.81,0,1.47,0.71,1.47,1.58c0,0.87-0.66,1.58-1.47,1.58H66.73L66.73,29.63z M66.73,38.46c-0.81,0-1.47-0.71-1.47-1.58 s0.66-1.58,1.47-1.58h23.03c0.81,0,1.47,0.71,1.47,1.58s-0.66,1.58-1.47,1.58H66.73L66.73,38.46z M30.92,15.22h0.91 c0.46,0,0.84,0.31,0.84,0.68v21.37c0,0.37-0.38,0.68-0.84,0.68h-0.91c-0.46,0-0.84-0.31-0.84-0.68V15.9 C30.08,15.52,30.46,15.22,30.92,15.22L30.92,15.22z M15.47,3.65h91.65v54.24H15.47V3.65L15.47,3.65L15.47,3.65z M59.15,61.84h7.67 c0.72,0,1.31,0.59,1.31,1.31l0,0c0,0.72-0.59,1.31-1.31,1.31h-7.67c-0.72,0-1.31-0.59-1.31-1.31l0,0 C57.84,62.42,58.43,61.84,59.15,61.84L59.15,61.84L59.15,61.84z"></path></g></svg>';
	
	echo '
	<div class="d-flex flex-column px-5 mx-auto mt-3" style="max-width: 800px;">
		<h4 class="mb-4 mx-auto text-break text-uppercase">Siguiente parte del curso:</h4>
		<hr class="mt-0 mb-4 border-0">
		<div class="d-flex flex-column flex-fill">
			<div class="portada mx-auto mb-3" style="width: 200px; height: 200px; background-color: #EEE; border-radius: 100%; position: relative; overflow: hidden;">
				<a href="visualizador.php?curso_int='.$curso_secuencial ['Id_curso'].'&visualizar=true">'.$portada.'</a>
			</div>
			<a href="visualizador.php?curso_int='.$curso_secuencial ['Id_curso'].'&visualizar=true" style="text-decoration: none; color: #000;">
				<h5 class="mx-auto text-break text-center">'.$curso_secuencial ['curso_secuencial'].'</h5>
			</a>
		</div>
		<span class="mx-3" style="border-left: 1px solid #CCC; height: 100%; !important;"></span>
		<div class="d-flex flex-column flex-fill">';
		echo $asignado ? '<p class="text-break text-center">Éste nuevo curso se te asignará de manera automática. 
			Puedes continuar con tu aprendizaje seleccionando el nuevo curso asignado en la sección 
			<a href="cursos_pendientes.php" style="color: #F00; text-decoration: none">Cursos pendientes</a>.</p>
			<p class="text-break text-center">¡Sigue avanzando para completar la formación completa!</p></div>' :
			'<div class="alert alert-danger" role="alert"><p class="text-break mb-0">
				Hubo un problema inesperado al asignar el curso de manera automática. 
				Puedes acceder a la siguiente parte del curso dando clic 
				<a href="visualizador.php?curso_int='.$curso_secuencial ['Id_curso'].'&visualizar=true" style="text-decoration: none; color: #420909ff;">
					<b>aquí</b>
				</a>, o puedes encontrarlo la sección de 
				<a href="catalogo_cursos.php?nomina='.$asignacion.'&buscar='.$curso_secuencial ['curso_secuencial'].'" style="text-decoration: none; color: #420909ff;">
					<b>Cursos Disponibles</b>
				</a>.
			</p></div>';
		echo '</div>';
}

function NotificarCursoAprobado ($notificacion, $no_nomina) {
	// Obtener correos a los que se realizará la notificación
	$correos = implode (
		',', array_map (function ($elemento) { return $elemento ['email']; }, $notificacion)
	);

	// Solicitar envío de correo de notificación mediante proxy
	$url = 'http://10.25.1.24/BMXQ_PR_OEE/CursosNotificacion.php';
	$parametros = [
		'notificar' => 	'curso-aprobado',
		'no_nomina' => 	$no_nomina,
		'nombre' => 	$notificacion [0]['Nombre'],
		'curso' => 		$notificacion [0]['Curso'],
		'asunto' => 	$notificacion [0]['Asunto'],
		'correos' => 	$correos
	];

	// Inicializar petición cURL
	$ch = curl_init ($url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_POST, true);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $parametros);
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);

	// Ejecuta la petición
    $response = curl_exec ($ch);
    curl_close ($ch);
}

function MostrarResultadoEvaluacion () {
	if (!isset ($_POST ['evaluacion'])) {
		
		// No se recibieron resultados de evaluación
		header ('Location: inicio.php'); exit;
	}

	// Mostrar resultados en caso de que se haya realizado una evaluación
	$evaluacion = 			 json_decode ($_POST ['evaluacion']);
	$colaboradores =		 $evaluacion->colaboradores->nominas;
	$preguntas_incorrectas = [];
	$puntaje_obtenido = 	 0;

	$bd = new BDcursos ();

	// Consultar si se trata de un curso secuencial
	$curso_secuencial = $bd->ConsultarCursoSecuencial ($evaluacion->id_curso);

	foreach ($evaluacion->preguntas as $pregunta) {
		if (is_object ($pregunta->respuesta)) {
			if ($pregunta->respuesta->indice == 
				$pregunta->respuesta_usuario->indice) 
			{
				$puntaje_obtenido += $pregunta->puntaje;
				continue;
			}
			$preguntas_incorrectas [] = $pregunta;
		} 
		else {
			if (
				htmlspecialchars_decode ($pregunta->respuesta) == 
				$pregunta->respuesta_usuario->texto
			) {
				$puntaje_obtenido += $pregunta->puntaje;
				continue;
			} 
			$preguntas_incorrectas [] = $pregunta;
		}
	}
	
	// Determinar si el usuario aprobó o no el curso interno
	$registrado = false;
	$aprobado =   ($puntaje_obtenido >= $evaluacion->puntaje_min) ? 
				  true : false;
	
	if ($aprobado) {
		// El empleado aprobó el curso
		$registrado = RegistrarCursoAprobado (
			$evaluacion->id_curso,
			$evaluacion->version,
			$colaboradores,
			$puntaje_obtenido,
			$evaluacion->puntaje_total
		);
		// Remover curso, de los cursos pendientes
		$CP_removido = RemoverCursoPendiente (
			$evaluacion->id_curso,
			$evaluacion->version,
			$colaboradores
		);
		// Consultar si tiene asociada una notificación de aprobación y realizarla en caso de que así sea
		foreach ($evaluacion->colaboradores->nominas as $no_nomina) {
			$notificacion = $bd->ConsultarNotificarAprobado ($evaluacion->id_curso, $no_nomina);
			if (count ($notificacion) > 0) {
				NotificarCursoAprobado ( $notificacion, $no_nomina );
			}
		}
	}

	// Imprimir resultados en pantalla
	ImprimirResultados ($aprobado, $registrado, $puntaje_obtenido, $evaluacion);

	// Asignar siguiente curso (en caso de que el curso sea secuencial)
	if ($curso_secuencial) AsignarCursoSecuencial (
		$bd,
		$curso_secuencial, 
		$colaboradores,
		$evaluacion->no_nomina
	);

	// Mostrar respuestas incorrectas en pantalla
	ImprimirRespuestasCorrectas ($preguntas_incorrectas);
	return $aprobado ? 'aprobado' : 'no-aprobado';
}

?>