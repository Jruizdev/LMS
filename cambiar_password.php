<?php 
require_once ('modulos/GestionUsuarios.php'); 
ValidarSesion (); 
ValidarSesionInstAdmin ($sesion->getTipoUsuario ());
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Cambiar contraseña</title>
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
			data-titulo="Cambiar contraseña"
			data-color="dark">
				<div data-tipo="contenido">
	
					<div class="personalizado gap-5 my-5">
						<div class="d-flex flex-column flex-lg-row">
							
							<div class="m-auto mb-4 mb-lg-auto d-flex pe-5 w-50 h-100" style="max-width: 250px; min-width: 200px;">
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 122.879 118.662"><g><path fill="#DDD" clip-rule="evenodd" d="M43.101,54.363h4.138v-8.738c0-4.714,1.93-8.999,5.034-12.105v-0.004 c3.105-3.105,7.392-5.034,12.108-5.034c4.714,0,8.999,1.929,12.104,5.034l0.004,0.004c3.104,3.105,5.034,7.392,5.034,12.104v8.738 l3.297,0.001c0.734,0,1.335,0.601,1.335,1.335v28.203c0,0.734-0.602,1.335-1.336,1.335H43.101c-0.734,0-1.336-0.602-1.336-1.335 V55.698C41.765,54.964,42.366,54.363,43.101,54.363L43.101,54.363z M16.682,22.204c-1.781,2.207-3.426,4.551-5.061,7.457 c-5.987,10.645-8.523,22.731-7.49,34.543c1.01,11.537,5.432,22.827,13.375,32.271c2.853,3.392,5.914,6.382,9.132,8.968 c11.112,8.935,24.276,13.341,37.405,13.216c13.134-0.125,26.209-4.784,37.145-13.981c3.189-2.682,6.179-5.727,8.915-9.13 c6.396-7.957,10.512-17.29,12.071-27.138c1.532-9.672,0.595-19.829-3.069-29.655c-3.487-9.355-8.814-17.685-15.775-24.206 C96.695,8.333,88.593,3.755,79.196,1.483c-2.943-0.712-5.939-1.177-8.991-1.374c-3.062-0.197-6.193-0.131-9.401,0.224 c-2.011,0.222-3.459,2.03-3.238,4.041c0.222,2.01,2.03,3.459,4.04,3.237c2.783-0.308,5.495-0.366,8.141-0.195 c2.654,0.171,5.23,0.568,7.731,1.174c8.106,1.959,15.104,5.914,20.838,11.288c6.138,5.751,10.847,13.125,13.941,21.427 c3.212,8.613,4.035,17.505,2.696,25.959c-1.36,8.589-4.957,16.739-10.553,23.699c-2.469,3.071-5.121,5.78-7.912,8.127 c-9.591,8.067-21.031,12.153-32.502,12.263c-11.473,0.109-23.001-3.762-32.764-11.61c-2.895-2.328-5.621-4.983-8.129-7.966 c-6.917-8.224-10.771-18.092-11.655-28.202c-0.908-10.375,1.317-20.988,6.572-30.331c1.586-2.82,3.211-5.071,5.013-7.241 l0.533,14.696c0.071,2.018,1.765,3.596,3.782,3.524s3.596-1.765,3.524-3.782l-0.85-23.419c-0.071-2.019-1.765-3.596-3.782-3.525 c-0.126,0.005-0.25,0.016-0.372,0.032v-0.003L3.157,16.715c-2.001,0.277-3.399,2.125-3.122,4.126 c0.276,2.002,2.124,3.4,4.126,3.123L16.682,22.204L16.682,22.204L16.682,22.204z M53.899,54.363h20.963v-8.834 c0-2.883-1.18-5.504-3.077-7.403l-0.002,0.001c-1.899-1.899-4.521-3.08-7.402-3.08c-2.883,0-5.504,1.18-7.404,3.078 c-1.898,1.899-3.077,4.521-3.077,7.404V54.363L53.899,54.363L53.899,54.363z M64.465,69.795l2.116,9.764l-5.799,0.024l1.701-9.895 c-1.584-0.509-2.733-1.993-2.733-3.747c0-2.171,1.76-3.931,3.932-3.931c2.17,0,3.931,1.76,3.931,3.931 C67.612,67.845,66.261,69.433,64.465,69.795L64.465,69.795L64.465,69.795z"/></g></svg>
							</div>
							<div class="w-100 w-xl-50 d-grid gap-4">

								<div <?php echo UsaTmpPass ($sesion->getNumNomina ()) ? 'hidden' : '' ?>>
									<div>
										<h5 class="mb-3 resaltar">Contraseña actual:</h5>
										<input oninput="ActualizarEntrada()" id="pass-actual" type="password" class="form-control mb-4" placeholder="Ingrese la contraseña actual..."/>
									</div>
									<hr class="my-2" >
								</div>

								<div>
									<h5 class="mb-3">Nueva contraseña:</h5>
									<input oninput="ActualizarEntrada()" id="nuevo-pass" type="password" class="form-control mb-2" placeholder="Ingrese la nueva contraseña..."/>
									<small class="text-body-tertiary text-break">La contraseña debe contener al menos 8 caracteres, y debe contener letras, números y/o caracteres especiales.</small>
								</div>
								<div>
									<h5 class="mb-3">Confirmar nueva contraseña:</h5>
									<input oninput="ActualizarEntrada()" id="confirmar-pass" type="password" class="form-control" placeholder="Ingrese nuevamente la nueva contraseña..."/>
								</div>
								<button id="btn-cambiar" class="mt-3 ms-xl-auto px-lg-5 btn btn-svg" data-tipo="btn-accion" style="color: var(--light); background-color: var(--entidad-primario);" onclick="CambiarPass()" disabled>
									<span title="Cambiar contraseña">Cambiar</span>
									<svg class="ms-1 my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#FFF" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160 352 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l111.5 0c0 0 0 0 0 0l.4 0c17.7 0 32-14.3 32-32l0-112c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1L16 432c0 17.7 14.3 32 32 32s32-14.3 32-32l0-35.1 17.6 17.5c0 0 0 0 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.8c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352l34.4 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L48.4 288c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"/></svg>
								</button>
							</div>
							
						</div>
					</div>
					
				</div>
			</div>
			<hr class="my-4 border-0">
		</article>
	</body>
	<script src="js/plantillas.js"></script>
	<script src="js/validacion_in.js"></script>
	<script src="js/nuevo_usuario.js"></script>
	<script>	
		let $pass_actual =	  null;
		let $nuevo_pass = 	  null;
		let $confirmar_pass = null;
		let $btn_cambiar = 	  null;
		
		function ActualizarEntrada () { 
			$pass_actual = 		document.querySelector ('#pass-actual:not(div[hidden] #pass-actual');
			$nuevo_pass = 		document.querySelector ('#nuevo-pass');
			$confirmar_pass = 	document.querySelector ('#confirmar-pass');
			$btn_cambiar = 		document.querySelector ('#btn-cambiar');
		
			if (($pass_actual && $pass_actual.value == '') || 
				$nuevo_pass.value == '' || 
				$confirmar_pass.value == '') 
			{
				// No se han llenado todos los campos
				if (!$btn_cambiar.disabled) $btn_cambiar.disabled = true;
				return;
			}
			// Tods los campos han sido llenados
			if ($btn_cambiar.disabled) $btn_cambiar.disabled = false;
		}
		
		async function CambiarPass () {
			
			const validarPassActual = async (pass) => {
				
				const formData = new FormData ();
				formData.append ('validar_pass', pass);
				formData.append ('cambio_pass', true)
				
				// Validar contraseña en la Base de Datos
				return  await fetch ('modulos/GestionUsuarios.php', {
					method: 'POST',
					body: formData
				})
				.then ((resp) => resp.text())
				.then ((valido) => {
					// Devolver resultado de la validación
					return valido === 'true' ? true : false;
				});
			};
			
			const cambiarPass = async (nuevo_pass) => {

				const formData = new FormData ();
				formData.append ('cambiar_pass', nuevo_pass);
				
				// Enviar petición para cambiar la contraseña
				await fetch ('modulos/GestionUsuarios.php', {
					method: 'POST',
					body: formData
				})
				.then ((resp) => resp.text())
				.then ((realizado = realizado === 'true' ? true : false) => {

					const resultado = realizado ? 
					'La contraseña fue cambiada correctamente.' : 
					'No fue posible cambiar la contraseña en este momento.' + 
					'Por favor, inténtalo más tarde.';
					
					const accion = realizado ? 
						  () => window.location.replace ('inicio.php') : 
						  null;
					
					// Resultado del cambio de contraseña
					MostrarMensaje (
						'Cambio de contraseña', 
						resultado, accion, 
						realizado ? icono_success : icono_error, 
						false, false
					);
				});
			};
			
			const pass_valido = 	  $pass_actual ? await validarPassActual ($pass_actual.value) : true;
			const nuevo_pass_valido = ValidarPass ($nuevo_pass.value, $confirmar_pass.value);
			
			if (!pass_valido) {
				MostrarMensaje 	 (
					"No es posible cambiar la contraseña", 
					"La contraseña actual es incorrecta.", 
					null, icono_error
				);
				return;
			}
			
			switch (nuevo_pass_valido) {
				// Validar campos de nueva contraseña
				case 0: MostrarMensaje 	 ("No es posible cambiar la contraseña", mensajes ['error_1'], null, icono_error); break;
				case 1: MostrarMensaje 	 ("No es posible cambiar la contraseña", mensajes ['error_2'], null, icono_error); break;
				case 2: cambiarPass  	 ($nuevo_pass.value); break;
			}
		}
	</script>
	<script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/accesibilidad.js"></script>

</html>