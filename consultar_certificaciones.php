<?php 
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/CursosExternos.php'); 
require_once ('modulos/util/paginado.php');
ValidarSesion (); 
ValidarAdmin ($sesion->getTipoUsuario ());

// Crear paginación
$tam_pag = 	 10; // Número de registros por página
$paginado =  new Paginado ('certificaciones_externas.php', $tam_pag);

// Recuperar información de empleado, en caso de que se esté consultando un usuario diferente
$empleado = isset ($_GET ['nomina']) ? UsuarioEmpleado ($_GET ['nomina']) : null;
$no_nomina = isset($empleado) ? $_GET ['nomina'] : null;
$criterio_busqueda = isset ($_GET ['buscar']) ? $_GET ['buscar'] : null;

// Salir de la página si no se recibe un empleado por consultar
if (!isset ($empleado)) { header ('Location: inicio.php'); exit; } 

// Obtener certificaciones externas del empleado
$registros = ObtenerCursosExternos (
	$no_nomina,
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
		<title>Cursos externos<?php echo ' - '.$empleado ['nombre'] ?></title>
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
            data-titulo="Cursos externos"
            <?php echo isset ($empleado) ? 'data-subtitulo="'.$empleado ['nombre'].'"' : "" ?>
            data-color="blue">
                <div 
				data-componente="busqueda-curso"
				data-pagina="consultar_certificaciones.php"
				data-empleado="<?php echo $no_nomina ?>"
				data-criterio="<?php echo $criterio_busqueda ?>">
				</div>
                <div data-tipo="contenido">
                    <div data-componente="tabla-admin-Cert" class="w-100 mx-0">
                        <?php
                            if (!isset ($certificaciones) || count ($certificaciones) == 0) {
								echo 
								'<div data-tipo="vacio" data-colspan="5">
									<h6 class="text-center py-3">No hay certificaciones para mostrar</h6>
								</div>';
							}
                            else {
                                foreach ($certificaciones as $curso) {
                                    echo '
                                    <div 
                                    data-componente="admin-CE"
                                    data-id_cert="'.$curso ['Id_certificacion'].'"
                                    data-usuario="'.$curso ['No_nomina'].'"
                                    data-curso="'.$curso ['Nombre'].'"
                                    data-fecha="'.$curso ['Fecha'].'"
                                    data-vigencia="'.$curso ['Validez'].'"
                                    data-nombre_empleado="'.$empleado ['nombre'].'"
                                    ></div>
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
				data-pagina="consultar_certificaciones.php"
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
	<script src="js/gestion_curso_externo.js"></script>

</html>