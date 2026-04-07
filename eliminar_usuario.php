<?php 
require_once ('modulos/util/mensaje.php');
require_once ('modulos/GestionUsuarios.php'); 
ValidarSesion (); 
ValidarAdmin ($sesion->getTipoUsuario ());

$mensajes = array (
	'error_1' => 'No se encontró ningún usuario con privilegios con este número de nómina.',
	'error_2' => 'No se puede eliminar a este usuario desde la sesión activa.',
	'success' => 'El usuario fue eliminado correctamente.'
);

// Mensaje resultante de la ejecución del proceso
$mensaje = new Mensaje ();

// Esperar formulario de búsqueda de empleado
$empleado = RecibirEmpleadoRegistrado ();

if(isset($_POST ['no_nomina']) && !$empleado) {
	$mensaje = new Mensaje ('error_usuario', $mensajes ['error_1'], null);
}

if (isset ($_POST ['eliminar'])) {
	
	// Evitar que usuario se elimine a sí mismo
	if($_POST ['no_nomina'] != $sesion->getNumNomina()) {
		
		$resultado = EliminarUsuario ($_POST ['no_nomina']);
		
		if ($resultado) {
			// Se eliminó correctamente el usuario
			$empleado = null;
			$mensaje = new Mensaje ('success', $mensajes['success'], 'inicio.php');
		}
	}
	else $mensaje = new Mensaje ('error', $mensajes['error_2'], null);
}

// Eliminar consulta de usuario al existir un mensaje
if($mensaje->getTipo()) $empleado = null;
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Eliminar usuario existente</title>
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
			data-titulo="Eliminar usuario"
			data-color="dark">
				<div data-tipo="contenido">
					<div class="d-flex flex-column flex-xl-row">
						<div class="personalizado mx-auto mt-5 m-xl-5 w-100" style="max-width: 250px;">
							<svg class="w-100" style="max-width: 200px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="#CCC" d="M112 48a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm40 304l0 128c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-223.1L59.4 304.5c-9.1 15.1-28.8 20-43.9 10.9s-20-28.8-10.9-43.9l58.3-97c17.4-28.9 48.6-46.6 82.3-46.6l29.7 0c33.7 0 64.9 17.7 82.3 46.6l44.9 74.7c-16.1 17.6-28.6 38.5-36.6 61.5c-1.9-1.8-3.5-3.9-4.9-6.3L232 256.9 232 480c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-128-16 0zm136 16a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm224 0c0-8.8-7.2-16-16-16l-128 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l128 0c8.8 0 16-7.2 16-16z"/></svg>
						</div>
						<div 
						data-componente="buscar-empleado-nomina"
						data-msg="Selecciona el empleado por eliminar:"
						data-action="eliminar_usuario.php"
						class="w-100 my-auto">
						</div>
					</div>
				</div>
			</div>
			
			<div <?php if($empleado == null) echo 'style="display: none;"'; ?>
			data-componente="bloque"
			data-titulo="Información del usuario:"
			class="my-5 py-3">
				<div data-tipo="contenido">
					<div class="personalizado">
						<div
						data-componente="info-empleado"
						data-num_nomina="<?php if(isset($empleado)) echo $empleado->getNumNomina ()?>"
						data-nombre="<?php if(isset($empleado)) echo $empleado->getNombre ()?>"
						data-departamento="<?php if(isset($empleado)) echo $empleado->getDepartamento ()?>"
						data-email="<?php if(isset($empleado)) echo $empleado->getEmail ()?>"
						data-tipo="<?php if(isset($empleado)) echo $empleado->getNombreTipoUsuario ()?>"></div>
						
						<form class="d-flex" action="eliminar_usuario.php" method="post" onsubmit="EliminarUsuario(this, event)">
							<input type="hidden" name="eliminar" value="true"/>
							<input type="hidden" name="no_nomina" value="<?php if(isset($empleado)) echo $empleado->getNumNomina()?>"/>
							<div class="d-flex flex-column w-100">
								<button class="btn btn-svg ms-md-auto d-flex py-3 px-5" style="background-color: var(--entidad-primario)">
									<span class="ms-auto">Eliminar usuario</span>
									<svg class="ms-3 me-auto my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="30"><path fill="#fff" d="M112 48a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm40 304l0 128c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-223.1L59.4 304.5c-9.1 15.1-28.8 20-43.9 10.9s-20-28.8-10.9-43.9l58.3-97c17.4-28.9 48.6-46.6 82.3-46.6l29.7 0c33.7 0 64.9 17.7 82.3 46.6l44.9 74.7c-16.1 17.6-28.6 38.5-36.6 61.5c-1.9-1.8-3.5-3.9-4.9-6.3L232 256.9 232 480c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-128-16 0zm136 16a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm224 0c0-8.8-7.2-16-16-16l-128 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l128 0c8.8 0 16-7.2 16-16z"/></svg>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			
		</article>
	</body>
	<script src="js/plantillas.js"></script>
	<script>
		const empleado_encontrado = <?php echo ($empleado == null) ? 'false' : 'true' ?>;
		
		setTimeout(function () {
			
			// Hacer scroll hasta el panel de información, en caso de encontrar el empleado
			if (empleado_encontrado) {
				const $panel_info = document.querySelector ('div[data-componente="bloque"]');
				$panel_info.scrollIntoView({
					behavior: 'auto',
					block: 	  'nearest',
					inline:   'center'
				});
			}
		}, 500);

		function EliminarUsuario (formulario, e) {
			e.preventDefault ();

			MostrarMensaje (
				'Eliminar usuario', 
				'Se eliminarán los privilegios de este usuario,<hr class="my-2 border-0">' +
				'¿Deseas continuar?', 
				() => formulario.submit(), icono_pregunta, true, true
			);
		}
	</script>
	<script>
		var mensaje = {
			tipo: '<?php echo $mensaje->getTipo() ?>',
			texto: '<?php echo $mensaje->getTexto() ?>',
			accion: '<?php echo $mensaje->getAccion() ?>'
		};
	</script>
	<script src="js/mensajes.js"></script>

</html>