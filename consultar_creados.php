<?php 
require_once ('modulos/GestionUsuarios.php');
require_once ('modulos/CursosInternos.php'); 
require_once ('modulos/util/paginado.php'); 
ValidarSesion ();  
ValidarAdmin ($sesion->getTipoUsuario ()); 

// Crear paginación
$tam_pag =  10;
$paginado = new Paginado (
	'cosultar_creados.php', $tam_pag
);

// Recuperar información de empleado, en caso de que se esté consultando un usuario diferente
$empleado = isset ($_GET ['nomina']) ? UsuarioEmpleado ($_GET ['nomina']) : null;
$no_nomina = isset($empleado) ? $_GET ['nomina'] : null;
$criterio_busqueda = isset ($_GET ['buscar']) ? $_GET ['buscar'] : null;

// Salir de la página si no se recibe un empleado por consultar
if (!isset ($empleado)) { header ('Location: inicio.php'); exit; } 

// Obtener información de los registros de cursos creados, considerando la paginación
$registros = ObtenerCursosCreados ( 
	$no_nomina, 
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
		<title>Cursos creados<?php echo ' - '.$empleado ['nombre'] ?></title>
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
                <?php echo isset ($empleado) ? 'data-subtitulo="'.$empleado ['nombre'].'"' : "" ?>
				data-color="dark"
				class="mb-5"
			>
				<div 
				data-componente="busqueda-curso"
				data-pagina="consultar_creados.php"
				data-empleado="<?php echo $no_nomina ?>"
				data-criterio="<?php echo $criterio_busqueda ?>"></div>
				<div data-tipo="contenido">

                    <div data-componente="tabla-CC-admin" class="w-100 mx-0" style="animation-play-state: running;">
                        <?php
                            if (!isset ($cursos_creados) || count ($cursos_creados) == 0) {
                                echo 
                                '<div data-tipo="vacio" data-colspan="5">
                                    <h6 class="text-center py-3">No hay cursos creados todavía</h6>
                                </div>';
                            }
                            else {
                                foreach ($cursos_creados as $curso) {
                                    echo '
                                    <div 
                                    data-componente="CC-admin"
                                    data-curso="'.$curso->getNombre ().'"
                                    data-version="'.$curso->getVersionActual ().'"
                                    data-id_curso="'.$curso->getIdCurso ().'"
                                    >
                                    </div>
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
				data-pagina="consultar_creados.php"
                <?php echo isset ($empleado) ? 'data-empleado="'.$no_nomina.'"' : '' ?>>
					<?php $paginado->crearPaginado (); ?>
				</div>
			</div>
			<hr class="my-5 border-0">
		</article>
	</body>
	<script src="js/gestion_curso_interno.js"></script>
	<script src="js/historial_versiones.js"></script>
	<script src="js/plantillas.js"></script>
	<script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>

	<script>
		function RealizarBusqueda () {
			console.log ("Buscando");
		}
	</script>

</html>