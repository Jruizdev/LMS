<?php
require_once ('modulos/util/mensaje.php'); 
require_once ('modulos/GestionUsuarios.php'); 
ValidarSesion ();
ValidarAdmin ($sesion->getTipoUsuario ());

// Posibles tipos de usuario en el sistema
$TIPOS_USUARIO = ['Empleado', 'Instructor', 'Administrador'];

// Elemento que contendrá el mensaje de resultado del proceso de eliminación
$mensaje = new Mensaje();

// Esperar formulario de búsqueda de empleado
$empleado = RecibirEmpleado ();

if (isset($_POST ['no_nomina']) && !$empleado) {
	$mensaje = new Mensaje (
		'error_usuario', 'No se encontró ningún empleado con este número de nómina.', null
	);
}

if (isset ($_POST ['registrar'])) {
	// Crear usuario con los datos proporcionados
	$resultado = CrearUsuario (
		$_POST ['no_nomina'],
		$_POST ['tipo_usuario'],
		$_POST ['pass'],
		$_POST ['creador'],
		$_POST ['email'],
		$_POST ['email_externo']
	);

	if ($resultado == NULL) {
		// Se registró correctamente el usuario
		header ('Location: inicio.php'); exit;
	}
}
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Crear nuevo usuario</title>
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
			data-titulo="Crear nuevo usuario"
			data-color="dark">
				<div data-tipo="contenido">
					<div class="d-flex flex-column flex-xl-row">
						<div class="personalizado mx-auto mt-5 m-xl-5 w-100" style="max-width: 250px;">
							<svg class="w-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="#CCC" d="M112 48a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm40 304l0 128c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-223.1L59.4 304.5c-9.1 15.1-28.8 20-43.9 10.9s-20-28.8-10.9-43.9l58.3-97c17.4-28.9 48.6-46.6 82.3-46.6l29.7 0c33.7 0 64.9 17.7 82.3 46.6l44.9 74.7c-16.1 17.6-28.6 38.5-36.6 61.5c-1.9-1.8-3.5-3.9-4.9-6.3L232 256.9 232 480c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-128-16 0zM432 224a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm16 80c0-8.8-7.2-16-16-16s-16 7.2-16 16l0 48-48 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l48 0 0 48c0 8.8 7.2 16 16 16s16-7.2 16-16l0-48 48 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-48 0 0-48z"/></svg>
						</div>
						<div 
						data-componente="buscar-empleado-nomina"
						data-action="nuevo_usuario.php"
						class="w-100 my-auto"></div>
					</div>
				</div>
			</div>
			
			<div <?php if ($empleado == null) echo 'style="display: none;"'; ?>
			data-componente="bloque"
			data-titulo="Información del empleado:"
			class="my-5">
				<div data-tipo="contenido" class="mb-4">
					<div class="personalizado">
						<?php
						$tipo_usuario = isset($empleado) ? $TIPOS_USUARIO [$empleado->getTipoUsuario ()] : NULL;
						if (isset($empleado)) echo '
							<div 
							data-componente="info-empleado"
							data-num_nomina="'.$empleado->getNumNomina ().'"
							data-nombre="'.$empleado->getNombre ().'"
							data-departamento="'.$empleado->getDepartamento ().'"
							data-email="'.$empleado->getEmail ().'"
							data-tipo="'.$tipo_usuario.'"/></div>
						';
						?>
						<hr class="my-2"/>
						<form id="form-nuevo-usuario" action="nuevo_usuario.php" method="post">
							<input type="hidden" name="registrar" value="true"/>
							<input type="hidden" name="creador" value="<?php echo $sesion->getNumNomina () ?>"/>
							<input type="hidden" name="no_nomina" value="<?php if (isset ($empleado)) echo $empleado->getNumNomina () ?>"/>
							<div class="d-grid gap-3">
								<h5 class="mb-0">Tipo de usuario por crear:</h5>
								<select name="tipo_usuario" class="form-select">
									<option selected>Instructor</option>
									<option>Administrador</option>
								</select>

								<div <?php echo (isset ($empleado) && $empleado->getEmail ()) ? 'hidden' : '' ?>>
									<div class="alert alert-primary w-100 d-flex align-items-center px-4 mb-0 mt-3" role="alert">
										<svg class="me-3" style="float: left; min-width: 30px;" fill="currentColor" viewBox="0 0 16 16" width="40">
											<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"></path>
										</svg>
										<div style="animation-play-state: running;">
											<div class="text-break" style="animation-play-state: running;">Se ha detectado que el empleado no cuenta con un correo electrónico asignado por la empresa, por lo que, es necesario proporcionar algún correo electrónico alterno para dar de alta el nuevo usuario.</div>
										</div>
									</div>
									<h5 class="my-3">Correo electrónico del empleado:</h5>
									<div class="input-group">
										<span class="input-group-text">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="25"><path fill="#AAA" d="M256 64C150 64 64 150 64 256s86 192 192 192c17.7 0 32 14.3 32 32s-14.3 32-32 32C114.6 512 0 397.4 0 256S114.6 0 256 0S512 114.6 512 256l0 32c0 53-43 96-96 96c-29.3 0-55.6-13.2-73.2-33.9C320 371.1 289.5 384 256 384c-70.7 0-128-57.3-128-128s57.3-128 128-128c27.9 0 53.7 8.9 74.7 24.1c5.7-5 13.1-8.1 21.3-8.1c17.7 0 32 14.3 32 32l0 80 0 32c0 17.7 14.3 32 32 32s32-14.3 32-32l0-32c0-106-86-192-192-192zm64 192a64 64 0 1 0 -128 0 64 64 0 1 0 128 0z"/></svg>
										</span>
										<input name="email" class="form-control" type="email" placeholder="Ingresa el correo electrónico personal o alterno">
									</div>
								</div>

								<div class="d-flex flex-column mt-3">
									<button class="btn btn-svg ms-md-auto px-4 py-3" type="button" onclick="CrearUsuario('<?php echo isset ($empleado) ? $empleado->getEmail () : '' ?>')" style="background-color: var(--entidad-primario) !important;">
										<span>Crear usuario</span>
										<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="25"><path fill="#FFF" d="M112 48a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm40 304l0 128c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-223.1L59.4 304.5c-9.1 15.1-28.8 20-43.9 10.9s-20-28.8-10.9-43.9l58.3-97c17.4-28.9 48.6-46.6 82.3-46.6l29.7 0c33.7 0 64.9 17.7 82.3 46.6l44.9 74.7c-16.1 17.6-28.6 38.5-36.6 61.5c-1.9-1.8-3.5-3.9-4.9-6.3L232 256.9 232 480c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-128-16 0zM432 224a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm16 80c0-8.8-7.2-16-16-16s-16 7.2-16 16l0 48-48 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l48 0 0 48c0 8.8 7.2 16 16 16s16-7.2 16-16l0-48 48 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-48 0 0-48z"/></svg>
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<hr class="my-5" style="border-color: transparent">
		</article>
	</body>
	<script src="js/plantillas.js"></script>
	<script>
		const empleado_encontrado = <?php echo ($empleado == null) ? 'false' : 'true' ?>;
		
		setTimeout(function () {
			
			// Hacer scroll hasta el panel de información, en caso de encontrar el empleado
			if(empleado_encontrado) {
				const $panel_info = document.querySelector ('div[data-componente="bloque"]');
				$panel_info.scrollIntoView({
					behavior: 'auto',
					block: 	  'end',
					inline:   'center'
				});
			}
		}, 500);
		
	</script>
	<script>
		var mensaje = {
			tipo: '<?php echo $mensaje->getTipo() ?>',
			texto: '<?php echo $mensaje->getTexto() ?>',
			accion: '<?php echo $mensaje->getAccion() ?>'
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/nuevo_usuario.js"></script>
	<script src="js/validacion_in.js"></script>

</html>