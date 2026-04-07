<?php 
require_once ('modulos/GestionUsuarios.php'); 
ValidarSesion (); 
ValidarAdmin ($sesion->getTipoUsuario ());
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Crear nuevo curso externo</title>
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
				data-titulo="Crear nuevo curso externo"
				data-color="dark"
			>
				<div data-tipo="contenido">
					<div class="personalizado my-4 d-flex flex-column flex-xl-row my-5">
						<div class="d-flex">
							<svg style="max-width: 300px" class="mx-auto w-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  fill="none"  stroke="#CCC"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-certificate"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5" /><path d="M6 14m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M4.5 17l-1.5 5l3 -1.5l3 1.5l-1.5 -5" /></svg>
						</div>
						<form onsubmit="ValidarFormulario (event)" method="post" id="formulario-crear" class="w-100 d-flex flex-column">
							<h3 class="resaltar">Información del curso</h3>
							<hr class="my-3 border-0">
							<div class="form-floating">
								<input id="nombre-curso" type="text" class="form-control" placeholder="Ingrese el nombre del curso externo..." oninput="ValidarNombreCurso(event, this)" autocomplete="off"/>
								<label for="nombre-curso">Nombre del curso externo</label>
							</div>
							<hr class="my-2 border-0">
							<div class="form-floating">
								<input id="descripcion-curso" type="text" class="form-control" placeholder="Ingrese el nombre del curso externo..." oninput="ValidarNombreCurso(event, this)" autocomplete="off"/>
								<label for="descripcion-curso">Descripción del curso</label>
							</div>
							<hr class="my-3 border-0">
							<div>
								<h6>Fecha del curso:</h6>
								<input id="fecha" type="date" class="form-control" />
							</div>
							<hr class="my-2 border-0">
							<div>
								<h6>¿El curso tiene vigencia?</h6>
								<select id="selector-vigencia" class="form-select" onchange="HabilitarVigencia (this)">
									<option value="0" selected>No</option>
									<option value="1">Si</option>
								</select>
							</div>
							<hr class="my-2 border-0">
							<div id="info-vigencia" class="collapse multi-collapse">
								<h6>Vigencia:</h6>
								<div class="d-flex">
									<input id="vigencia-num" type="number" class="form-control me-3" value="1" min="1" max="50"/>
									<select id="vigencia-unidad" class="form-select" aria-label="Default select example">
									  <option value="0" selected>Años</option>
									  <option value="1">Meses</option>
									  <option value="2">Días</option>
									</select>
								</div>
							</div>
							<hr class="my-2 border-0">
							<div class="d-flex flex-column w-100">
								<button id="btn-crear" type="submit" data-tipo="btn-accion" class="ms-md-auto px-5 py-3" disabled>
									<span>Crear curso</span>
									<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="#FFF" width="20"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>
								</button>
							</div>							
						</form>
					</div>
				</div>
			</div>
			<hr class="my-5" style="border-color: transparent">
		</article>
	</body>
	<script src="js/plantillas.js"></script>
	<script src="js/gestion_curso_externo.js"></script>
	<script src="js/tooltip.js"></script>
	<script src="js/validacion_in.js"></script>
	<script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/accesibilidad.js"></script>
	<script>
		let btn_crear = 		null;
		let collapse_vigencia = null;
		
		function ValidarNombreCurso (e, input) {
			if (!btn_crear) btn_crear = document.querySelector ('#btn-crear');
			
			// Habilitar botón al detectar nombre de curso externo
			btn_crear.disabled = (input.value == '') ; 
		}
		
		function ValidarFormulario (e) { 
			e.preventDefault();
			
			const formulario = 		 e.srcElement;
			const entradas = 		 Array.from(formulario.querySelectorAll ('input'));
			const error_validacion = ValidarMultiplesEntradas (entradas);
			
			if(error_validacion) {
				// Error en el llenado del formulario
				IndicarError (error_validacion ['input'], error_validacion ['error']);
				return;
			}
			
			// El formulario para la creación del curso es válido
			const nombre = 			formulario.querySelector ('#nombre-curso');
			const descripcion = 	formulario.querySelector ('#descripcion-curso');
			const fecha = 			formulario.querySelector ('#fecha');
			const vigencia_sel = 	formulario.querySelector ('#selector-vigencia');
			const vigencia_num = 	formulario.querySelector ('#vigencia-num');
			const vigencia_unidad = formulario.querySelector ('#vigencia-unidad');
			
			// Recuperar datos del curso por crear
			const datos_curso = {
				nombre:   		nombre.value,
				descripcion:	descripcion.value,
				fecha: 	  		fecha.value,
				tiene_vigencia: vigencia_sel.value,
				vigencia: 		vigencia_num.value,
				unidad:   		vigencia_unidad.value
			};
			
			MostrarMensaje (
				'Crear nuevo curso externo', 
				'¿Deseas continuar con la creación del curso externo "' + 
				nombre.value + '"?', 
				() => CrearCursoExterno (datos_curso), 
				icono_pregunta, true
			);
		}
		
		function HabilitarVigencia (selector) {
			if (!collapse_vigencia) collapse_vigencia = document.querySelector ('#info-vigencia');
			
			const bs_collapse_vigencia = new bootstrap.Collapse (
				collapse_vigencia, { toggle: false }
			);
			if (selector.value == 1) bs_collapse_vigencia.show ();
			else bs_collapse_vigencia.hide ();
		}
	</script>

</html>