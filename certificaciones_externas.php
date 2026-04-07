<?php 
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/CursosExternos.php'); 
require_once ('modulos/util/paginado.php');
ValidarSesion (); 

// Crear paginación
$tam_pag = 	 10; // Número de registros por página
$paginado =  new Paginado ('certificaciones_externas.php', $tam_pag);

// Recuperar criterio de búsqueda, en caso de que exista
$criterio_busqueda = isset ($_GET ['buscar']) ? $_GET ['buscar'] : null;

// Obtener certificaciones externas del empleado
$registros = ObtenerCursosExternos (
	$sesion->getNumNomina (),
	$paginado->getPagActual (),
	$tam_pag,
	urldecode ($criterio_busqueda) 
);

// Configurar paginado
$paginado->setTotalRegistros ($registros ['total_cert']);
$paginado->validarPagActual  ();

// Obtener lista de certificaciones
$certificaciones = $registros ['lista_cert'];
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Mis cursos externos</title>
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
            data-titulo="Mis cursos externos"
            data-color="blue">
                <div 
				data-componente="busqueda-curso"
				data-pagina="certificaciones_externas.php"
				data-empleado="<?php echo $sesion->getNumNomina () ?>"
				data-criterio="<?php echo $criterio_busqueda ?>"></div>
                <div data-tipo="contenido">
					<?php
						if (!isset ($certificaciones) || count($certificaciones) == 0 && $criterio_busqueda == null) {
							echo '
							<div class="d-flex flex-column align-items-center p-4">
								<svg class="mb-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="60"><path fill="#CCC" d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9l0 28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5l0-24.6c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z"/></svg>
								<h4 class="text-muted"><b>No hay certificaciones por mostrar</b></h4>
								<h6 class="text-muted">Actualmente, no tienes certificaciones externas</h6>
							</div>
							';
						} 
						else if (count($certificaciones) == 0 && $criterio_busqueda != null) {
							echo '
							<div class="d-flex flex-column align-items-center p-4">
								<h6 class="mb-0">No se encontraron resultados</h6>
							</div>
							';
						}
						else {
							$lista_certificaciones = '';
							
							foreach ($certificaciones as $certificacion) {
								// Agregar certificaciones del empleado
								$lista_certificaciones .= '
									<div
									data-componente="curso-e"
									data-id_cert="'.$certificacion ['Id_certificacion'].'"
									data-nombre="'.htmlspecialchars ($certificacion ['Nombre']).'"
									data-fecha="'.$certificacion ['Fecha'].'"
									data-validez="'.$certificacion ['Validez'].'"
									></div>
								';
							}
							echo 
							'<div 
							data-componente="tabla-admin-cursos-e" 
							data-color="" 
							class="w-100 mx-0">'.
								$lista_certificaciones.
							'</div>';
						}
					?>
                </div>
				<div 
				data-componente="footer-paginacion"
				data-max_pags="<?php echo $paginado->getMaxPags () ?>"
				data-pag_actual="<?php echo $paginado->getPagActual () ?>"
				data-pagina="certificaciones_externas.php"
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