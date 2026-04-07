<?php 
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/CursosInternos.php');
require_once ('modulos/util/paginado.php'); 
ValidarSesion (); 
ValidarAdmin ($sesion->getTipoUsuario ()); 

// Crear paginación
$tam_pag = 	 10;	// Número de registros por página
$paginado =  new Paginado ('consultar_pendientes.php', $tam_pag);
$no_nomina = $_GET ['nomina'];

// Recuperar información de empleado, en caso de que se esté consultando un usuario diferente
$empleado = isset ($no_nomina) ? UsuarioEmpleado ($no_nomina) : null;

// Salir de la página si no se recibe un empleado por consultar
if (!isset ($no_nomina)) { header ('Location: inicio.php'); exit; } 

// Obtener cursos pendientes
$registros = ObtenerCursosPendientes (
	$no_nomina,
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
		<title>Consultar pendientes<?php echo ' - '.$empleado ['nombre'] ?></title>
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
				data-titulo="Cursos pendientes"
				<?php echo isset ($empleado) ? 'data-subtitulo="'.$empleado ['nombre'].'"' : "" ?>
				data-color="orange"
			>
				<div data-tipo="contenido">
					<div data-componente="tabla-CP-admin" class="w-100 mx-0">
						<?php
						if (!isset ($cursos_pendientes) || count ($cursos_pendientes) == 0) {
							echo 
							'<div data-tipo="vacio" data-colspan="5">
								<h6 class="text-center py-3">No hay cursos pendientes para mostrar</h6>
							</div>';
						} 
						else {
							$lista_cursos_pendientes = '';
							foreach ($cursos_pendientes as $curso) {
								echo '
								<div 
								data-componente="CP-admin"
								data-id_curso="'.$curso ['Id_curso'].'"
								data-curso="'.$curso ['Nombre'].'"
								data-version="'.$curso ['Version'].'"
								data-fecha="'.$curso ['Fecha'].'"
								data-usuario="'.$no_nomina.'"></div>
								';
							}
						}
						?>
					</div>
				</div>
				<div 
				data-componente="footer-paginacion"
				data-max_pags="<?php echo $paginado->getMaxPags () ?>"
				data-pag_actual="<?php echo $paginado->getPagActual () ?>"
				data-pagina="consultar_pendientes.php"
				<?php echo isset ($empleado) ? 'data-empleado="'.$_GET ['nomina'].'"' : '' ?>>
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
	<script src="js/gestion_curso_interno.js"></script>

</html>