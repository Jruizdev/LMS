<?php

class Curso {
	
	private $nombre;
	private $descripcion;
	private $objetivo;
	private $tags;
	private $portada;
	private $tipo;
	
	public function __construct ($nombre, $descripcion, $objetivo, $tipo, $tags, $portada) {
		$this->setNombre 	  ($nombre);
		$this->setDescripcion ($descripcion);
		$this->setObjetivo 	  ($objetivo);
		$this->setTipo	  	  ($tipo);
		$this->setTags		  ($tags);
		$this->setPortada	  ($portada);
	}
	
	public function setNombre ($nombre) 	 	  { $this->nombre = $nombre; }
	public function setDescripcion ($descripcion) { $this->descripcion = $descripcion; }
	public function setObjetivo ($objetivo) 	  { $this->objetivo = $objetivo; }
	public function setTags ($tags) 	 		  { $this->tags = $tags; }
	public function setPortada ($portada) 	 	  { $this->portada = $portada; }
	public function setTipo ($tipo) 		 	  { $this->tipo = $tipo; }
	public function getNombre () 	  			  { return $this->nombre; }
	public function getDescripcion () 			  { return htmlspecialchars_decode ($this->descripcion); }
	public function getObjetivo () 			  	  { return htmlspecialchars_decode ($this->objetivo); }
	public function getTipo () 	  	  			  { return $this->tipo; }
	public function getTags () 	  	  			  { return $this->tags; }
	public function getPortada () 	  	  		  { return $this->portada; }
}

class Certificacion extends Curso {
	private $nombre_empleado;
	private $no_nomina;
	private $id_certificacion;
	private $id_curso;
	private $fecha;
	private $certificado;
	private $validez;
	
	public function setNombreEmpleado ($nombre)				{ $this->nombre_empleado = $nombre; }
	public function setNumNomina ($no_nomina)				{ $this->no_nomina = $no_nomina; }
	public function setIdCertificacion ($id_certificacion) 	{ $this->id_certificacion = $id_certificacion; }
	public function setIdCurso ($id_curso)					{ $this->id_curso = $id_curso; }
	public function setFecha ($fecha) 						{ $this->fecha = $fecha; }
	public function setCertificado ($certificado) 			{ $this->certificado = $certificado; }
	public function setValidez ($validez) 					{ $this->validez = $validez; }
	public function getNumNomina ()			{ return $this->no_nomina; }
	public function getIdCertificacion () 	{ return $this->id_certificacion; }
	public function getIdCurso ()			{ return $this->id_curso; }
	public function getCertificado () 		{ return $this->certificado; }
	public function getValidez () 			{ return $this->fechaCompleta ($this->validez); }
	public function getFecha () 			{ return $this->fechaCompleta ($this->fecha); }
	public function getNombreEmpleado ()	{ 
		$nombre = strtolower ($this->nombre_empleado);
		$nombre_dividido = explode(" ", $nombre);
		$nombre = "";
		
		// Poner mayúsculas y minúsculas en el nombre
		foreach ($nombre_dividido as $seccion) {
			if (!isset($seccion[0])) continue;
			$seccion[0] = strtoupper($seccion[0]);
			$nombre .= $seccion." ";
		}
		return $nombre; 
	}
	private function fechaCompleta ($fecha_base) {
		
		// No hay fecha base
		if (!$fecha_base) return 'N/A';
		
		// Hay fecha base
		$meses = [
			'January' => 	'enero',
			'February' => 	'febrero',
			'March' => 		'marzo',
			'April' => 		'abril',
			'May' => 		'mayo',
			'June' => 		'junio',
			'July' => 		'julio',
			'August' => 	'agosto',
			'September' => 	'septiembre',
			'October' =>  	'octubre',
			'November' => 	'noviembre',
			'December' => 	'diciembre'
		];
		$fecha = DateTime::createFromFormat('Y-m-d', $fecha_base);
		$mes = 	 $meses [strftime ('%B', $fecha->getTimestamp ())];
		
		return $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y'); 
	}
}

class CursoExterno extends Curso {
	private $id_curso;
	private $id_ext;
	private $fecha;
	private $vigencia;
	private $unidad;

	public function setIdCurso ($id_curso) 	{ $this->id_curso = $id_curso; }
	public function setIdExt ($id_ext) 		{ $this->id_ext = $id_ext; }
	public function setFecha ($fecha) 		{ $this->fecha = $fecha; }
	public function setVigencia ($vigencia) { $this->vigencia = $vigencia; }
	public function setUnidad ($unidad) 	{ $this->unidad = $unidad; }
	public function getIdCurso () 			{ return $this->id_curso; }
	public function getIdExt () 			{ return $this->id_ext; }
	public function getFecha () 			{ return $this->fecha; }
	public function getVigencia () 			{
		// El curso no tiene vigencia
		if ($this->vigencia == null) return 'N/A';

		// Especificar la vigencia del curso en su unidade de tiempo establecida (Años, Meses o Días)
		$unidades = [ "Año", "Mes", "Día" ];
		$plural = 	$this->vigencia > 1 ? 's' : '';
		return $this->vigencia.' '.$unidades [$this->unidad].$plural; 
	}
}

class CursoInterno extends Curso {
	
	private $id_int;
	private $id_curso;
	private $contenido;
	private $banco_preg;
	private $departamento;
	private $comentarios;
	private $version_actual;
	private $fecha;
	private $borrador;
	private $creador;
	private $puntaje_min;
	private $puntaje_total;
	
	public function setIdInt ($id_int) 			{ $this->id_int = $id_int; }
	public function setIdCurso ($id_curso) 		{ $this->id_curso = $id_curso; }
	public function setContenido ($contenido) 	{ $this->contenido = json_decode($contenido); }
	public function setBancoPreg ($banco) 		{ $this->banco_preg = $banco; }
	public function setComentarios ($coment)	{ $this->comentarios = $coment; }
	public function setDepartamento ($dept)		{ $this->departamento = $dept; }
	public function setVersionActual ($version) { $this->version_actual = $version; }
	public function setFecha ($fecha) 			{ $this->fecha = $fecha; }
	public function setBorrador ($borrador)		{ $this->borrador = $borrador; }
	public function setCreador ($creador)		{ $this->creador = $creador; }
	public function getIdInt ()		 			{ return $this->id_int; }
	public function getIdCurso ()	 			{ return $this->id_curso; }
	public function getContenido ()  			{ return $this->contenido; }
	public function getBancoPreg ()  			{ return $this->banco_preg; }
	public function getDepartamento ()			{ return $this->departamento; }
	public function getComentarios ()			{ return $this->comentarios; }
	public function getBorrador ()				{ return $this->borrador; }
	public function getCreador ()				{ return $this->creador; }
	public function getPuntajeMin ()			{ return $this->puntaje_min; }
	public function getPuntajeTotal ()			{ return $this->puntaje_total; }
	public function getNuevaEvaluacion () {
		
		// Obtener banco de preguntas
		$banco_preg = 	 json_decode ($this->banco_preg);
		$max_preguntas = $banco_preg->max_preguntas;
		$num_preg = 	 count($banco_preg->banco);
		$max_puntaje =	 0;
		
		$num_aleatorios = [];
		$preg_aleatorias = [];
		
		while (count($num_aleatorios) < $max_preguntas && 
			   count($num_aleatorios) < $num_preg)
		{
			// Generar indices aleatorios para obtener preguntas del banco   
			$aleatorio = rand(0, $num_preg - 1);
			if (!in_array ($aleatorio, $num_aleatorios)) {
				
				// Evitar que se repita la misma pregunta
				$num_aleatorios[] = $aleatorio;
				$preg_aleatorias[] = $banco_preg->banco[$aleatorio];
				$max_puntaje += $banco_preg->banco[$aleatorio]->puntaje;
			}
	    }
		
		// Calcular puntaje mínimo del curso
		$this->puntaje_min = 	$max_puntaje * ($banco_preg->calificacion_min / 100);
		$this->puntaje_total = 	$max_puntaje;
		
		echo '
		<div class="alert alert-primary d-flex align-items-center px-4" role="alert">
			<svg class="me-3 mb-auto mt-2" style="float: left" fill="currentColor" viewBox="0 0 16 16" width="40">
				<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
			</svg>
			<div>
				<p class="text-break m-0">El <b>puntaje mínimo para aprobar este curso es de '.$this->puntaje_min.' puntos</b>. Revisa bien tus respuestas antes de terminar la evaluación.</p>
			</div>
		</div>
		
		<div style="animation: fadeIn .5s;" id="carouselExampleControls" class="carousel slide" data-ride="carousel">
		  <div class="carousel-inner">
			<div id="contenido-preguntas"></div>
		  </div>
		</div>		
		';
		
		for ($i = 0; $i < count($preg_aleatorias); $i++) {
			// Generar formato de evaluación
			echo '
			<div data-componente="pregunta-evaluacion" data-id_preg="'.$i.'">
				<div 
				data-tipo="pregunta">'.
					$preg_aleatorias [$i]->pregunta.'<small> ('.$preg_aleatorias[$i]->puntaje.' puntos)</small>
				</div>
				<ul data-tipo="opciones">';
				for ($j = 0; $j < count ($preg_aleatorias[$i]->opciones); $j++) {
					echo '
					<div id="op_'.$i.'" data-componente="opcion-evaluacion">'.
						$preg_aleatorias[$i]->opciones[$j]->texto.
					'</div>
					';
				}
			echo '
				</ul>
			</div>
			';
		} return $preg_aleatorias;
	}
	public function getVersionActual () { 
		return isset($this->version_actual) ? 
			   $this->version_actual : 
			   'N/A'; 
	}
	public function getFecha () {
		
		// Retornar fecha formateada
		$fecha = date_create ($this->fecha);
		return date_format($fecha, "d/m/Y");
	}
	
	public function VerCurso ($tipo_usuario, $es_secuencial = false) {
		
		$historial_normal = (($tipo_usuario == 'admin' || 
						  	 $tipo_usuario == 'instructor') &&
							 $this->getVersionActual() != 'N/A') ? 'hidden' : '';
		$historial_versiones = (($tipo_usuario == 'admin' ||
							 $tipo_usuario == 'instructor') &&
							 $this->getVersionActual () != 'N/A') ? '' : 'hidden';

		// El contenido del curso está vacío
		if(!isset($this->contenido)) {
			echo '<h1>No se encontró el curso</h1>';
			return;
		};

		// Obtener objetivo del curso, considerando que algunos cursos podrían no tenerlo debido a su posterior implementación
		$objetivo = $this->getObjetivo () == '' ? 
					'El objetivo de este curso no ha sido especificado.' : 
					$this->getObjetivo ();

		// Obtener portada del curso
		$portada_curso =  $this->getPortada () != '' ? 
						 '<img src="uploads/portadas/'.$this->getPortada ().'">': 
						 '<svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="100"><path fill="#FFF" d="M160 96a96 96 0 1 1 192 0A96 96 0 1 1 160 96zm80 152l0 264-48.4-24.2c-20.9-10.4-43.5-17-66.8-19.3l-96-9.6C12.5 457.2 0 443.5 0 427L0 224c0-17.7 14.3-32 32-32l30.3 0c63.6 0 125.6 19.6 177.7 56zm32 264l0-264c52.1-36.4 114.1-56 177.7-56l30.3 0c17.7 0 32 14.3 32 32l0 203c0 16.4-12.5 30.2-28.8 31.8l-96 9.6c-23.2 2.3-45.9 8.9-66.8 19.3L272 512z"/></svg>';
		
		// Mostrar bandera de Curso Modular en caso de que el curso sea secuencial
		$secuencial_flag = $es_secuencial ?
		'<span class="secuencial-flag" title="Este curso se conforma de dos o más partes">Curso Modular</span>' : '';

		// El contenido del curso no está vacío
		$body = '
		<div class="d-flex flex-row w-100">
			<div class="d-flex flex-column" style="width: 70%">
				<h5 class="resaltar">Descripción</h5>
				<p class="flex-grow-1 text-break">'.$this->getDescripcion ().'</p>
				<hr class="mt-2">
				<h5 class="resaltar-secundario mt-2">Objetivo</h5>
				<p>'.$objetivo.'</p>
				<div class="curso-info my-3">
					<h6>Creado por:</h6>
					<p>'.$this->getCreador().'</p>
				</div>
				<div class="d-flex flex-row">
					<div class="curso-info flex-grow-1 me-3">
						<h6>Departamento:</h6>
						<p>'.$this->getDepartamento ().'</p>
					</div>
					<div class="curso-info flex-grow-1">
						<h6>Última modificación:</h6>
						<p>'.$this->getFecha ().'</p>
					</div>
				</div>
			</div>
			<div class="flex-fill mx-3 d-flex flex-column">
				<div class="contenedor-portada px-5 py-5 d-flex flex-fill" style="background-color: #AAA; position: relative;">
					'.$portada_curso.'
					'.$secuencial_flag.'
				</div>
				<div class="curso-info mt-3" '.$historial_normal.'>
					<h6>Versión actual:</h6>
					<p>'.$this->getVersionActual ().'</p>
				</div>
				
				<div class="accordion mt-3" id="ver-historial" '.$historial_versiones.'>
					<div class="accordion-item">
						<h2 class="accordion-header">
						<button class="accordion-button collapsed d-flex flex-column" title="Abrir opciones de versión" type="button" data-bs-toggle="collapse" data-bs-target="#opciones-version" aria-expanded="false" aria-controls="collapseOne">
							<h6 class="ps-3 pt-3 me-auto">Versión actual:</h6>
							<p class="ps-3 pb-3 me-auto">'.$this->getVersionActual ().'</p>
						</button>
						</h2>
						<div id="opciones-version" class="accordion-collapse collapse" data-bs-parent="#ver-historial" style="background-color: var(--entidad-primario); border-radius: 0;">
						<div class="accordion-body p-0">
							<button class="btn-opciones btn btn-svg p-3" onclick="AbrirHistorial ()">
								<svg class="me-2 my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#FFF" d="M75 75L41 41C25.9 25.9 0 36.6 0 57.9L0 168c0 13.3 10.7 24 24 24l110.1 0c21.4 0 32.1-25.9 17-41l-30.8-30.8C155 85.5 203 64 256 64c106 0 192 86 192 192s-86 192-192 192c-40.8 0-78.6-12.7-109.7-34.4c-14.5-10.1-34.4-6.6-44.6 7.9s-6.6 34.4 7.9 44.6C151.2 495 201.7 512 256 512c141.4 0 256-114.6 256-256S397.4 0 256 0C185.3 0 121.3 28.7 75 75zm181 53c-13.3 0-24 10.7-24 24l0 104c0 6.4 2.5 12.5 7 17l72 72c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-65-65 0-94.1c0-13.3-10.7-24-24-24z"></path></svg>
								<span class="text-truncate">Historial de versiones<span>
							<button>
						</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<hr>
		<div>
			'.$this->getContenido().'
		</div>
		'; echo $body;
	}
}

?>