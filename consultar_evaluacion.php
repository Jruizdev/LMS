<?php
require_once ('modulos/GestionUsuarios.php');
require_once ('modulos/util/curso.php');
require_once ('modulos/CursosInternos.php');
ValidarSesion ();
?>
<!DOCTYPE  html>
<html lang="es">
	<head>
		<meta charset="utf-8"/>
		<title>Resultado de evaluación</title>
		<link href="css/bootstrap@5.3.3/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/estilos.css" rel="stylesheet"/>
		<link href="css/font-awesome.min.css" rel="stylesheet"/>
		<script src="js/bootstrap@5.3.3/bootstrap.bundle.min.js"></script>
	</head>
	<body style="min-height: 100vh !important;">
		<header>
			<div data-componente = "navbar-sesion-<?php echo $sesion->getTipoUsuario()?>"></div>
		</header>
		<article>
		
			<button class="scroll-top d-flex" onclick="ScrollTop ()">
				<svg class="m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40"><path fill="#fff" d="M233.4 105.4c12.5-12.5 32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L256 173.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l192-192z"/></svg>
			</button>
			
			<button class="scroll-bottom d-flex" style="margin-bottom: 5rem" onclick="ScrollBottom ()">
				<svg class="m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40"><path fill="#fff" d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>
			</button>
		
			<div 
			data-componente="ventana"
			data-titulo="Resultado de la evaluación"
			data-color="gray">
				<div data-tipo="contenido">
					<div class="personalizado"><?php MostrarResultadoEvaluacion ()?></div>
				</div>
			</div>
			<div class="barra-inferior fixed-bottom d-flex shadow-lg">
				<button class="btn btn-svg mx-auto" style="width: max(15%, 250px); background-color: var(--entidad-primario); color: var(--light);" onclick="window.location.replace('inicio.php')">
					<span>Salir</span>
					<svg class="ms-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#FFF" d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
				</button>
			</div>
		</article>
		<hr class="border-0" style="height: 15vh;">
	</body>
	<script src="js/plantillas.js"></script>
	<script src="js/scroll.js"></script>
	<script src="js/evaluacion.js"></script>

</html>