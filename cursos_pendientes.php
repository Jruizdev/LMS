<?php 
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/CursosInternos.php');
require_once ('modulos/util/paginado.php'); 
ValidarSesion (); 

// Crear paginación
$tam_pag = 3;	// Número de registros por página
$paginado =  new Paginado ('cursos_pendientes.php', $tam_pag);

// Obtener cursos pendientes
$registros = ObtenerCursosPendientes (
	$sesion->getNumNomina(),
	$paginado->getPagActual (),
	$tam_pag
);

// Configurar paginado
$paginado->setTotalRegistros ($registros ['total_cp']);
$paginado->validarPagActual  ();

// Obtener lista de cursos pendientes
$cursos_pendientes = $registros ['lista_cp'];
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Cursos pendientes</title>
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
				data-titulo="Mis cursos pendientes"
				data-color="orange"
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
									data-nombre="'. htmlspecialchars ($curso ['Nombre']) .'"
									data-descripcion="'. htmlspecialchars ($curso ['Descripcion']) .'"
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
				data-componente="footer-paginacion"
				data-max_pags="<?php echo $paginado->getMaxPags () ?>"
				data-pag_actual="<?php echo $paginado->getPagActual () ?>"
				data-pagina="cursos_pendientes.php">
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
	<script src="js/gestion_curso_interno.js"></script>
	<script src="js/plantillas.js"></script>

</html>