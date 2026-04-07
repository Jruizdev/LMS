<?php 
require_once ('modulos/GestionUsuarios.php'); 
ValidarSesion (); 

// Obtener estadísticas de cursos
$estadisticas = ObtenerEstadisticas ($sesion->getNumNomina ());
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Mi información</title>
		<link href="css/bootstrap@5.3.3/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/estilos.css" rel="stylesheet"/>
		<link href="css/font-awesome.min.css" rel="stylesheet"/>
		<script src="js/bootstrap@5.3.3/bootstrap.bundle.min.js"></script>
	</head>
	<body style="height: 100vh !important;">
		<header>
			<div data-componente = "navbar-sesion-<?php echo $sesion->getTipoUsuario()?>"></div>
		</header>
		<article style="height: 100vh;">
			<div class="d-flex flex-column flex-column mx-5 my-lg-0 my-5">
				<div
				data-componente="ventana"
				data-titulo="Mi información"
				data-color="dark"
				class="mx-0 w-100 mb-xl-5">
					<div data-tipo="contenido">
						<div data-componente="bloque-info-usuario" class="info-xl"></div>
					</div>
				</div>
				
				<div
				data-componente="bloque"
				data-titulo="Estadísticas de cursos:"
				class="flex-fill w-100 mb-auto mx-0">
					<div data-tipo="contenido">
						<div 
						data-componente="bloque-info-estadisticas"
						data-aprobados="<?php echo $estadisticas [0]['Aprobados']?>"
						data-externos="<?php echo $estadisticas [0]['Certificaciones']?>"
						data-pendientes="<?php echo $estadisticas [0]['Pendientes']?>"
						class="bloque-xl"></div>
					</div>
				</div>
			</div>
			<hr class="my-5" style="border-color: transparent">
		</article>
	</body>
	<script src="js/plantillas.js"></script>

</html>