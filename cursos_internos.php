<?php 
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/CursosInternos.php'); 
require_once ('modulos/util/paginado.php'); 
ValidarSesion (); 
ValidarAdmin ($sesion->getTipoUsuario ());

// Crear paginación
$tam_pag = 	10;	// Número de registros por página
$paginado = new Paginado (
	'cursos_internos.php', $tam_pag
);

// Recuperar criterio de búsqueda, en caso de que exista
$criterio_busqueda = isset ($_GET ['buscar']) ? $_GET ['buscar'] : null; 

// Recuperar cursos internos
$registros = ObtenerTodosCI (
	$paginado->getPagActual (), 
	$tam_pag,
	urldecode ($criterio_busqueda)
);

// Configurar paginado
$paginado->setTotalRegistros ($registros ['total_ci']);
$paginado->validarPagActual  ();

// Recuperar información de los cursos internos, de la consulta
$cursos_internos = 	$registros ['lista_ci'];
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Cursos internos</title>
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
			data-titulo="Cursos internos"
			data-color="dark"
			>
				<div 
				data-componente="busqueda-curso"
				data-pagina="cursos_internos.php"
				data-empleado="<?php echo $sesion->getNumNomina () ?>"
				data-criterio="<?php echo $criterio_busqueda ?>"></div>
				<div data-tipo="contenido">
					<div data-componente="tabla-admin-cursos-i" style="overflow-x: auto;">
						<?php
						foreach ($cursos_internos as $curso) {
							echo '
							<div 
							data-componente="admin-curso-i"
							data-nombre="'.htmlspecialchars ($curso ['Nombre']).'"
							data-usuario="'.$curso ['nombre'].'"
							data-version="'.$curso ['Version'].'"
							data-fecha="'.$curso ['Fecha'].'"
							data-id_curso="'.$curso ['Id_curso'].'"></div>
							';
						}
						?>
					</div>
				</div>
				<div 
				data-componente="footer-paginacion"
				data-max_pags="<?php echo $paginado->getMaxPags () ?>"
				data-pag_actual="<?php echo $paginado->getPagActual () ?>"
				data-pagina="cursos_internos.php"
				data-busqueda="<?php echo $criterio_busqueda ?>">
					<?php $paginado->crearPaginado (); ?>
				</div>
			</div>
			<hr class="my-5 border-0">
		</article>
	</body>
	<script src="js/plantillas.js"></script>
	<script src="js/accesibilidad.js"></script>

</html>