<?php 
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/CursosExternos.php'); 

ValidarSesion (); 

// Comprobar que se esté visualizando una certificación
if (!isset ($_GET ['id_certificacion'])) header ('Location: inicio.php');

$id_certificacion = $_GET ['id_certificacion'];

// Recuperar información de la certificación
$certificacion = RecuperarCertificacion ($id_certificacion);
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8" />
		<title>Certificación externa</title>
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
			<div 
            data-componente="ventana"
            data-titulo="Certificación externa"
            data-color="blue">
				<div data-tipo="contenido">
					<div class="personalizado">
						<h4 class="resaltar mt-3 fw-bold">INFORMACIÓN GENERAL</h4>
						
						<div class="d-grid">
							<div class="row align-middle">
								<h5 class="fw-bold col">Empleado certificado:</h5>
								<h5 class="col text-break"><?php echo $certificacion->getNombreEmpleado () ?></h5>
							</div>
							<div class="row">
								<h5 class="fw-bold col my-auto">Nombre del curso:</h5>
								<p class="col text-break"><?php echo $certificacion->getNombre() ?></p>
							</div>
							<div class="row">
								<h5 class="fw-bold col my-auto">Descripción:</h5>
								<p class="col text-break"><?php echo $certificacion->getDescripcion () ?></p>
							</div>
							<div class="row">
								<h5 class="fw-bold col my-auto">Fecha en que se aprobó:</h5>
								<p class="col text-break"><?php echo $certificacion->getFecha () ?></p>
							</div>
							<div class="row">
								<h5 class="fw-bold col my-auto">Vencimiento:</h5>
								<p class="col text-break"><?php echo $certificacion->getValidez () ?></p>
							</div>
				
						</div>
						<hr class="my-3">
						<h4 class="resaltar fw-bold">EVIDENCIA DEL CERTIFICADO</h4>
						<iframe zoom="page-fit" loading="lazy" src='<?php echo $certificacion->getCertificado()?>'></iframe>
						<hr class="my-3 border-0">
					</div>
				</div>
			</div>
			<hr class="my-5" style="border-color: transparent">
		</article>
	</body>
	<script src="js/plantillas.js"></script>
</html>