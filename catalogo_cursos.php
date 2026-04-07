<?php 
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/CursosInternos.php'); 
require_once ('modulos/util/paginado.php'); 
ValidarSesion (); 
ValidarAdmin ($sesion->getTipoUsuario ());

// Crear paginación
$tam_pag = 	9;	// Número de registros por página
$paginado = new Paginado (
	'catalogo_cursos.php', $tam_pag
);

// Recuperar criterio de búsqueda, en caso de que exista
$criterio_busqueda = isset ($_GET ['buscar']) ? $_GET ['buscar'] : null; 

// Recuperar cursos internos
$registros = ObtenerCIDisponibles (
    $sesion->getNumNomina (),
    $paginado->getPagActual (),
    $tam_pag,
    urldecode ($criterio_busqueda)
);

// Configurar paginado
$paginado->setTotalRegistros ($registros ['total_ci']);
$paginado->validarPagActual  ();

// Recuperar información de los cursos internos, de la consulta
$cursos_disponibles = $registros ['lista_ci'];
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Catálogo de cursos disponibles</title>
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
			data-componente="ventana-extendida"
			data-titulo="Cursos disponibles"
			data-color="dark"
            data-xl="true"
            class="xl"
			>
				<div 
				data-componente="busqueda-curso"
				data-pagina="catalogo_cursos.php"
				data-empleado="<?php echo $sesion->getNumNomina () ?>"
				data-criterio="<?php echo $criterio_busqueda ?>"></div>
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
							'<div data-componente="lista" data-orientacion="rejilla" data-tipo="catalogo" data-registros="'.$tam_pag.'" data-pag_actual="'.$paginado->getPagActual ().'">'.
								$lista_cursos_disponibles.
							'</div>';
						} 
						else {
							echo 
							'<div class="d-flex flex-column align-items-center p-4">
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
				data-componente="footer-paginacion"
				data-max_pags="<?php echo $paginado->getMaxPags () ?>"
				data-pag_actual="<?php echo $paginado->getPagActual () ?>"
				data-pagina="catalogo_cursos.php"
				data-busqueda="<?php echo $criterio_busqueda ?>">
					<?php $paginado->crearPaginado (); ?>
				</div>
			</div>
			<hr class="my-5 border-0">
		</article>
	</body>
    <script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/plantillas.js"></script>
	<script src="js/catalogo_cursos.js"></script>
	<script src="js/email.js"></script>

</html>