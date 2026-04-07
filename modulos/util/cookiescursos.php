<?php
require_once ('curso.php');
require_once ('BD.php');

// Curso interno, recuperado de las cookies
$c_curso_interno = null;

// Recuperar borrador utilizando su id (enviado por método get)
if (isset ($_GET ['curso_int'])) {
	
	// Limpiar cookie al recibir un curso
	EliminarCookie ('editar-curso');
	
	$id_curso =  $_GET ['curso_int'];
	$BDcursos =  new BDcursos ();
	$resultado = null;
	
	if( // Se editará el curso
		isset ($_GET['editar']) && 
		$_GET ['editar'] == 'true'
	) {
		$resultado = $BDcursos->ObtenerUltimoContenido ($id_curso);
	}
	else {
		// Recuperar versión que se desea visualizar, en caso de que se haya especificado
		$version = isset ($_GET ['version']) ? $_GET ['version'] : null;
		
		// Se visualizará la última versión del curso si la versión es nula
		$resultado = $BDcursos->ObtenerContenido ($id_curso, $version);
	}
	
	if ($resultado) {
		// Almacenar resultado (curso) en las cookies
		$id_int = $resultado ['Id_int'];
		
		if( // Se está visualizando el curso desde fuera del editor (no almacenar en cookies)
			isset ($_GET ['visualizar']) && 
			$_GET ['visualizar'] == 'true'
		) {
			$c_curso_interno = ObtenerCurso (
				$resultado
			);
		}
		else 
		{	// Se está previsualizando el curso desde el editor
			$c_curso_interno = GuardarBorradorCookies (
				$resultado, 
				false, 
				$id_int, 
				$id_curso
			);
		}
	}
}

if (isset ($_COOKIE ['editar-curso'])) {	
	// Cargar curso almacenado en cookies (para editar)
	$c_curso_interno = unserialize ($_COOKIE ['editar-curso']);
	
	$BDentidad = 		new BDcursos ();
	$id_int = 		$c_curso_interno->getIdInt ();
	$contenido = 	null;
	$banco_preg = 	null;
	
	// Recuperar contenido del curso directamente de la BD
	$resultado = $BDentidad->ObtenerCBP ($id_int);
	
	if(!$resultado) return false;	// No existe el curso interno
	
	$contenido = 	$resultado ['Contenido'];
	$banco_preg = 	$resultado ['Banco_preg'];
	$c_curso_interno->setContenido ($contenido);
	$c_curso_interno->setBancoPreg ($banco_preg);
}

function NombreCurso () {
	global $c_curso_interno;
	
	if ($c_curso_interno == null) return;
	echo $c_curso_interno->getNombre ();
} 

function DescripcionCurso () {
	global $c_curso_interno;
	
	if ($c_curso_interno == null) return;
	echo htmlspecialchars_decode ($c_curso_interno->getDescripcion ());
}

function ObjetivoCurso () {
	global $c_curso_interno;
	
	if ($c_curso_interno == null) return;
	echo htmlspecialchars_decode ($c_curso_interno->getObjetivo ());
}

function ObtenerTagsCurso () {
	global $c_curso_interno;
	
	if ($c_curso_interno == null || $c_curso_interno->getTags () == '') return;
	$tags = explode (',', $c_curso_interno->getTags ());
	$elementos_tags = '';

	foreach ($tags as $tag) {
		$elementos_tags .= '
		<div class="tag mx-2 my-2">
			<span data-info="texto">'.$tag.'</span>
			<button onclick="EliminarTag (this)">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="var(--entidad-secundario)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/></svg>
			</button>
		</div>
		';
	}
	return [
		'tags' => $elementos_tags,
		'num_tags' => count ($tags)
	];
}

function ComentariosVersion () {
	global $c_curso_interno;
	
	if ($c_curso_interno == null) return;
	echo $c_curso_interno->getComentarios ();
}

function ContenidoCurso () {
	global $c_curso_interno;
	if ($c_curso_interno == null) return;
	
	echo $c_curso_interno->getContenido();
}

function BancoPreguntas () {
	global $c_curso_interno;
	
	if ($c_curso_interno == null) {
		// Se trata de un curso nuevo (no hay datos cargados)
		echo '
		<div data-componente="banco-preguntas" data-max_preg="5" data-calif_min="60">
			<div data-componente="pregunta"></div>
		</div>
		'; return;
	}
	
	// Recuperar banco de preguntas como objeto JSON
	$banco_preg = json_decode ($c_curso_interno->getBancoPreg());
	
	// Definir componente de banco de preguntas, con las preguntas recuperadas de la BD
	$componente_banco = '
	<div 
	data-componente="banco-preguntas"
	data-max_preg="'.$banco_preg->max_preguntas.'"
	data-calif_min="'.$banco_preg->calificacion_min.'">
	';
	
	foreach ($banco_preg->banco as $pregunta) {
		$componente_banco .= '
		<div 
		data-componente="pregunta"
		data-texto="'.htmlspecialchars ($pregunta->pregunta, ENT_QUOTES).'"
		data-puntaje="'.$pregunta->puntaje.'">';

		// Comprobar si la respuesta contiene índice (A raíz de la actualización del método de evaluación)
		$contiene_indice = is_object ($pregunta->respuesta);

		// Obtener opción de respuesta correcta
		$respuesta = $contiene_indice ? 
					 $pregunta->respuesta->indice :
					 $pregunta->respuesta;

		foreach ($pregunta->opciones as $i => $opcion) {
			$componente_banco .= '
			<div data-componente="opcion" 
			data-texto="'.htmlspecialchars ($opcion->texto, ENT_QUOTES).'"
			data-checked="'.
				((!$contiene_indice && ($respuesta == $opcion->texto) || 
				   $contiene_indice && $respuesta == $i) ? 
				   "true" : "false").
			'">
			</div>
			';
		}
		$componente_banco .= '</div>';
	}
	
	$componente_banco .= '</div>';
	echo $componente_banco;
}

function AgregarElemento ($tipo_elemento, $contenido) {
	echo "<div data-componente='".$tipo_elemento.
		 "' data-valor='".$contenido."'></div>";
}

function EliminarCookie ($cookie) {
	if (isset ($_COOKIE[$cookie])) {
		unset ($_COOKIE[$cookie]); 
		setcookie ($cookie, null, -1);
		return true;
	} 	return false;
}

function LimpiarCookieCurso () {
	EliminarCookie ('editar-curso');
	$c_curso_interno = 	null;
}

function ObtenerCurso ($resultado) {
	/* 
	//	Crear instancia de curso interno, 
	//	con las propiedades obtenidas de la BD
	*/
	$BDentidad = new BDentidad ();
	
	// Obtener nombre del creador del curso
	$consulta = $BDentidad->ConsultarUsuario ($resultado['No_nomina'], false);
	$tags = isset ($resultado ['Tags']) ? $resultado ['Tags'] : null;
	$curso = new CursoInterno (
		$resultado ['Nombre'],
		$resultado ['Descripcion'],
		$resultado ['Objetivo'],
		'INT',
		$tags,
		$resultado ['Portada']
	); 
	$curso->setIdCurso	 	 ($resultado ['Id_curso']);
	$curso->setIdInt	 	 ($resultado ['Id_int']);
	$curso->setContenido 	 ($resultado ['Contenido']);
	$curso->setBancoPreg 	 ($resultado ['Banco_preg']);
	$curso->setComentarios	 ($resultado ['Comentarios']);
	$curso->setDepartamento  ($resultado ['Departamento']);
	$curso->setVersionActual ($resultado ['Version']);
	$curso->setFecha		 ($resultado ['Fecha']);
	$curso->setCreador		 (is_array ($consulta) ? $consulta ['nombre'] : '');
	$curso->setBorrador 	 (false);
	
	return $curso;
}

function GuardarBorradorCookies (
	$curso, 
	$json = 		 true, 
	$id_int = 		 null, 
	$id_curso = 	 null, 
	$version_actual = null
) {
	global $sesion;
	
	$nombre_curso = 	 null;
	$descripcion_curso = null;
	$objetivo_curso = 	 null;
	$tags =				 null;
	$portada =			 null;
	$comentarios = 		 null;
	$contenido_json = 	 null;
	$banco_preg = 		 null;
	$departamento = 	 null;
	$creador =			 isset ($sesion) ? 
						 $sesion->getNombreMayus () : 
						 null;
	
	// Obtener elementos del curso interno
	if ($json) {
		$nombre_curso = 		htmlspecialchars ($curso->nombre);
		$descripcion_curso = 	htmlspecialchars ($curso->descripcion);
		$objetivo_curso = 		htmlspecialchars ($curso->objetivo);
		$tags =					$curso->tags;
		$portada =				$curso->portada;
		$comentarios = 			htmlspecialchars ($curso->comentarios);
		$contenido_json = 		json_encode ($curso->contenido);
		$banco_preg = 			json_encode ($curso->banco_preg);
		$departamento =			htmlspecialchars ($curso->departamento);
	} 
	else {
		$nombre_curso = 	 htmlspecialchars ($curso ['Nombre']);
		$descripcion_curso = htmlspecialchars ($curso ['Descripcion']);
		$objetivo_curso = 	 htmlspecialchars ($curso ['Objetivo']);
		$tags = 	 		 htmlspecialchars ($curso ['Tags']);
		$portada = 	 		 htmlspecialchars ($curso ['Portada']);

		// Recuperar comentarios únicamente si ya existe un borrador de la edición curso
		$comentarios = 		 $curso ['Nueva_edicion'] == 0 ? 
							 htmlspecialchars ($curso ['Comentarios']) : '';

		$contenido_json = 	 $curso ['Contenido'];
		$banco_preg =		 $curso ['Banco_preg'];
		$departamento = 	 htmlspecialchars ($curso ['Nombre']);
	}
	
	// Configurar curso interno
	$curso_interno = new CursoInterno (
		$nombre_curso, 
		$descripcion_curso, 
		$objetivo_curso, 
		'INT',
		$tags,
		$portada
	);

	$curso_interno->setIdCurso	 	 ($id_curso);
	$curso_interno->setIdInt	 	 ($id_int);
	$curso_interno->setComentarios	 ($comentarios);
	$curso_interno->setDepartamento  ($departamento);
	$curso_interno->setVersionActual ($version_actual);
	$curso_interno->setCreador		 ($creador);
	$curso_interno->setBorrador 	 (true);

	// Almacenar curso en las cookies
	setcookie ('editar-curso', serialize ($curso_interno));
	
	/*
	// 	Evitar almacenar contenido del curso y banco de preguntas en cookies,
	//	ya que, únicamente se permiten almacenar hasta 4096 caracteres
	//	por cada cookie; generando un error al crear contenido que supere este
	//	límite.
	*/
	$curso_interno->setContenido 	 ($contenido_json);
	$curso_interno->setBancoPreg 	 ($banco_preg);
	
	return $curso_interno;
}

?>