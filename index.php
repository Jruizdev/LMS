<?php 
require_once ('modulos/util/mensaje.php');
require_once ('modulos/util/cookiescursos.php');
require_once ('modulos/GestionUsuarios.php'); 
ValidarInicioSesion (); 

// Elemento que contendrá el mensaje de resultado del proceso de autenticación
$mensaje = new Mensaje();

if(isset($_COOKIE ['error'])) {
	
	// Gestionar mensaje de error al iniciar sesión, en caso de que exista
	$mensaje = new Mensaje (
		'error_usuario', $_COOKIE ['error'], null, 'Usuario no encontrado'
	);
	EliminarCookie ('error');
}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8"/>
		<title>Iniciar Sesión</title>
		<link href="css/bootstrap@5.3.3/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/estilos.css" rel="stylesheet"/>
		<link href="css/font-awesome.min.css" rel="stylesheet"/>
		<script src="js/bootstrap@5.3.3/bootstrap.bundle.min.js"></script>
	</head> 
	<body class="d-flex flex-column" style="min-height: 100vh; background: -webkit-linear-gradient(to bottom, #FFFFFF, #ECE9E6);background: linear-gradient(to bottom, #FFFFFF, #ECE9E6);">
		
		<article class="my-auto p-0">

		<div class="d-flex flex-column flex-xl-row align-items-center">
			
			<svg style="z-index: -2; filter: drop-shadow(0 5px 5px #AAA); height: 45%; top: 15px; left: 0; position: absolute; transform: translateX(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#ff3030" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"/></svg>
			<svg style="z-index: -2; filter: drop-shadow(0 5px 5px #AAA); height: 45%; bottom: 15px; left: 0; position: absolute; transform: translateX(-50%);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#ff3030" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"/></svg>

			<div class="w-50 d-flex flex-column me-0 pe-0 py-5 text-right" style="animation: fadeIn 1s;">
				<svg class="mx-auto ms-lg-auto mt-auto w-75 w-lg-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 196.881 34.857" style="filter: drop-shadow(0 0 5px #FFF)">
					<defs><style>.entidad-logo-fill-el { fill: #ff0a07; }</style></defs>
					<g id="entidad_logo" data-name="Group entidad logo" transform="translate(-745.009 -371.143)">
						<path id="logo-b" data-name="Path B" class="entidad-logo-fill-el" d="M757.4,371.143H745.009V406h41.639V381.009H757.4Zm16.851,16.428v11.013H757.4V387.571Z"/>
						<path id="logo-o" data-name="Path O" class="entidad-logo-fill-el" d="M791.131,406H832.77V381.009H791.131Zm12.394-18.429h16.851v11.013H803.525Z"/>
						<path id="logo-s" data-name="Path S" class="entidad-logo-fill-el" d="M837.252,395.824H866.5v2.759H837.252V406h41.639V390.331H849.646v-2.76h29.245v-6.562H837.252Z"/>
						<path id="logo-a" data-name="Path A" class="entidad-logo-fill-el" d="M883.374,387.571h29.245v2.76H883.374V406h41.639V381.009H883.374Zm29.245,11.013H895.768v-2.76h16.851Z"/>
						<rect id="logo-l" data-name="Path L" class="entidad-logo-fill-el" width="12.394" height="34.857" transform="translate(929.496 371.143)"/>
					</g>
				</svg>
				<span class="mt-2 mb-auto mx-auto text-center" style="color: #232323; font-size: 1.2rem;"><b>:Learning Management System</b></span>
			</div>
			<div
				data-componente="bloque"
				data-titulo="Iniciar sesión"
				data-color="light-red"
				class="w-75 mb-5"
			>
				<div data-tipo="contenido">
					
					<div class="personalizado pb-3 gap-0">
					
						<div class="input-group mb-4">
							<span class="input-group-text">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="30"><path fill="#555" d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/></svg>
							</span>
							<input oninput="ValidarNomina(this)" name="num_nomina" class="form-control" type="number" placeholder="Ingresa tu número de nómina"/>
						</div>
						<div id="autenticacion" class="collapse multi-collapse">
							<h5 class="resaltar">Autenticación requerida:</h5>
							<div class="input-group">
								<span class="input-group-text">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30"><path fill="#AAA" d="M336 352c97.2 0 176-78.8 176-176S433.2 0 336 0S160 78.8 160 176c0 18.7 2.9 36.8 8.3 53.7L7 391c-4.5 4.5-7 10.6-7 17l0 80c0 13.3 10.7 24 24 24l80 0c13.3 0 24-10.7 24-24l0-40 40 0c13.3 0 24-10.7 24-24l0-40 40 0c6.4 0 12.5-2.5 17-7l33.3-33.3c16.9 5.4 35 8.3 53.7 8.3zM376 96a40 40 0 1 1 0 80 40 40 0 1 1 0-80z"/></svg>
								</span>
								<input oninput="HabilitarBtnIS (this)" name="pass" class="form-control" type="password" placeholder="Ingresa tu contraseña"/>
							</div>
							<div class="d-flex">
								<button class="btn-link ms-auto mt-2 p-0" onclick="RecuperarPass ()">¿Olvidaste tu contraseña?</button>
							</div>
							<hr class="border-0 my-3">
						</div>
						<button id="btn-iniciar" class="btn-primario btn-icono d-flex" data-tipo="btn-accion" onclick="IniciarSesion ()" disabled>
							<p class="ms-auto my-auto">Iniciar sesión</p>
							<svg class="ms-2 me-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30px"><path fill="#FFF" d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"/></svg>
						</button>
					</div>
					
				</div>
			</div>
		</div>
		</article>
		
	</body>
	<script src="js/plantillas.js"></script>
	<script>
		var redireccionar = '<?php echo isset ($_GET ['id']) ? base64_decode ($_GET ['id']) : ''; ?>';
		var mensaje = {
			tipo: '<?php echo $mensaje->getTipo() ?>',
			texto: '<?php echo $mensaje->getTexto() ?>',
			accion: '<?php echo $mensaje->getAccion() ?>',
			titulo: '<?php echo $mensaje->getTitulo() ?>'
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/tooltip.js"></script>
	<script src="js/nuevo_usuario.js"></script>
	<script src="js/validacion_in.js"></script>
	<script src="js/accesibilidad.js"></script>
	<script src="js/iniciar_sesion.js"></script>
	
</html>