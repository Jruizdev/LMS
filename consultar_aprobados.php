<?php 
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/CursosInternos.php');
require_once ('modulos/util/paginado.php');
ValidarSesion (); 
ValidarAdmin ($sesion->getTipoUsuario ());

// Crear paginación
$tam_pag =   10; // Número de registros por página
$paginado =  new Paginado ('consultar_aprobados.php', $tam_pag);

// Recuperar información de empleado, en caso de que se esté consultando un usuario diferente
$empleado = isset ($_GET ['nomina']) ? UsuarioEmpleado ($_GET ['nomina']) : null;
$no_nomina = isset($empleado) ? $_GET ['nomina'] : null;
$criterio_busqueda = isset ($_GET ['buscar']) ? $_GET ['buscar'] : null;

// Salir de la página si no se recibe un empleado por consultar
if (!isset ($empleado)) { header ('Location: inicio.php'); exit; } 

// Obtener cursos aprobados
$registros = ObtenerCursosAprobados (
	$no_nomina,
	$paginado->getPagActual (),
	$tam_pag,
	urldecode ($criterio_busqueda) 
);

// Configurar paginado
$paginado->setTotalRegistros ($registros ['total_ca']);
$paginado->validarPagActual  ();

// Obtener lista de cursos aprobados
$cursos_aprobados = $registros ['lista_ca'];
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Cursos aprobados<?php echo ' - '.$empleado ['nombre'] ?></title>
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
			data-titulo="Cursos aprobados"
			<?php echo isset ($empleado) ? 'data-subtitulo="'.$empleado ['nombre'].'"' : "" ?>
			data-color="green">
				<div 
				data-componente="busqueda-curso"
				data-pagina="consultar_aprobados.php"
				data-empleado="<?php echo $no_nomina ?>"
				data-criterio="<?php echo $criterio_busqueda ?>"></div>
                    <div data-tipo="contenido">
                        <div data-componente="tabla-cursos-aprobados" class="w-100 mx-0">
							<?php
							if (!isset ($cursos_aprobados) || count ($cursos_aprobados) == 0) {
								echo 
								'<div data-tipo="vacio" data-colspan="7">
									<h6 class="text-center py-3">No hay cursos aprobados para mostrar</h6>
								</div>';
							} 
							else {
								$lista_cursos_aprobados = '';
								foreach ($cursos_aprobados as $curso) {
									echo 
									'<div 
									data-componente="curso-aprobado"
									data-id_curso="'.$curso ['Id_curso'].'"
									data-no_nomina="'.$curso ['No_nomina'].'"
									data-nombre="'.$curso ['Nombre'].'"
									data-version="'.$curso ['No_version'].'"
									data-fecha="'.$curso ['Aprobado'].'"
									data-intentos="'.$curso ['Intentos'].'"
									data-puntaje="'.$curso ['Puntaje'].'"
									data-puntaje_max="'.$curso ['Puntaje_max'].'"
									data-colaborativo="'.$curso ['Colaborativo'].'"
									data-usuario="'.$no_nomina.'"></div>';
								}
							}
							?> 										 
                    	</div>
					</div>
				<div 
				data-componente="footer-paginacion"
				data-max_pags="<?php echo $paginado->getMaxPags () ?>"
				data-pag_actual="<?php echo $paginado->getPagActual () ?>"
				data-pagina="consultar_aprobados.php"
				<?php echo isset ($empleado) ? 'data-empleado="'.$no_nomina.'"' : '' ?>>
					<?php $paginado->crearPaginado (); ?>
				</div>
			</div>
			<hr class="my-5 border-0">
		</article>
	</body>
	<script src="js/util_movil.js"></script>
	<script src="js/certificados.js"></script>
	<script src="js/plantillas.js"></script>
	
</html>