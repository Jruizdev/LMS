<?php 
require_once ('modulos/GestionUsuarios.php');
require_once ('modulos/CursosInternos.php'); 
require_once ('modulos/util/paginado.php');
ValidarSesion ();
ValidarSesionInstAdmin ($sesion->getTipoUsuario ());

// Crear paginación
$tam_pag = 	 10; // Número de registros por página
$paginado =  new Paginado ('usuarios_cursos_pendientes.php', $tam_pag);

// Recuperar criterio de búsqueda, en caso de que exista
$criterio_busqueda = isset ($_GET ['buscar']) ? $_GET ['buscar'] : null;

$num_nomina = ($sesion->getTipoUsuario() != 'admin') ? 
			   $sesion->getNumNomina() : 
			   null;

// Obtener todos los cursos pendientes
$registros = ObtenerEmpleadosCP (
	$num_nomina,
	$paginado->getPagActual (), 
	$tam_pag,
	urldecode ($criterio_busqueda)
);

// Configurar paginado
$paginado->setTotalRegistros ($registros ['total_ucp']);
$paginado->validarPagActual  ();

// Obtener lista de usaurios con cursos pendientes
$cursos_pendientes = $registros ['lista_ucp'];
?>
<!DOCTYPE>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Usuarios con cursos pendientes</title>
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

			<hr class="my-4 my-sm-0 border-0">
			<div class="alerta" <?php echo $sesion->getTipoUsuario () == 'admin' ? 'hidden' : '' ?>>
				<div class="alert alert-primary w-100 d-flex align-items-center px-4" role="alert" style="animation: add-btn-in 1s">
					<svg class="me-3" style="float: left" fill="currentColor" viewBox="0 0 16 16" width="40">
						<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
					</svg>
					<div>
						<div class="text-break">Aquí puedes cosultar el historial de los empleados con cursos pendientes, en los cursos creados por tí.</div>
					</div>
				</div>
			</div>

			<div 
			data-componente="ventana"
			data-titulo="Empleados con cursos pendientes"
			data-color="orange">
				<div 
				data-componente="busqueda-curso"
				data-pagina="usuarios_cursos_pendientes.php"
				data-criterio="<?php echo $criterio_busqueda ?>"></div>
				<div data-tipo="contenido">
					<div 
					data-componente="tabla-usuarios-CP" 
					data-msg_placeholder="No hay registros de cursos pendientes" 
					style="overflow-x: auto">
						<?php
						foreach ($cursos_pendientes as $registro) {
							// Enlistar todos los cursos pendientes en la tabla
							echo '
							<div 
							data-componente="usuario-CP"
							data-nomina="'.$registro ['no_nomina'].'"
							data-id_curso="'.$registro ['Id_curso'].'"
							data-nombre="'.$registro ['nombre'].'"
							data-curso="'.htmlspecialchars ($registro ['Curso']).'"
							data-version="'.$registro ['Version'].'"
							data-fecha="'.$registro ['Fecha'].'"
							data-fecha_limite="'.$registro ['Fecha_limite'].'">
							</div>
							';
						}
						?>
					</div>
				</div>
				<div 
				data-componente="footer-paginacion"
				data-max_pags="<?php echo $paginado->getMaxPags () ?>"
				data-pag_actual="<?php echo $paginado->getPagActual () ?>"
				data-pagina="usuarios_cursos_pendientes.php"
				data-busqueda="<?php echo $criterio_busqueda ?>">
					<?php $paginado->crearPaginado (); ?>
				</div>
			</div>

			<hr class="my-5 border-0"/>
		</article>
	
	</body>
	<script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/plantillas.js"></script>
	<script src="js/gestion_curso_interno.js"></script>
	<script src="js/accesibilidad.js"></script>

</html>