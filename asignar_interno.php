<?php require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/util/BD.php');
ValidarSesion (); 
ValidarSesionInstAdmin ($sesion->getTipoUsuario ());

$fecha_actual = 	new DateTime ();
$fecha_min = 		new DateTime ();
$fecha_sugerida =	new DateTime ();
$fecha_limite = 	$fecha_actual->modify ('+13 months');
$fecha_sugerida = 	$fecha_sugerida->modify ('+3 weeks');

$BDentidad = 	new BDentidad ();
$areas = 	$BDentidad->ConsultarAreas ();
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="utf-8"/>
        <title>Asignar curso interno</title>
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
            data-titulo="Asignar curso interno"
            data-color="gray"
			class="mb-5">
                <span class="indicador-check dark" hidden>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="60"><path fill="#0F0" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
				</span>
                <div data-tipo="contenido">
                    <div class="personalizado my-5 d-flex">
						<h5 class="mb-0">¿Cómo desea asignar el curso?</h5>
						<select id="tipo-asignacion" class="form-select" aria-label="Default select example">
							<option value="departamento">Asignar por departamento</option>
							<option value="no_nomina">Asignar por nombre o número de nómina del empleado</option>
						</select>
						<div class="d-flex flex-column w-100">
							<button class="ms-md-auto btn px-5 py-3" onclick="SeleccionarEmpleados ()">
								Siguiente
								<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#fff" d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
							</button>
						</div>
                    </div>
                </div>
            </div>
			
			<div 
			id="asignacion-departamento"
            data-componente="ventana"
            data-titulo="Asignar curso por departamento"
            data-color="dark"
			class="collapse multi-collapse"
			style="transition: all ease .5s">
                <span class="indicador-check">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="60"><path fill="#0F0" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
				</span>
                <div data-tipo="contenido">

                    <div class="personalizado my-5">
						<h5 class="mb-0">Selecciona el departamento por asignar:</h5>
						<select id="seleccion-area" class="form-select" aria-label="Default select example">
						<?php 
						if ($areas) {
							echo '<option value="" selected disabled hidden>Seleccionar departamento...</option>';
							foreach ($areas as $area) 
								echo '<option value="'.$area['id_centro_gastos'].'">'.$area['area'].'</option>';
						} else echo '<option>No se pudieron recuperar las áreas</option>';
						?>
						</select>

						<div>
							<h5 class="text-center resaltar"><b>EMPLEADOS DEL ÁREA:</b></h5>
							<div 
							id="tabla-empleados" 
							data-componente="tabla-empleados-simple" 
							data-color="gray"
							data-msg_placeholder="No hay empleados por asignar"
							data-colspan="4"
							class="fade-scroll"></div>
						</div>
						
						<div>
							<h5 class="text-center resaltar"><b>EMPLEADOS EXCLUIDOS:</b></h5>
							<div 
							id="tabla-excluidos" 
							data-componente="tabla-empleados-simple" 
							data-color="dark"
							data-msg_placeholder="No has excluido a ningún empleado"
							data-colspan="4"
							class="fade-scroll"></div>
						</div>
						
						<div class="d-flex flex-column w-100">
							<button class="ms-md-auto px-5 py-2 btn" onclick="SeleccionarCurso ('departamento')">
								Seleccionar curso
								<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#fff" d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
							</button>
						</div>
                    </div>
                </div>
            </div>

            <div 
			id="asignacion-nomina"
            data-componente="ventana"
            data-titulo="Asignar curso por nombre o número de nomina"
            data-color="dark"
			class="collapse multi-collapse"
			style="transition: all ease .5s">
                <span class="indicador-check dark" hidden>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="60"><path fill="#0F0" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
				</span>
                <div data-tipo="contenido">

                    <div class="personalizado my-5">
                        <h5 class="mb-0 mt-0">Buscar empleado:</h5>
						<div class="d-flex">
						<input id="input-busqueda" type="text" class="form-control rounded-start" placeholder="Ingresa el nombre o número de nómina..."/>
                        <button id="buscar-nomina" data-tipo="btn-accion" onclick="BuscarEmpleadoNomina (this)" disabled class="btn btn-busqueda btn-svg d-flex rounded-end w-fit" type="submit" title="Buscar empleado">
							Buscar
							<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#fff" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
						</button>
						</div>
						<div>
							<h5 class="text-center mt-4 resaltar"><b>RESULTADO DE LA BÚSQUEDA:</b></h5>
							<div 
								id="resultado-empleado" 
								data-componente="tabla-empleados-simple" 
								data-color="dark" 
								data-msg_placeholder="No hay resultados" 
								data-colspan="4"
								class="fade-scroll">
							</div>
						</div>
						
						<div>
                        <h5 class="text-center resaltar"><b>EMPLEADOS POR ASIGNAR:</b></h5>
							<div 
								id="empleados-agregados" 
								data-componente="tabla-empleados-simple" 
								data-color="gray" 
								data-msg_placeholder="No hay ningún empleado asignado" 
								data-colspan="4"
								class="fade-scroll">
							</div>
						</div>
						<div class="d-flex flex-column w-100">
							<button class="ms-md-auto btn px-5 py-2" onclick="SeleccionarCurso ('nomina')">
								Seleccionar curso
								<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#fff" d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
							</button>
						</div>
                    </div>
                </div>
            </div>
			
			<hr class="mt-5 border-0">
			
            <div 
			id="seleccion-curso"
            data-componente="bloque"
            data-titulo="Buscar curso para asignar:"
			class="collapse multi-collapse pb-3">
                <span class="indicador-check dark" hidden>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="60"><path fill="#0F0" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
				</span>
                <div data-tipo="contenido">
					
                    <div class="personalizado" style="overflow: hidden;">
					
						<div <?php echo $sesion->getTipoUsuario () == 'admin' ? 'hidden' : '' ?>>
						<div class="alert alert-primary d-flex align-items-center px-4" role="alert">
							<svg class="me-3 mb-auto mt-2" style="float: left" fill="currentColor" viewBox="0 0 16 16" width="40">
								<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
							</svg>
							<div>
								<div class="text-break"><b>Nota. </b>Recuerda que únicamente podrás asignar empleados a los cursos internos creados por tí, de manera que se realizará la búsqueda en tus cursos.</div>
							</div>
						</div>
						</div>
					
                        <div class="d-flex">
						<input id="busqueda-curso" type="text" class="form-control rounded-start" placeholder="Buscar por nombre o fecha del curso..."/>
                        <button id="btn-buscar-curso" class="btn btn-busqueda btn-svg d-flex rounded-end w-fit" data-tipo="btn-accion" type="submit" title="Buscar curso" onclick="BuscarCurso ('<?php echo $sesion->getTipoUsuario () ?>', <?php echo $sesion->getNumNomina () ?>, this)" disabled>
							Buscar
							<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#fff" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
						</button>
						</div>
						
						<div>
							<h5 class="text-center resaltar"><b>RESULTADOS DE LA BÚSQUEDA:</b></h5>
							<div 
							id="resultado-curso" 
							data-componente="tabla-admin-cursos-i" 
							data-color="gray"
							data-msg_placeholder="No hay resultados por seleccionar"
							data-colspan="5"
							class="fade-scroll">
								<div 
									data-componente="curso-asignar" 
									data-color="dark" 
									data-msg_placeholder="No se ha recuperado nincún curso" 
									data-colspan="5">
								</div>
							</div>
						</div>
						
						<div>
							<h5 class="text-center resaltar"><b>CURSOS POR ASIGNAR:</b></h5>
							<div 
							id="cursos-seleccion" 
							data-componente="tabla-admin-cursos-i" 
							data-color="dark"
							data-msg_placeholder="No se ha seleccionado ningún curso"
							data-colspan="5"
							class="fade-scroll">
								<div 
									data-componente="curso-asignar" 
									data-color="dark" 
									data-msg_placeholder="No se ha recuperado nincún curso" 
									data-colspan="5">
								</div>
							</div>
						</div>
						<div>
							<fieldset>
								<legend>Fecha límite para tomar el curso</legend>
								<div>
									<select id="limite-curso" class="form-select" onchange="SeleccionarFechaLimite (this)">
										<option value="false">Sin fecha límite</option>
										<option value="true">Definir fecha límite</option>
									</select>
								</div>
								<div id="collapse-fecha-lim" class="collapse">
									<hr class="my-4">
									<h6>Fecha límite:</h6>
									<input
									id="fecha-limite" 
									class="form-control" 
									min="<?php echo $fecha_min->format('Y-m-d'); ?>"
									max="<?php echo $fecha_limite->format('Y-m-d'); ?>" 
									value="<?php echo $fecha_sugerida->format('Y-m-d'); ?>" 
									type="date">
								</div>
							</fieldset>
						</div>
						<div class="d-flex flex-column w-100">
							<button class="ms-md-auto btn px-5 py-2" onclick="RealizarAsignacion ()">
								Realizar asignación
								<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#fff" d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
							</button>
						</div>
                    </div>
					
                </div>
            </div>
			
			<hr class="mt-5 border-0">
			
        </article>
    </body>
	<script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>
    <script src="js/plantillas.js"></script>
	<script src="js/tablas.js"></script>
	<script src="js/scroll.js"></script>
	<script src="js/email.js"></script>
	<script src="js/tooltip.js"></script>
	<script src="js/validacion_in.js"></script>
	<script src="js/accesibilidad.js"></script>
	<script>
	let collapse_fecha_lim =	  null;
	const articulo = 			  document.querySelector 	('article');
	const collapse_departamento = document.getElementById 	('asignacion-departamento');
	const collapse_nomina = 	  document.getElementById 	('asignacion-nomina');
	const collapse_curso = 		  document.getElementById 	('seleccion-curso');
	const indicadores = 		  document.querySelectorAll ('.indicador-check');
	
	const obtenerCursosSeleccionados = () => {
		// functión para obtener cursos seleccionados
		return Array.from (
			document.querySelectorAll (
				'#cursos-seleccion tbody tr:not([data-elemento="placeholder"])'
			)
		).map ( seleccion => seleccion.dataset.id_curso );
	};
	
	const obtenerEmpleadosSeleccionados = () => {
		return Array.from (
			document.querySelectorAll (
				'#empleados-agregados tr:not(.gray, [data-elemento="placeholder"]) ' + 
				'b[data-tipo="id-nomina"]'
			)
		).map ( seleccion => seleccion.innerHTML );
	};
	
	// Altura inicial del artículo
	articulo.style.height = '100vh';
	
	let empleados_asignados = 	null;
	let curso_por_asignar = 	null;
	let tipo_asignacion = 		null;
	let input_nomina =			null;
	let btn_buscar_nomina =		null;
	let bs_collapse_fecha_lim = null;

	const bs_collapse_departamento = new bootstrap.Collapse (
		collapse_departamento, { toggle: false }
	);
	const bs_collapse_nomina = new bootstrap.Collapse (
		collapse_nomina, { toggle: false }
	);
	const bs_collapse_curso = new bootstrap.Collapse (
		collapse_curso, { toggle: false	}
	);
	
	function SeleccionarFechaLimite (selector) {
		if (!collapse_fecha_lim) {
			collapse_fecha_lim = document.getElementById	('collapse-fecha-lim');
			bs_collapse_fecha_lim = new bootstrap.Collapse (
				collapse_fecha_lim, { toggle: false }
			);
		}
		if (selector.value == "false") {
			bs_collapse_fecha_lim.hide ();
			return;
		}
		bs_collapse_fecha_lim.show ();
	}
	
	async function BuscarEmpleadoNomina (boton) {
		
		let   empleado_repetido =	false;
		let   btn_rollback = null;
		const criterio_busqueda =	document.querySelector ('#input-busqueda');
		
		if (!criterio_busqueda) return;
		
		// Obtener números de nómina de los empleados seleccionados
		const empleados_seleccionados = obtenerEmpleadosSeleccionados ();

		// Deshabilitar temporalmente el botón de búsqueda
		btn_rollback = btnModoCarga (boton);
		
		// Realizar búsqueda de empleados en la Base de Datos
		await fetch ('modulos/GestionUsuarios.php?buscar_empleados=' + criterio_busqueda.value, {
			method: 'GET'
		})
		.then ((resp) => resp.json ())
		.then ((empleados) => {
			
			const $tabla = document.querySelector   ('#resultado-empleado tbody');
			const mensajeEnTabla = (mensaje) => {
				$tabla.innerHTML = 
				'<tr data-elemento="placeholder">'+
					'<td colspan="4" class="text-center">' + 
						'<h6>' + mensaje + '</h6>' + 
					'</td>' +
				'</tr>';
			};
			$tabla.innerHTML = '';
			
			if (!empleados.length) {
				mensajeEnTabla ('No se encontró ningún resultado'); 
				return;
			}
			
			// Filtrar empleados que ya se encuentren seleccionados
			const empleados_filtrados = 
				  empleados_seleccionados.length ? 
				  empleados.filter (
					empleado => !empleados_seleccionados.includes (empleado ['no_nomina'])
				  ) : empleados;
				  
			if (!empleados_filtrados.length) {
				// No hay resultados por mostrar de la búsqueda
				mensajeEnTabla ('Éste empleado, o empleados, ya han sido agregados');
				return;
			}
			
			// Mostrar como resultado únicamente los empleados que aún no ha sido seleccionados
			empleados_filtrados.forEach ((empleado) => {
				
				const registro = 	 $temp_empleado_asignar.content.cloneNode (true);
				const id_nomina =  	 registro.querySelector   ('b[data-tipo="id-nomina"]');
				const nombre = 	   	 registro.querySelector   ('td[data-tipo="nombre-empleado"]');
				const departamento = registro.querySelector   ('td[data-tipo="departamento"]');
				const btn_accion = 	 registro.querySelector   ('button');
				const icono_boton =  btn_accion.querySelector ('svg');
				
				id_nomina.innerHTML = 	  empleado ['no_nomina'];
				nombre.innerHTML = 	  	  empleado ['nombre'];
				departamento.innerHTML =  empleado ['area'];
				btn_accion.dataset.tipo = 'no-reversible';
				icono_boton.innerHTML =
				'<path fill="#569c38" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>';
				$tabla.appendChild (registro);
			});
		})
		.catch ((error) => console.error(error))
		.finally (() => { btnModoNormal (boton, btn_rollback); });
	}
	
	function SeleccionarEmpleados () {
		
		tipo_asignacion = 	document.querySelector ('#tipo-asignacion');
		input_nomina = 		document.querySelector ('#input-busqueda');
		btn_buscar_nomina = document.querySelector ('#buscar-nomina');
		
		const asignacionDepartamento = () => {
			// Ocultar indicadores "check"
			indicadores[1].hidden = true;
			indicadores[2].hidden = true;
			indicadores[3].hidden = true;
			
			// Mostrar ventana para seleccionar empleados
			bs_collapse_nomina.hide ();
			bs_collapse_departamento.show ();
			bs_collapse_curso.hide ();
			
			collapse_departamento.style.opacity = 1;
			scrollAElemento (collapse_departamento, 500);
		};
		
		const asignacionNomina = () => {
			// Ocultar indicadores "check"
			indicadores[1].hidden = true;
			indicadores[2].hidden = true;
			indicadores[3].hidden = true;
			
			// Mostrar ventana para seleccionar empleados
			bs_collapse_departamento.hide ();
			bs_collapse_nomina.show ();
			bs_collapse_curso.hide ();
			
			collapse_nomina.style.opacity = 1;
			scrollAElemento (collapse_nomina, 500);
		};
				
		switch (tipo_asignacion.value) {
			case 'departamento':	// Selección de empleados por departamento
				setTimeout (() => asignacionDepartamento (), indicadores [0].hidden ? 500 : 0); 
			break;
			case 'no_nomina': 		// Selección de empleados por número de nómina 
				setTimeout (() => asignacionNomina (), indicadores [0].hidden ? 500 : 0); 
			break;
		}
		
		indicadores [0].hidden = 			  false;
		collapse_departamento.style.opacity = 0;
		collapse_nomina.style.opacity = 	  0;
		articulo.style.height = 			 'initial';
		
		/*
			Actualizar automáticamente la ventana de selección 
			de empleados al cambiar el tipo de asignación.
		*/
		tipo_asignacion.onchange = () => SeleccionarEmpleados ();
		input_nomina.oninput = () => {
			if (input_nomina.value === '') btn_buscar_nomina.disabled = true;
			else btn_buscar_nomina.disabled = false;
		};
		
		// Configurar elementos (tablas) con fade dinámico 
		ConfigurarFadeScroll ();
	}
	
	async function BuscarCurso (tipo_usuario, no_nomina, boton) {
		const criterio = document.querySelector ('#busqueda-curso').value;

		// Deshabilitar temporalmente el botón de búsqueda
		let btn_rollback = btnModoCarga (boton);
		
		// Realizar la búsqueda del curso mediante el criterio de búsqueda ingresado
		await fetch (
			'modulos/CursosInternos.php?busqueda=' + criterio + 
			'&tipo=' + tipo_usuario + 
			'&no_nomina=' + no_nomina, {
			method: 'GET'
		})
		.then ((resp) => resp.json())
		.then ((resultados) => {

			// Obtener Id's de los cursos por asignar seleccionados
			const $cursos_seleccion = obtenerCursosSeleccionados ();
			
			// Tabla con los resultados de la búsqueda
			const $tabla_resultados = document.querySelector (
				'div[data-componente="tabla-admin-cursos-i"] tbody'
			); 
			$tabla_resultados.innerHTML = '';
			
			// Filtrar los resultados de la búsqueda para no mostrar los cursos ya seleccionados
			const resultados_filtrados = 
				  $cursos_seleccion.length ? 
				  resultados.filter (
					resultado => !$cursos_seleccion.includes (resultado ['Id_curso'])
				  ) : resultados;
			
			const mensajeEnTabla = (msg) => {
				$tabla_resultados.innerHTML = 
				'<tr>' + 
					'<td colspan="5">' + 
						'<h6 class="text-center">' + msg + '<h6>' + 
					'</td>' + 
				'</tr>';
			};
			
			if (!resultados_filtrados.length) {
				mensajeEnTabla ('No se encontraron resultados'); return;
			}
			
			resultados_filtrados.forEach ((curso) => {
				
				// Mostrar resultados de la búsqueda
				const $resultado = 	$temp_curso_asignar.content.cloneNode (true);
				const $nombre = 	$resultado.querySelector ('[data-info="nombre-curso"]');
				const $usuario = 	$resultado.querySelector ('[data-info="usuario"]');
				const $version = 	$resultado.querySelector ('[data-info="version"]');
				const $fecha = 		$resultado.querySelector ('[data-info="fecha"]');
				const $btn_ver = 	$resultado.querySelector ('[data-info="visualizar"]');
				const $tr =			$resultado.querySelector ('tr');
				
				$btn_ver.onclick = () => {
					
					// Botón para previsualizar el curso
					window.open (
						'visualizador.php?curso_int=' + curso ['Id_curso'] + 
						'&visualizar=true', '_blank'
					).focus();
				}
				$tr.dataset.id_curso =	curso ['Id_curso'];
				$nombre.textContent =	curso ['Nombre'];
				$usuario.textContent = 	curso ['nombre'];
				$version.textContent =	curso ['Version'];
				$fecha.textContent =	curso ['Fecha'];

				// Agregar descripción de columnas
				$nombre.setAttribute ('title', curso ['Nombre']);
				$usuario.setAttribute ('title', curso ['nombre']);
				
				// Mostrar resultados en la tabla
				$tabla_resultados.appendChild ($resultado);
			});
			
		})
		.catch ((error) => console.error(error))
		.finally (() => { btnModoNormal (boton, btn_rollback); });
	}
	
	function SeleccionarCurso (tipo_seleccion) {
		
		const $input_buscar_curso = document.querySelector ('#busqueda-curso');
		const $btn_buscar_curso = 	document.querySelector ('#btn-buscar-curso');
		
		const seleccionarCurso = () => {
			// Mostrar ventana para la selección del curso por asignar
			bs_collapse_curso.show();
			collapse_curso.style.opacity = 1;
			collapse_curso.addEventListener ('shown.bs.collapse', () => {
				collapse_curso.scrollIntoView ({ behavior: "smooth", block: "center"})
			});
		};
		// Obtener empleados por asignar mediante el departamento
		const obtenerTablaDepartamento = () => {
			empleados_asignados = document.querySelectorAll (
				'#tabla-empleados tr:not(.gray, [data-elemento="placeholder"])'
			);
		};
		// Obtener empleados por asignar mediante selección (número de nómina)
		const obtenerTablaSeleccion = () => {
			empleados_asignados = document.querySelectorAll (
				'#empleados-agregados tr:not(.gray, [data-elemento="placeholder"])'
			);
		};
		
		switch (tipo_seleccion) {
			// Obtener empleados mediante el método seleccionado
			case 'departamento': obtenerTablaDepartamento (); break;
			case 'nomina': 		 obtenerTablaSeleccion (); 	  break;
		}
		
		if (empleados_asignados.length === 0) {
			MostrarMensaje (
				'Seleccionar empleados', 
				'No se han agregado empleados a la asignación del curso.', 
				null, icono_error, false
			); return;
		}
		
		$input_buscar_curso.oninput = () => {
			// Desabilitar botón de búsqueda al no haber ningún criterio de búsqueda
			if ($input_buscar_curso.value === '') {
				$btn_buscar_curso.disabled = true;
				return;
			}
			$btn_buscar_curso.disabled = false;
		};
		
		// Mostrar indicador "check"
		indicadores[1].hidden = false;
		indicadores[2].hidden = false;
		
		// Mostrar ventana para seleccionar curso
		setTimeout (() => seleccionarCurso (), 500);
	}
	
	function RealizarAsignacion () {

		const obtenerVersionesCursos = (cursos) => {
			let versiones = [];
			cursos.forEach ((curso) => {
				versiones.push (
					document.querySelector (
						'#cursos-seleccion tbody tr[data-id_curso="' + curso + '"] ' + 
						' td[data-info="version"]'
					).textContent
				);
			}); 
			return versiones;
		};
		
		const realizarAsignacion = async () => {
		
			const selector_asignacion = 	 	document.querySelector ('#tipo-asignacion');
			const cursos_seleccionados = 	 	obtenerCursosSeleccionados ();
			let	  empleados_seleccionados = 	null;
			let   id_empleados_seleccionados = 	null;
			let   versiones_cursos  = 			obtenerVersionesCursos (cursos_seleccionados);
			
			// Campo de fecha límite
			const selector_fecha_limite = document.querySelector ('#limite-curso');
			const fecha_limite = selector_fecha_limite.value == 'true' ? 
								 document.querySelector ('#fecha-limite') :
								 false;
			const fecha_invalida = FechaFueraDeRango (fecha_limite);

			if (fecha_limite && fecha_invalida) {
				// Validar fecha límite para tomar el curso
				const ultimo_tooltip = fecha_limite.parentElement.querySelector ('.tooltip-input');
				const nuevo_error = ultimo_tooltip ? true : false;
				IndicarError (
					fecha_limite, 
					fecha_invalida.error,
					null,
					nuevo_error,
					ultimo_tooltip
				);
				return;
			}
			
			const obtenerSeleccionEmpleados = (tabla) => {
				// Recuperar lista de nombres de los empleados seleccionados
				empleados_seleccionados = Array.from (
					document.querySelectorAll ('#' + tabla + ' [data-tipo="nombre-empleado"]')
				).map (elemento => elemento.textContent);
				
				// Recuperar lista de números de nómina de los empleados seleccionados
				id_empleados_seleccionados = Array.from (
					document.querySelectorAll ('#' + tabla + ' [data-tipo="id-nomina"]')
				).map (elemento => elemento.textContent);
			};

			const realizarAsignacionBD = async () => {
				const formData = new FormData ();
				formData.append ('asignar_ci_emp', id_empleados_seleccionados);
				formData.append ('cursos_i', cursos_seleccionados);
				formData.append ('versiones', versiones_cursos);
				formData.append ('fecha_limite', fecha_limite ? fecha_limite.value : '');
				formData.append ('asignacion', <?php echo $sesion->getNumNomina () ?>);
				
				// Registrar asignación en la Base de Datos
				await fetch ('modulos/GestionUsuarios.php', {
					method: 'POST',
					body: formData
				})
				.then ((resp) => resp.json ())
				.then ((resultado) => {
					// Notificar usuarios
					NotificarUsuarios (resultado ['correos'], resultado ['cursos']);

					// Mensaje con el resultado de la asignación
					MostrarMensaje (
						'Asignación de curso', 
						resultado ['msg'], 
						resultado ['error'] == 'true' ? null : () => window.location.reload (), 
						resultado ['error'] == 'true' ? icono_error : icono_success
					);
				})
				.catch ((error) => console.error (error));
			};

			const FechaATexto = (fecha_str) => {
				const cadena_fecha = new Date (fecha_str + 'T00:00:00-06:00');
				const meses = [
					'enero', 'febrero', 
					'marzo', 'abril', 
					'mayo', 'junio', 
					'julio', 'agosto', 
					'septiembre', 'octubre', 
					'noviembre', 'diciembre'
				];
				const dia = cadena_fecha.getDate ();
				const mes = meses [cadena_fecha.getMonth ()];
				const anno = cadena_fecha.getFullYear ();
	
				return dia + ' de ' + mes + ' de ' + anno;
			};
			
			let cursos_asignar = 	  '';
			let empleados_asignar =   '';
			let id_cursos = 		  [];
			let empleados_no_nomina = [];
			
			switch (selector_asignacion.value) {
				// Recuperar empleados seleccionados, de acuerdo con el método de selección
				case 'departamento': obtenerSeleccionEmpleados ('tabla-empleados'); 	break;
				case 'no_nomina': 	 obtenerSeleccionEmpleados ('empleados-agregados'); break;
			}
			
			cursos_seleccionados.forEach ((curso) => {
				const string_curso = document.querySelector (
					'tr[data-id_curso="' + curso + '"] b[data-info="nombre-curso"]'
				);
				cursos_asignar += '<li>' + string_curso.textContent + '</li>';
			});
			
			empleados_seleccionados.forEach ((empleado) => {
				empleados_asignar += '<li>' + empleado + '</li>';
			});
			
			if (!cursos_seleccionados.length) {
				MostrarMensaje (
					'Selección de curso', 
					'Todavía no se ha seleccionado ningún curso por asignar.', 
					null, icono_error, false
				); 	return;
			}
			
			// Mostrar indicador "check"
			indicadores[3].hidden = false;

			const confirmacion_fecha = 	
				fecha_limite ? 
				'<div>' +
					'<p><b>La fecha límite será:</b></p><p>' + FechaATexto (fecha_limite.value) + '</p>' +
				'</div>' : '';
				
			MostrarMensaje (
				'Realizar asignación', 
				'<p><b>Se asignarán los cursos: </b></p><p><ul class="fade-scroll">' + cursos_asignar + '</ul></p>' +
				'<p class="mt-3"><b>A los siguientes empleados:</b></p><ul class="fade-scroll">' + empleados_asignar + '</ul>' +
				confirmacion_fecha +
				'<p class="mb-0"><b>¿Deseas continuar?</b></p>', 
				realizarAsignacionBD, icono_pregunta, true, true, true
			);
		};
		
		// Realizar asignación de empleados a curso
		setTimeout (() => realizarAsignacion (), 500);
	}
	</script>

</html>