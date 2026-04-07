<?php  
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/util/curso.php');
require_once ('modulos/util/cookiescursos.php');
require_once ('modulos/CursosInternos.php'); 

ValidarSesion ();
ValidarSesionInstAdmin ($sesion->getTipoUsuario ());

// Determinar si se está editando un curso ya existente
$tiene_version_prev = 	isset ($c_curso_interno) ? 
						HayVersionAnterior ($c_curso_interno->getIdCurso()) : 
						'false';

// Determinar si tiene un curso dependiente (en caso de ser modular)
$curso_dependiente = isset ($c_curso_interno) ? 
					 ObtenerCursoDependiente ($c_curso_interno->getIdCurso()) : 
					 'null';

// Obtener contenido JSON enviado a través de petición POST
$curso_json = json_decode (file_get_contents ('php://input'));

// Se recibió un curso como JSON, para registrar en la BD
if ($curso_json != null) {
	$estatus = GuardarBorrador (
		$curso_json, 
		$c_curso_interno, 
		$sesion
	); 
	echo json_encode (["respuesta" => $estatus]);
	exit; 
}

function GuardarBorrador ($curso_json, $c_curso_interno, $sesion) {
	$id_int = 		  null;
	$id_curso = 	  null;
	$version_actual = null;
	$respuesta = 	  null;
	$estatus =		  null;
	$num_nomina = 	  $sesion->getNumNomina();

	if (!isset ($c_curso_interno) || 
		(isset ($c_curso_interno) &&
		$c_curso_interno->getIdInt () == null &&
		$c_curso_interno->getIdCurso () == null)) 
	{
		// Guardar curso como borrador en BD (nuevo)
		$respuesta = GuardarBorradorBD (
			$num_nomina, $curso_json
		); $estatus = 'nuevo';
	}
	else {
		$id_int =	$c_curso_interno->getIdInt ();
		$id_curso = $c_curso_interno->getIdCurso ();
		
		// Guardar curso como borrador en BD (existente)
		$respuesta = GuardarBorradorBD (
			$num_nomina, $curso_json, $id_int, $id_curso
		); $estatus = 'actualizado';
	}
	$id_int = 		  $respuesta ["id_int"];
	$id_curso = 	  $respuesta ["id_curso"];
	$version_actual = $respuesta ["version_actual"];
	
	// Guardar temporalmente contenido de borrador en cookies
	$c_curso_interno = GuardarBorradorCookies (
		$curso_json, true, $id_int, $id_curso, $version_actual
	);
	return [
		'estatus' => $estatus,
		'portada_actual' => $respuesta ['portada_actual'],
		'portada_anterior' => $respuesta ['portada_anterior']
	];
}

if ( // Eliminación lógica de curso en la BD
	isset ($_POST ['eliminar']) &&
	$_POST ['eliminar'] == 'curso' && 
	isset ($_POST ['id_curso'])
) {
	$id_curso =  		$_POST ['id_curso'];
	$resultado = 		EliminarCursoInterno ($id_curso);
	LimpiarCookieCurso ();
	
	echo $resultado; exit;
}

// Crear nuevo curso
if (isset ($_POST ['nuevo'])) {
	LimpiarCookieCurso ();
	echo json_encode ([
		'respuesta' => 'correcto'
	]); exit;
}

// Descartar edición (o borrador) de curso
if (isset ($_POST ['descartar'])) {
	$id_int = 	null;
	$id_curso = null;
	
	if($_POST ['descartar'] == "editar-curso" && 
		$c_curso_interno != null) {
		$id_int = 	$c_curso_interno->getIdInt ();
		$id_curso = $c_curso_interno->getIdCurso ();
	}
	else if (isset ($_POST ['id_int'])) {
		$id_int = 	$_POST ['id_int'];
		$id_curso = $_POST ['descartar'];
	}
	
	DescartarBorradorBD ($id_int, $id_curso);
	LimpiarCookieCurso ();
}

if (	// Publicar curso
	isset ($_POST ['publicar']) && 
	$_POST ['publicar'] == "publicar-curso" &&
	$c_curso_interno != null
) {
	$id_int = 			$c_curso_interno->getIdInt ();
	$id_curso = 		$c_curso_interno->getIdCurso ();
	$version_actual =	$c_curso_interno->getVersionActual ();

	$respuesta = PublicarCursoInterno (
		$id_int, 
		$id_curso, 
		$version_actual
	);
	
	LimpiarCookieCurso ();
	echo json_encode (["respuesta" => $respuesta ['version']]);
	exit;
}
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8">
		<title>Editor de cursos</title>
		<link href="css/bootstrap@5.3.3/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/estilos.css" rel="stylesheet"/>
		<link href="css/font-awesome.min.css" rel="stylesheet"/>
		<script src="js/bootstrap@5.3.3/bootstrap.bundle.min.js"></script>
	</head>
	<body style="min-height: 100vh">
		
		<div data-componente="mod-agregar-comp"></div>
		
		<main class="d-flex flex-row">
		
		<aside class="d-flex" style="animation: barra-lateral 1.5s; background-color: #555; width: max(5vw, 80px); position: fixed; left: 0; top: 0; bottom: 0;">
			<div class="opciones-editor w-100">
				<button title="Publicar curso" class="w-100 align-items-center" onclick="PublicarCurso(this, '<?php echo $sesion->getDepartamento () ?>')">
					<svg class="mx-auto h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="25"><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L369 209z"/></svg>
					<br><small style="font-size: max(.7vw, 10px);">Publicar</small>
				</button>
				<hr class="my-1">	
				<button title="Nuevo curso" class="w-100 align-items-center" onclick="NuevoCurso()">
					<svg class="mx-auto my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>
					<br><small style="font-size: max(.7vw, 10px);">Nuevo</small>
				</button>
				<hr class="my-1">
				<button title="Descartar edición" class="w-100 align-items-center" 
					onclick="DescartarEdicion(<?php echo !isset ($c_curso_interno) ? "null, null" :
					$c_curso_interno->getIdCurso().",".$c_curso_interno->getIdInt()?>)" 
					<?php if(!isset ($c_curso_interno)) echo "disabled"?>
				>
					<svg class="mx-auto my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20"><path d="M290.7 57.4L57.4 290.7c-25 25-25 65.5 0 90.5l80 80c12 12 28.3 18.7 45.3 18.7L288 480l9.4 0L512 480c17.7 0 32-14.3 32-32s-14.3-32-32-32l-124.1 0L518.6 285.3c25-25 25-65.5 0-90.5L381.3 57.4c-25-25-65.5-25-90.5 0zM297.4 416l-9.4 0-105.4 0-80-80L227.3 211.3 364.7 348.7 297.4 416z"/></svg>
					<br><small style="font-size: max(.7vw, 10px);">Descartar</small>
				</button>
				<hr class="my-1">
				<button title="Previsualizar curso" class="w-100 align-items-center" onclick="PrevisualizarCurso ('<?php echo $sesion->getDepartamento () ?>')">
					<svg class="mx-auto my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>
					<br><small style="font-size: max(.7vw, 10px);">Previsualizar</small>
				</button>
				<hr class="my-1">
				<button title="Guardar como borrador" class="w-100 align-items-center" onclick="GuardarBorrador(this, '<?php echo $sesion->getDepartamento () ?>')">
					<svg class="mx-auto h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path d="M48 96l0 320c0 8.8 7.2 16 16 16l320 0c8.8 0 16-7.2 16-16l0-245.5c0-4.2-1.7-8.3-4.7-11.3l33.9-33.9c12 12 18.7 28.3 18.7 45.3L448 416c0 35.3-28.7 64-64 64L64 480c-35.3 0-64-28.7-64-64L0 96C0 60.7 28.7 32 64 32l245.5 0c17 0 33.3 6.7 45.3 18.7l74.5 74.5-33.9 33.9L320.8 84.7c-.3-.3-.5-.5-.8-.8L320 184c0 13.3-10.7 24-24 24l-192 0c-13.3 0-24-10.7-24-24L80 80 64 80c-8.8 0-16 7.2-16 16zm80-16l0 80 144 0 0-80L128 80zm32 240a64 64 0 1 1 128 0 64 64 0 1 1 -128 0z"/></svg>
					<br><small style="font-size: max(.7vw, 10px);">Guardar</small>
				</button>
			</div>
		</aside>
		
		<article class="my-auto pt-3" style="margin-left: 80px; height: 100% !important;">
			
			<div 
				data-componente = "navbar-sesion-<?php echo $sesion->getTipoUsuario()?>"
				style="margin-left: max(5vw, 50px);">
			</div>
			<button class="scroll-elemento-top d-flex" onclick="ScrollTop ()">
				<svg class="m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40"><path fill="#fff" d="M233.4 105.4c12.5-12.5 32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L256 173.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l192-192z"/></svg>
			</button>
			
			<div id="contenido-curso" data-pos="elemento-top">				
				<div data-componente="comp-titulo-principal" data-valor="<?php NombreCurso()?>"></div>
				
				<div class="editor shadow">
					<div class="toolbar-texto px-5 py-2">
						<div class="opciones">
							
							<div class="w-100">
								<fieldset class="border pb-2 rounded-3 mt-2 d-flex flex-column">
									<legend class="h6 text-center">Agregar componente:</legend>
									<div class="opciones-agregar d-flex flex-wrap justify-content-center w-100 px-2">
										<button class="px-lg-3 px-sm-2" onclick="AgregarElementoEditor('titulo')" title="Agregar nuevo título al contenido">
											<div class="d-flex flex-column">
												<h3 class="m-0 p-0">T</h3>
												<small>Título</small>
											</div>
										</button>
										<button class="d-flex align-items-end px-lg-3 px-sm-2" onclick="AgregarElementoEditor('subtitulo')" title="Agregar nuevo subtítulo al contenido">
											<div class="d-flex flex-column">
												<h5 class="m-0 p-0">Aa</h5>
												<small>Subtítulo</small>
											</div>
										</button>
										<button class="d-flex align-items-end px-lg-3 px-sm-2" onclick="AgregarElementoEditor ('imagen')" title="Agregar imagen al contenido">
											<div class="d-flex flex-column">
												<svg class="mx-auto pt-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#2b2b2b" d="M448 80c8.8 0 16 7.2 16 16l0 319.8-5-6.5-136-176c-4.5-5.9-11.6-9.3-19-9.3s-14.4 3.4-19 9.3L202 340.7l-30.5-42.7C167 291.7 159.8 288 152 288s-15 3.7-19.5 10.1l-80 112L48 416.3l0-.3L48 96c0-8.8 7.2-16 16-16l384 0zM64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zm80 192a48 48 0 1 0 0-96 48 48 0 1 0 0 96z"/></svg>
												<small>Imagen</small>
											</div>
										</button>
										<button class="d-flex align-items-end px-lg-3 px-sm-2" onclick="AgregarElementoEditor ('video')" title="Agregar video al contenido">
											<div class="d-flex flex-column">
												<svg class="mx-auto pt-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#2b2b2b" d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM188.3 147.1c-7.6 4.2-12.3 12.3-12.3 20.9l0 176c0 8.7 4.7 16.7 12.3 20.9s16.8 4.1 24.3-.5l144-88c7.1-4.4 11.5-12.1 11.5-20.5s-4.4-16.1-11.5-20.5l-144-88c-7.4-4.5-16.7-4.7-24.3-.5z"/></svg>
												<small>Video</small>
											</div>
										</button>
									</div>
								</fieldset>
							</div>
							<fieldset class="border rounded-3 w-100 ms-3 mt-3 d-flex">
								<div class="d-flex flex-wrap w-100 justify-content-center" title="Negritas">
									<button class="btn-formato" onclick="formatDoc ('bold')">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="25"><path fill="#000" d="M0 64C0 46.3 14.3 32 32 32l48 0 16 0 128 0c70.7 0 128 57.3 128 128c0 31.3-11.3 60.1-30 82.3c37.1 22.4 62 63.1 62 109.7c0 70.7-57.3 128-128 128L96 480l-16 0-48 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l16 0 0-160L48 96 32 96C14.3 96 0 81.7 0 64zM224 224c35.3 0 64-28.7 64-64s-28.7-64-64-64L112 96l0 128 112 0zM112 288l0 128 144 0c35.3 0 64-28.7 64-64s-28.7-64-64-64l-32 0-112 0z"/></svg>
									</button>
									<button class="btn-formato" onclick="formatDoc ('italic')" title="Italic">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="25"><path fill="#000" d="M128 64c0-17.7 14.3-32 32-32l192 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-58.7 0L160 416l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 480c-17.7 0-32-14.3-32-32s14.3-32 32-32l58.7 0L224 96l-64 0c-17.7 0-32-14.3-32-32z"/></svg>
									</button>
									<button class="btn-formato" onclick="formatDoc ('justifyLeft')" title="Alinear a la izquierda">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="25"><path fill="#000" d="M288 64c0 17.7-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l224 0c17.7 0 32 14.3 32 32zm0 256c0 17.7-14.3 32-32 32L32 352c-17.7 0-32-14.3-32-32s14.3-32 32-32l224 0c17.7 0 32 14.3 32 32zM0 192c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 224c-17.7 0-32-14.3-32-32zM448 448c0 17.7-14.3 32-32 32L32 480c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"/></svg>
									</button>
									<button class="btn-formato" onclick="formatDoc ('justifyCenter')" title="Centrar">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="25"><path fill="#000" d="M352 64c0-17.7-14.3-32-32-32L128 32c-17.7 0-32 14.3-32 32s14.3 32 32 32l192 0c17.7 0 32-14.3 32-32zm96 128c0-17.7-14.3-32-32-32L32 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l384 0c17.7 0 32-14.3 32-32zM0 448c0 17.7 14.3 32 32 32l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L32 416c-17.7 0-32 14.3-32 32zM352 320c0-17.7-14.3-32-32-32l-192 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l192 0c17.7 0 32-14.3 32-32z"/></svg>
									</button>
									<button class="btn-formato" onclick="formatDoc ('justifyRight')" title="Alinear a la derecha">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="25"><path fill="#000" d="M448 64c0 17.7-14.3 32-32 32L192 96c-17.7 0-32-14.3-32-32s14.3-32 32-32l224 0c17.7 0 32 14.3 32 32zm0 256c0 17.7-14.3 32-32 32l-224 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l224 0c17.7 0 32 14.3 32 32zM0 192c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 224c-17.7 0-32-14.3-32-32zM448 448c0 17.7-14.3 32-32 32L32 480c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"/></svg>
									</button>
									<button class="btn-formato" onclick="formatDoc ('insertOrderedList')" title="Lista enumerada">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="25"><path fill="#000" d="M24 56c0-13.3 10.7-24 24-24l32 0c13.3 0 24 10.7 24 24l0 120 16 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-80 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l16 0 0-96-8 0C34.7 80 24 69.3 24 56zM86.7 341.2c-6.5-7.4-18.3-6.9-24 1.2L51.5 357.9c-7.7 10.8-22.7 13.3-33.5 5.6s-13.3-22.7-5.6-33.5l11.1-15.6c23.7-33.2 72.3-35.6 99.2-4.9c21.3 24.4 20.8 60.9-1.1 84.7L86.8 432l33.2 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-88 0c-9.5 0-18.2-5.6-22-14.4s-2.1-18.9 4.3-25.9l72-78c5.3-5.8 5.4-14.6 .3-20.5zM224 64l256 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-256 0c-17.7 0-32-14.3-32-32s14.3-32 32-32zm0 160l256 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-256 0c-17.7 0-32-14.3-32-32s14.3-32 32-32zm0 160l256 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-256 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z"/></svg>
									</button>
									<button class="btn-formato" onclick="formatDoc ('insertUnorderedList')" title="Enlistar selección">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="25"><path fill="#000" d="M64 144a48 48 0 1 0 0-96 48 48 0 1 0 0 96zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L192 64zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-288 0zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-288 0zM64 464a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm48-208a48 48 0 1 0 -96 0 48 48 0 1 0 96 0z"/></svg>
									</button>
									<button class="btn-formato" title="Remover sangría" onclick="formatDoc ('outdent')">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 550 550" width="25"><path fill="#000000" d="M96.4 128C96.4 110.3 110.7 96 128.4 96L512.4 96C530.1 96 544.4 110.3 544.4 128C544.4 145.7 530.1 160 512.4 160L128.4 160C110.8 160 96.4 145.7 96.4 128zM288.4 256C288.4 238.3 302.7 224 320.4 224L512.4 224C530.1 224 544.4 238.3 544.4 256C544.4 273.7 530.1 288 512.4 288L320.4 288C302.7 288 288.4 273.7 288.4 256zM320.4 352L512.4 352C530.1 352 544.4 366.3 544.4 384C544.4 401.7 530.1 416 512.4 416L320.4 416C302.7 416 288.4 401.7 288.4 384C288.4 366.3 302.7 352 320.4 352zM96.4 512C96.4 494.3 110.7 480 128.4 480L512.4 480C530.1 480 544.4 494.3 544.4 512C544.4 529.7 530.1 544 512.4 544L128.4 544C110.7 544 96.4 529.7 96.4 512zM96.7 332.6C88.5 326.2 88.5 313.7 96.7 307.3L198.6 228C209.1 219.8 224.4 227.3 224.4 240.6L224.4 399.2C224.4 412.5 209.1 420 198.6 411.8L96.7 332.6z"/></svg>
									</button>
									<button class="btn-formato" title="Agregar sangría" onclick="formatDoc ('indent')">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 550 550" width="25"><path fill="#000" d="M96 128C96 110.3 110.3 96 128 96L512 96C529.7 96 544 110.3 544 128C544 145.7 529.7 160 512 160L128 160C110.3 160 96 145.7 96 128zM288 256C288 238.3 302.3 224 320 224L512 224C529.7 224 544 238.3 544 256C544 273.7 529.7 288 512 288L320 288C302.3 288 288 273.7 288 256zM320 352L512 352C529.7 352 544 366.3 544 384C544 401.7 529.7 416 512 416L320 416C302.3 416 288 401.7 288 384C288 366.3 302.3 352 320 352zM96 512C96 494.3 110.3 480 128 480L512 480C529.7 480 544 494.3 544 512C544 529.7 529.7 544 512 544L128 544C110.3 544 96 529.7 96 512zM223.8 332.6L121.8 411.9C111.3 420.1 96 412.6 96 399.3L96 240.7C96 227.4 111.3 219.9 121.8 228.1L223.7 307.4C231.9 313.8 231.9 326.3 223.7 332.7z"/></svg>
									</button>
									<button class="btn-formato" onclick="formatDoc ('removeFormat')" title="Remover estilo de la selección">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="25"><path fill="#000" d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L355.7 253.5 400.2 96 503 96 497 120.2c-4.3 17.1 6.1 34.5 23.3 38.8s34.5-6.1 38.8-23.3l11-44.1C577.6 61.3 554.7 32 523.5 32L376.1 32l-.3 0L204.5 32c-22 0-41.2 15-46.6 36.4l-6.3 25.2L38.8 5.1zm168 131.7c.1-.3 .2-.7 .3-1L217 96l116.7 0L301.3 210.8l-94.5-74.1zM243.3 416L192 416c-17.7 0-32 14.3-32 32s14.3 32 32 32l160 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-42.2 0 17.6-62.1L272.9 311 243.3 416z"/></svg>
									</button>
									<button class="btn-formato" onclick="formatDoc ('nota')" title="Convertir selección en nota">
										<svg xmlns="http://www.w3.org/2000/svg"  width="25" viewBox="0 0 1871 1871" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,1871.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M4787 17305 c-668 -81 -1262 -451 -1637 -1020 -78 -119 -192 -344 -235 -465 -37 -106 -91 -317 -112 -440 -17 -99 -18 -302 -20 -3567 l-3 -3463 855 0 855 0 -5 968 c-6 1203 -7 1124 3 3707 l7 2140 26 67 c71 183 219 316 404 363 53 13 695 15 5817 15 4138 0 5772 -3 5810 -11 204 -42 395 -233 437 -437 8 -38 11 -1193 11 -4067 l0 -4015 -1507 -3 c-1361 -3 -1514 -5 -1573 -20 -460 -116 -761 -417 -877 -877 -15 -59 -17 -212 -20 -1572 l-3 -1508 -1513 0 -1512 0 -855 -855 -855 -855 2530 0 c2173 0 2548 2 2657 15 415 48 787 198 1136 456 137 102 3524 3488 3630 3629 257 345 408 720 457 1136 13 113 15 644 15 4330 0 2891 -3 4235 -11 4310 -40 410 -199 817 -449 1149 -373 493 -940 818 -1557 890 -112 13 -843 15 -5963 14 -4746 -1 -5855 -4 -5943 -14z"/><path d="M6175 11140 c-309 -66 -543 -292 -615 -594 -24 -100 -26 -247 -4 -354 17 -83 72 -210 124 -289 18 -26 631 -644 1361 -1373 731 -729 1329 -1329 1329 -1333 0 -4 -1729 -7 -3842 -7 -3828 0 -3843 0 -3928 -20 -264 -64 -473 -256 -563 -518 -39 -114 -49 -297 -23 -416 35 -153 107 -287 214 -394 97 -97 218 -168 357 -209 57 -17 251 -18 3925 -21 l3865 -2 -1326 -1328 c-729 -730 -1342 -1350 -1362 -1378 -47 -65 -102 -182 -122 -259 -9 -33 -19 -107 -22 -165 -15 -278 108 -528 340 -691 256 -180 611 -185 880 -14 58 38 460 435 2098 2073 1311 1311 2043 2051 2073 2094 152 216 187 489 96 740 -61 166 29 72 -2133 2236 -1107 1107 -2039 2035 -2072 2061 -80 64 -153 104 -250 137 -68 23 -100 27 -220 30 -77 2 -158 -1 -180 -6z"/></g></svg>
									</button>
								</div>
							</fieldset>

						</div>
					</div>
					
					<hr class="mb-0">
					
					<div 
					class="contenido-editor px-5 py-4" 
					contenteditable="true" 
					spellcheck="false" 
					data-placeholder="Escribe el contenido del curso..."><?php ContenidoCurso ()?></div>
				</div>
			</div>
			
			<div
			data-titulo="Configuración del curso:"
			data-componente="bloque"
			>
				<div data-tipo="contenido">
					<div data-componente="info-version-curso"></div>
				</div>
			</div>
			
			<button class="scroll-elemento-bottom d-flex" onclick="ScrollElementoBottom ()">
				<svg class="m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40"><path fill="#fff" d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>
			</button>
				
			<div 
			data-componente="ventana-extendida"
			data-titulo="Evaluación - Creación de banco de preguntas"
			data-color="light-red"
			data-spacing="top"
			data-pos="elemento-bottom"
			>				
				<div data-tipo="contenido">
					<?php BancoPreguntas ()?>
				</div>
			</div>
		</article>
		</main>
	</body>
	<script>
		var no_nomina = 		 	'2572';
		var id_int = 			 	<?php echo isset ($c_curso_interno) ? $c_curso_interno->getIdInt () : -1 ?>;
		var id_curso = 			 	<?php echo isset ($c_curso_interno) ? $c_curso_interno->getIdCurso () : -1 ?>;
		var portada = 			 	'<?php echo isset ($c_curso_interno) ? $c_curso_interno->getPortada () : '' ?>';
		var tiene_version_prev = 	<?php echo $tiene_version_prev ?>;
		var Id_curso_dependiente = 	<?php echo  isset ($curso_dependiente ['id']) ? $curso_dependiente ['id'] : 'null' ?>;
		var Curso_dependiente = 	'<?php echo isset ($curso_dependiente ['curso']) ? $curso_dependiente ['curso'] : 'null' ?>';
		
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/validacion_in.js"></script>
	<script src="js/gestion_curso_interno.js"></script>
	<script src="js/banco_preg.js"></script>
	<script src="js/plantillas.js"></script>
	<script src="js/editor.js"></script>
	<script src="js/indicador_flotante.js"></script>
	<script src="js/email.js"></script>
	<script src="js/tooltip.js"></script>
	<script src="js/scroll.js"></script>
	<script src="js/idlein.js"></script>
	<script>
		const $toolbar = 		  	document.querySelector ('.toolbar-texto');
		const $contenedor_curso = 	document.querySelector ('.contenido-editor');
		const $contenedor_editor = 	document.querySelector ('.editor');
		
		let sticky_creado = false;
		
		window.onscroll = (e) => {
			if ($barra_nav && !sticky_creado) {
				crearElementoStickyTop (
					$toolbar, 
					$contenedor_editor, 
					$contenedor_curso, 
					$barra_nav
				);
				sticky_creado = true;
			}
		};
	</script>
	<script>
		function DesplegarConfiguracionModular (selector) {
			// Sección para la configuración de curso modular
			const collapse_seccion = new bootstrap.Collapse (
				document.querySelector ('#conf-curso-modular'), { toggle: false }
			);

			// Gestionar visibilidad
			if (selector.value == 1) collapse_seccion.show (); 
			else collapse_seccion.hide ();
		}
	</script>
	
</html>