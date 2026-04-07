<?php 
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/CursosInternos.php');
ValidarSesion (); 

if ($sesion->getTipoUsuario() != 'empleado') {
	// Obtener cursos creados por el usuario
	$registro = 	  ObtenerCursosCreados ($sesion->getNumNomina());
	$cursos_creados = $registro ['lista_cc'];
}

// Obtener cursos pendientes
$cursos_pendientes = ObtenerCursosPendientes ($sesion->getNumNomina()) ['lista_cp'];

// Obtener cursos aprobados
$cursos_aprobados = ObtenerCursosAprobados ($sesion->getNumNomina()) ['lista_ca'];

// Obtener catálogo de cursos
$cursos_disponibles = ObtenerCIDisponibles (
	$sesion->getNumNomina(), 1, 4
) ['lista_ci'];

// Determinar si exite al menos una calificación colaborativa en los cursos aprobados
$hay_colaborativa = false;
foreach ($cursos_aprobados as $curso_aprobado) {
	if ($curso_aprobado ['Colaborativo']) {
		$hay_colaborativa = true;
		break;
	}
}

// Obtener estadísticas de cursos
$estadisticas = ObtenerEstadisticas ($sesion->getNumNomina ());
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8"/>
		<title>Inicio</title>
		<link href="css/bootstrap@5.3.3/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/estilos.css" rel="stylesheet"/>
		<link href="css/font-awesome.min.css" rel="stylesheet"/>
		<script src="js/bootstrap@5.3.3/bootstrap.bundle.min.js"></script>
	</head>
	<body>
		<header>
			<div data-componente = "navbar-sesion-<?php echo $sesion->getTipoUsuario()?>"></div>
		</header>
		
		<article class="contenido-wrap">

			<div class="ventana-flotante aviso w-75" <?php echo (UsaTmpPass ($sesion->getNumNomina ()) && $sesion->getTipoUsuario() != 'empleado') ? '' : 'hidden' ?>>
				<div class="msg-flotante">
					<div class="alert alert-primary d-flex" role="alert">
						<svg class="me-3 col-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30"><path fill="#2D5992" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336l24 0 0-64-24 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l48 0c13.3 0 24 10.7 24 24l0 88 8 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-80 0c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
						<p class="my-auto">Se detectó el inicio de sesión con una contraseña temporal. Por tu seguridad, es recomendable que cambies tu contraseña desde la sección de "Cuenta" y "Cambiar contraseña", o dando clic <a style="color: #2D5992" href="cambiar_password.php"><b>aquí</b></a>.</p>
						<button class="btn-cerrar btn-svg ms-2" onclick="RemoverAviso (this)">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="#2D5992" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
						</button>
					</div>
				</div>
			</div>
		
			<button class="scroll-top d-flex" onclick="ScrollTop ()">
				<svg class="m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40"><path fill="#fff" d="M233.4 105.4c12.5-12.5 32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L256 173.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l192-192z"/></svg>
			</button>
			<button class="scroll-bottom d-flex" onclick="ScrollBottom ()">
				<svg class="m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40"><path fill="#fff" d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>
			</button>
			
			<div class="d-flex flex-column mb-auto comp-primario">
				<h2 
				class="d-block d-xl-none w-100 text-center my-5 text-muted fw-bold" 
				style="color: #CCC !important; animation: nuevaFilaTablaTop .5s;">
					Aquí está tú <b>actividad</b> más <b>reciente</b>:
				</h2>
				<div
					data-componente="ventana"
					data-titulo="Últimos cursos creados"
					data-color="gray"
					class="flex-fill mt-2 mb-0"
					<?php if ($sesion->getTipoUsuario() === 'empleado') echo 'hidden'; ?>
				>
					<div 
					data-componente="busqueda-curso"
					data-pagina="cursos_creados.php"
					data-empleado="<?php echo $sesion->getNumNomina () ?>"></div>
					<div data-tipo="contenido">
						<?php
							if (isset($cursos_creados) && $cursos_creados != null) {
								$lista_cursos_creados = '';
								
								foreach ($cursos_creados as $curso) {
									// Agregar cursos creados por el usuario
									$lista_cursos_creados .= '
										<div 
										data-componente="elemento-lista-CC"
										data-nombre="'.htmlspecialchars ($curso->getNombre ()).'"
										data-descripcion="'.htmlspecialchars ($curso->getDescripcion ()).'"
										data-fecha="'.$curso->getFecha ().'"
										data-id_curso="'.$curso->getIdCurso ().'"
										data-portada="'.$curso->getPortada ().'"
									></div>
									';
								}
								echo 
								'<div data-componente="lista">'.
									$lista_cursos_creados.
								'</div>';
							} 
							else {
								echo '<div class="d-flex flex-column align-items-center p-4">
									<svg class="w-6 h-6 text-gray-800 dark:text-white mb-3" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="80" viewBox="0 0 24 24">
									  <path stroke="#CCC" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
									</svg>
									<h4 class="text-muted"><b>No tienes cursos creados todavía</b></h4>
									<h6 class="text-muted">Una vez que crees algún curso, aparecerá en esta sección</h6>
								 </div>';
							}
							
						?>
					</div>
					<div 
						data-componente="footer-ventana" 
						data-accion="cursos_creados.php"
						data-mostrar_mas="<?php echo isset($cursos_creados) && count ($cursos_creados) > 0 ? "true" : "false"; ?>"
					></div>
				</div>
				<hr class="mt-4 border-0" <?php if ($sesion->getTipoUsuario() === 'empleado') echo 'hidden'; ?>>
			<div
				data-componente="ventana"
				data-titulo="Cursos pendientes recientes"
				data-color="orange"
				class="mt-2 mb-5"
			>
				<div data-tipo="contenido">
					<?php
					if (!isset ($cursos_pendientes) || count($cursos_pendientes) == 0) {
						echo '
						<div class="d-flex flex-column align-items-center p-4">
							<svg class="mb-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="60"><path fill="#CCC" d="M128 0c13.3 0 24 10.7 24 24l0 40 144 0 0-40c0-13.3 10.7-24 24-24s24 10.7 24 24l0 40 40 0c35.3 0 64 28.7 64 64l0 16 0 48 0 256c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 192l0-48 0-16C0 92.7 28.7 64 64 64l40 0 0-40c0-13.3 10.7-24 24-24zM400 192L48 192l0 256c0 8.8 7.2 16 16 16l320 0c8.8 0 16-7.2 16-16l0-256zM329 297L217 409c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47 95-95c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
							<h4 class="text-muted"><b>¡Estás al día!</b></h4>
							<h6 class="text-muted">Actualmente, no tienes cursos pendientes</h6>
						</div>
						';
					}
					else {
						$lista_cursos_pendientes = '';
							
						foreach ($cursos_pendientes as $curso) {
							// Agregar cursos pendientes del usuario
							$lista_cursos_pendientes .= '
							<div 
								data-componente="elemento-lista-CP"
								data-nombre="'. $curso ['Nombre'] .'"
								data-descripcion="'. $curso ['Descripcion'] .'"
								data-version="'. $curso ['Version'] .'"
								data-id_curso="'. $curso ['Id_curso'] .'"
								data-fecha_limite="'.$curso ['Fecha_limite'].'"
								data-asignacion="'.$curso ['Asignacion'].'"
								data-portada="'.$curso ['Portada'].'"
							></div>
							';
						}
						echo '
							<div data-componente="lista" data-no_nomina="'.$sesion->getNumNomina ().'">'.
								$lista_cursos_pendientes.
							'</div>
						';
					}						
					?>
				</div>
				<div 
					data-componente="footer-ventana" 
					data-accion="cursos_pendientes.php"
					data-mostrar_mas="<?php echo isset ($cursos_pendientes) && count($cursos_pendientes) > 0 ? "true" : "false"; ?>"
				></div>
			</div>
			
			<div
				data-componente="ventana"
				data-titulo="Últimos cursos aprobados"
				data-color="green"
				class="mt-0 mb-5"
			>
				<div data-tipo="contenido">
					<small data-componente="nota-tabla" class="aprobado px-5" <?php echo $hay_colaborativa ? '' : 'hidden'; ?>>Una <b>C</b> al final de la calificación indicará que la calificación fué colaborativa.</small>
					<?php
						if (!isset ($cursos_aprobados) || count($cursos_aprobados) == 0) {
							echo '
							<div class="d-flex flex-column align-items-center p-4">
								<svg class="mb-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="60"><path fill="#CCC" d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9l0 28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5l0-24.6c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z"/></svg>
								<h4 class="text-muted"><b>Sin cursos aprobados</b></h4>
								<h6 class="text-muted">Actualmente, no tienes cursos aprobados</h6>
							</div>
							';
						}
						else {
							$lista_cursos_aprobados = '';
								
							foreach ($cursos_aprobados as $curso) {
								// Agregar cursos aprobados por el usuario
								$lista_cursos_aprobados .= '
									<div 
									data-componente="curso-aprobado"
									data-nombre="'. htmlspecialchars ($curso ['Nombre']) .'"
									data-no_nomina="'. $curso ['No_nomina'] .'"
									data-version="'. $curso ['No_version'] .'"
									data-fecha="'. $curso ['Aprobado'] .'"
									data-intentos="'. $curso ['Intentos'] .'"
									data-puntaje="'. $curso ['Puntaje'] .'"
									data-puntaje_max="'. $curso ['Puntaje_max'] .'"
									data-id_curso="'. $curso ['Id_curso'] .'"
									data-colaborativo="'.$curso ['Colaborativo'].'"
								></div>
								';
							}
							echo 
							'<div 
							data-componente="tabla-cursos-aprobados" 
							data-color="green" class="mx-0">'.
								$lista_cursos_aprobados.
							'</div>';
						}
					?>
				</div>
				<div 
					data-componente="footer-ventana" 
					data-accion="cursos_aprobados.php"
					data-mostrar_mas="<?php echo isset ($cursos_aprobados) && count ($cursos_aprobados) > 0 ? "true" : "false"; ?>"
				></div>
			</div>

			<div
				data-componente="ventana"
				data-titulo="Cursos disponibles"
				data-color="gray"
				class="mt-0"
			>
				<div 
					data-componente="busqueda-curso"
					data-pagina="catalogo_cursos.php"
					data-empleado="<?php echo $sesion->getNumNomina () ?>">
				</div>
				<div data-tipo="contenido">
					<?php
						if (isset($cursos_disponibles) && $cursos_disponibles != null) {
							$lista_cursos_disponibles = '';
							
							foreach ($cursos_disponibles as $curso) {
								// Agregar cursos creados por el usuario
								$lista_cursos_disponibles .= '
								<div data-componente="curso-disponible"
								data-nombre="'. $curso ['Nombre'] .'"
								data-descripcion="'. $curso ['Descripcion'] .'"
								data-fecha="'.$curso ['Fecha'].'"
								data-id_curso="'.$curso ['Id_curso'].'"
								data-version="'.$curso ['Version'].'"
								data-portada="'.$curso ['Portada'].'"
								></div>
								';
							}
							echo 
							'<div data-componente="lista" data-orientacion="columna" data-tipo="catalogo">'.
								$lista_cursos_disponibles.
							'</div>';
						} 
						else {
							echo '<div class="d-flex flex-column align-items-center p-4">
								<svg class="w-6 h-6 text-gray-800 dark:text-white mb-3" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="80" viewBox="0 0 24 24">
									<path stroke="#CCC" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
								</svg>
								<h4 class="text-muted"><b>No hay más cursos disponibles</b></h4>
								<h6 class="text-muted">Por el momento no hay más cursos por mostrar</h6>
								</div>';
						}
						
					?>
				</div>
				<div 
					data-componente="footer-ventana" 
					data-accion="catalogo_cursos.php"
					data-mostrar_mas="<?php echo isset ($cursos_disponibles) && count ($cursos_disponibles) > 0 ? "true" : "false"; ?>"
				></div>
			</div>
					
			</div>
			
			<div class="d-flex flex-column mb-auto comp-secundario">
			<div
				data-componente="ventana"
				data-titulo="Mi perfil"
				data-color="dark"
				class="flex-fill mb-5"
			>
				<div data-tipo="contenido">					
					<div data-componente="bloque-info-usuario"></div>
				</div>
			</div>
			
			<div
				data-titulo="Estadisticas del usuario:"
				data-componente="bloque"
				class="flex-fill"
			>
				<div data-tipo="contenido">
					<div 
					data-componente="bloque-info-estadisticas"
					data-aprobados="<?php echo $estadisticas [0]['Aprobados'] ?>"
					data-externos="<?php echo $estadisticas [0]['Certificaciones'] ?>"
					data-pendientes="<?php echo $estadisticas [0]['Pendientes'] ?>"></div>
				</div>
			</div>
			</div>
			<hr class="mt-2 border-0">
		</article>
		
	</body>
	<script src="js/gestion_curso_interno.js"></script>
	<script src="js/plantillas.js"></script>
	<script src="js/util_movil.js"></script>
	<script src="js/certificados.js"></script>
	<script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/scroll.js"></script>
	<script src="js/email.js"></script>
	<script src="js/catalogo_cursos.js"></script>
	<script src="js/accesibilidad.js"></script>
</html>