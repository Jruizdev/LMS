<?php 
require_once ('modulos/GestionUsuarios.php');
require_once ('modulos/CursosInternos.php'); 
require_once ('modulos/util/paginado.php'); 
ValidarSesion ();  
ValidarSesionInstAdmin ($sesion->getTipoUsuario ());

// Crear paginación
$tam_pag = 3;
$paginado = new Paginado (
	'cursos_creados.php', $tam_pag
);

// Recuperar criterio de búsqueda, en caso de que exista
$criterio_busqueda = isset ($_GET ['buscar']) ? $_GET ['buscar'] : null;

// Obtener información de los registros de cursos creados, considerando la paginación
$registros = ObtenerCursosCreados ( 
	$sesion->getNumNomina (), 
	$paginado->getPagActual (), 
	$tam_pag,
	urldecode ($criterio_busqueda)
); 

// Configurar paginado
$paginado->setTotalRegistros ($registros ['total_cc']);
$paginado->validarPagActual  ();

// Obtener lista de cursos creados por el usuario
$cursos_creados = $registros ['lista_cc'];
?>
<!DOCTYPE>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Mis cursos creados</title>
		<link href="css/bootstrap@5.3.3/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/estilos.css" rel="stylesheet"/>
		<link href="css/font-awesome.min.css" rel="stylesheet"/>
		<script src="js/bootstrap@5.3.3/bootstrap.bundle.min.js"></script>
	</head>
	<body style="height: 100vh !important;">
		<header>
			<div data-componente = "navbar-sesion-<?php echo $sesion->getTipoUsuario()?>"></div>
		</header>
		<article>
		
			<div
				data-componente="ventana"
				data-titulo="Cursos creados"
				data-color="dark"
				class="mb-5"
			>
				<div 
				data-componente="busqueda-curso"
				data-pagina="cursos_creados.php"
				data-empleado="<?php echo $sesion->getNumNomina () ?>"
				data-criterio="<?php echo $criterio_busqueda ?>"></div>
				<div data-tipo="contenido">
					<?php 
						if ($cursos_creados == null && $criterio_busqueda == null) {
							echo '
							<div class="d-flex flex-column align-items-center p-4" style="animation-play-state: running;">
								<svg class="w-6 h-6 text-gray-800 dark:text-white mb-3" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="80" viewBox="0 0 24 24">
									<path stroke="#CCC" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"></path>
								</svg>
								<h4 class="text-muted"><b>No tienes cursos creados todavía</b></h4>
								<h6 class="text-muted">Una vez que crees algún curso, aparecerá en esta sección</h6>
							</div>
							';
						} else if ($cursos_creados == null && $criterio_busqueda != null) {
							echo '
							<div class="d-flex flex-column align-items-center p-4" style="animation-play-state: running;">
								<h6 class="mb-0">No se encontraron resultados</h6>
							</div>
							';
						}
					?>
					<div <?php if($cursos_creados != null) echo 'data-componente="lista"'?>><?php 
						foreach ($cursos_creados as $curso) {
							// Mostrar cursos de la lista
							echo '
								<div 
								data-componente="elemento-lista-CC"
								data-nombre="'.htmlspecialchars ($curso->getNombre ()).'"
								data-descripcion="'.htmlspecialchars ($curso->getDescripcion ()).'"
								data-fecha="'.$curso->getFecha ().'"
								data-id_curso="'.$curso->getIdCurso ().'"
								data-portada="'.$curso->getPortada ().'"
							></div>
							';
						}?>
					</div>
				</div>
				<div 
				data-componente="footer-paginacion"
				data-max_pags="<?php echo $paginado->getMaxPags () ?>"
				data-pag_actual="<?php echo $paginado->getPagActual () ?>"
				data-pagina="cursos_creados.php"
				data-busqueda="<?php echo $criterio_busqueda ?>">
					<?php $paginado->crearPaginado (); ?>
				</div>
			</div>
			<hr class="my-5 border-0">
		</article>
	</body>
	<script src="js/gestion_curso_interno.js"></script>
	<script src="js/plantillas.js"></script>
	<script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/accesibilidad.js"></script>

</html>