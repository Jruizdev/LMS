<?php 
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/CursosExternos.php'); 
require_once ('modulos/util/paginado.php'); 
ValidarSesion (); 
ValidarAdmin ($sesion->getTipoUsuario ());

// Número de registros por página
$tam_pag =   10;
$paginado =  new Paginado ('cursos_externos.php', $tam_pag);

// Recuperar criterio de búsqueda, en caso de que exista
$criterio_busqueda = isset ($_GET ['buscar']) ? $_GET ['buscar'] : null;

// Obtener lista de cursos externos
$cursos_externos = array ();

// Obtener registros de los cursos externos
$registros = ObtenerTodosCE (
    $paginado->getPagActual (), 
    $tam_pag,
    urldecode ($criterio_busqueda)
);

foreach ($registros ['lista_ce'] as $curso) {
    $registro = new CursoExterno (
        $curso ['Nombre'],
        $curso ['Descripcion'],
        null,
        'EXT',
        $curso ['Tags'],
        null
    );
    $registro->setIdCurso ($curso ['Id_curso']);
    $registro->setIdExt ($curso ['Id_ext']);
    $registro->setFecha ($curso ['Fecha']);
    $registro->setVigencia ($curso ['Vigencia']);
    $registro->setUnidad ($curso ['Unidad']);

    array_push ($cursos_externos, $registro);
}
// Configurar paginado
$paginado->setTotalRegistros ($registros ['total_ce']);
$paginado->validarPagActual  ();
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="utf-8"/>
        <title>Cursos externos disponibles</title>
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
            data-titulo="Cursos externos disponibles"
            data-color="blue">
                <div 
                data-componente="busqueda-curso"
                data-pagina="cursos_externos.php"
				data-empleado="<?php echo $sesion->getNumNomina () ?>"
				data-criterio="<?php echo $criterio_busqueda ?>"></div>
                <div data-tipo="contenido">
                    <div data-componente="tabla-admin-cursos-e" style="overflow-x: auto;">
                        <?php
                        foreach ($cursos_externos as $curso) {
                            echo '
                            <div 
                            data-componente="admin-curso-e"
                            data-id_curso="'.$curso->getIdCurso().'"
                            data-id_ext="'.$curso->getIdExt().'"
                            data-nombre="'.htmlspecialchars ($curso->getNombre ()).'"
                            data-descripcion="'.$curso->getDescripcion ().'"
                            data-fecha="'.$curso->getFecha ().'"
                            data-validez="'.$curso->getVigencia ().'"></div>
                            ';
                        }
                        ?>
                    </div>
                </div>
				<div 
				data-componente="footer-paginacion"
				data-max_pags="<?php echo $paginado->getMaxPags () ?>"
				data-pag_actual="<?php echo $paginado->getPagActual () ?>"
				data-pagina="cursos_externos.php"
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
    <script src="js/gestion_curso_externo.js"></script>
    <script src="js/accesibilidad.js"></script>

</html>