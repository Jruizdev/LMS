<?php 
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/AsignacionCursos.php'); 
ValidarSesion (); 
ValidarAdmin ($sesion->getTipoUsuario ());
?>
<!DOCTYPE>
<html lang="es">
	<head>
		<meta charset="utf-8"/>
		<title>Asginar curso externo</title>
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

			<div class="alerta">
				<div class="alert alert-primary w-100 d-flex align-items-center px-4" role="alert" style="animation: add-btn-in 1s">
					<svg class="me-3" style="float: left" fill="currentColor" viewBox="0 0 16 16" width="40">
						<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
					</svg>
					<div>
						<div class="text-break">Desde esta sección podrás registrar los empleados que hayan tomado cursos externos. Para ello, recuerda que deberás contar con el archivo de la certificación en formato PDF.</div>
					</div>
				</div>
			</div>

			<div 
			data-componente="ventana"
			data-titulo="Asignar curso externo"
			data-color="gray"
			class="position-relative">
				<span class="indicador-check dark my-0" style="top: 50%" hidden>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="60"><path fill="#0F0" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
				</span>
				<div data-tipo="contenido">
					<div class="personalizado pb-5 pt-3 gap-0" style="overflow-x: auto;">
						<div>
							<h5>Buscar empleado por asignar:</h5>
							<div class="d-flex flex-wrap w-100">
								<div class="form-floating h-100 m-0 flex-grow-1" style="overflow: hidden;">
									<input id="buscar-nomina" type="text" class="form-control" style="border-radius: 5px 0 0 5px" placeholder="Buscar por número de nómina..." oninput="HabilitarBtnBusqueda (this)"/>
									<label for="buscar-nomina">Ingresa el nombre o número de nómina</label>
								</div>
								<button id="btn-buscar" class="btn btn-secundario btn-svg px-5 py-3 flex-grow-1 flex-sm-grow-0" style="border-radius: 0 5px 5px 0" data-tipo="btn-accion" onclick="BuscarEmpleado (this)" disabled>
									<span>Buscar</span>
									<svg class="my-auto ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#FFF" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
								</button>
							</div>
						</div>
						<div id="resultado-busqueda-empleado" class="collapse multi-collapse pt-3">
							<div>
								<h5 class="text-center mt-4 resaltar"><b>RESULTADO DE LA BÚSQUEDA:</b></h5>
								<div 
									id="resultado-empleado" 
									data-componente="tabla-empleados-simple" 
									data-color="dark" 
									data-msg_placeholder="No hay resultados" 
									data-colspan="4"
									class="fade-scroll"
									style="max-height: 50vh !important">
								</div>
							</div>
						</div>
						<div id="empleado-seleccionado" class="collapse multi-collapse">
							<hr class="border-0 my-3">
							<div class="d-flex flex-column position-relative" style="border: 1px solid #F00; border-radius: 10px;">
								<button class="p-3" style="position: absolute; right: 0; background-color: transparent;" onclick="EliminarSeleccionEmpleado ()">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20"><path fill="#F00" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
								</button>
								<h5 class="w-100 text-center py-3 resaltar">EMPLEADO SELECCIONADO</h5>
								<div class="px-5 py-3 d-flex flex-column">
									<div class="d-flex"><p class="w-25"><b>No. nómina:</b></p><p data-info="no-nomina">0000</p></div>
									<div class="d-flex"><p class="w-25"><b>Nombre:</b></p><p data-info="nombre-empleado">Nombre del empleado</p></div>
									<div class="d-flex"><p class="w-25"><b>Departamento:</b></p><p data-info="departamento">Departamento</p></div>
									<div class="d-flex">
										<button class="btn px-5 py-3 my-4 flex-grow-1 flex-md-grow-0 ms-md-auto" onclick="EspecificarCursoExterno ()">
											Siguiente
											<svg xmlns="http://www.w3.org/2000/svg" class="ms-2" viewBox="0 0 448 512" width="25px"><path fill="#ffffff" d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr class="border-0 my-3">

			<div
			id="info-curso"
			data-componente="bloque"
			data-titulo="ACERCA DE LA CERTIFICACIÓN:"
			data-color="dark"
			class="collapse multi-collapse">
				<div data-tipo="contenido">
					<div class="personalizado">

						<div class="alerta">
							<div class="alert alert-primary w-100 d-flex align-items-center px-4" role="alert">
								<svg class="me-3" style="float: left" fill="currentColor" viewBox="0 0 16 16" width="40">
									<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
								</svg>
								<div>
									<div class="text-break">Considera que para asignar un curso externo, este deberá estar previamente creado y disponible en la plataforma. Si aún no has creado el curso externo, puedes crearlo dando <a href="crear_externo.php" target="_blank" style="font-weight: bold; text-decoration: none; color: #052c65;">aquí</a>.</div>
								</div>
							</div>
						</div>
						
						<div class="d-grid">
							<div>
								<h5>Buscar curso externo:</h5>
								<div class="d-flex flex-wrap w-100">
									<div class="form-floating h-100 m-0 flex-grow-1" style="overflow: hidden;">
										<input id="buscar-curso" type="text" class="form-control" style="border-radius: 5px 0 0 5px" placeholder="Buscar curso por nombre o fecha" oninput="HabilitarBtnBusquedaCurso (this)"/>
										<label for="buscar-curso">Ingresa el nombre o fecha del curso</label>
									</div>
									<button id="btn-buscar-curso" style="border-radius: 0 5px 5px 0" class="btn btn-secundario btn-svg px-5 py-3 flex-grow-1 flex-sm-grow-0" data-tipo="btn-accion" onclick="BuscarCurso (this)" disabled>
										<span>Buscar</span>
										<svg class="my-auto ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#FFF" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
									</button>
								</div>
							</div>
							<div id="resultado-busqueda-curso" style="overflow-x: auto;" class="collapse multi-collapse">
								<hr class="border-0 my-3">
								<div>
									<h5 class="text-center resaltar"><b>RESULTADO DE LA BÚSQUEDA:</b></h5>
									<div 
										id="resultado-curso" 
										data-componente="tabla-externos" 
										data-color="gray" 
										data-msg_placeholder="No hay resultados" 
										data-colspan="5"
										class="fade-scroll"
										style="max-height: 50vh !important;">
									</div>
								</div>
							</div>
							
							<div id="curso-seleccionado" class="collapse multi-collapse">
								<hr class="border-0 my-3">
								<div class="d-flex flex-column position-relative" style="border: 1px solid #F00; border-radius: 10px;">
									<button class="p-3" style="position: absolute; right: 0; background-color: transparent;" onclick="EliminarSeleccionCurso ()">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20"><path fill="#F00" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
									</button>
									<h5 class="w-100 text-center py-3 resaltar">CURSO EXTERNO SELECCIONADO</h5>
									<div class="px-5 py-3 d-flex flex-column">
										<div class="d-flex"><p class="w-25"><b>Curso:</b></p><p data-info="nombre-curso">Nombre del curso</p></div>
										<div class="d-flex"><p class="w-25"><b>Descripción:</b></p><p data-info="descripcion-curso">Descripción del curso</p></div>
										<div class="d-flex"><p class="w-25"><b>Fecha:</b></p><p data-info="fecha-curso">DD/MM/AAAA</p></div>
										<div class="d-flex"><p class="w-25"><b>Vigencia:</b></p><p data-info="vigencia-curso">DD/MM/AAAA</p></div>
									</div>
								</div>
							</div>

							<hr class="border-0 my-3">
							<div>
								<h5>Fecha en que se acreditó el curso:</h5>
								<input id="fecha-curso" class="form-control" type="date" min="2025-01-01" max=""/>
							</div>
							<hr/>
							<h4 class="resaltar mb-3">Documento del certificado</h4>
							<div class="d-grid gap-3">
								<small>Selecciona el archivo PDF de la certificación:</small>
								<input id="certificado" type="file" accept=".pdf" class="form-control"/>
							</div>
							<div class="my-3 d-flex">
								<button class="btn px-5 py-3 flex-grow-1 flex-md-grow-0 ms-md-auto" onclick="AsignarCertificacion (this)">
									<span>Guardar registro</span>
									<svg class="ms-2 mt-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#FFF" d="M48 96l0 320c0 8.8 7.2 16 16 16l320 0c8.8 0 16-7.2 16-16l0-245.5c0-4.2-1.7-8.3-4.7-11.3l33.9-33.9c12 12 18.7 28.3 18.7 45.3L448 416c0 35.3-28.7 64-64 64L64 480c-35.3 0-64-28.7-64-64L0 96C0 60.7 28.7 32 64 32l245.5 0c17 0 33.3 6.7 45.3 18.7l74.5 74.5-33.9 33.9L320.8 84.7c-.3-.3-.5-.5-.8-.8L320 184c0 13.3-10.7 24-24 24l-192 0c-13.3 0-24-10.7-24-24L80 80 64 80c-8.8 0-16 7.2-16 16zm80-16l0 80 144 0 0-80L128 80zm32 240a64 64 0 1 1 128 0 64 64 0 1 1 -128 0z"/></svg>									
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr id="fin-ventana-sel" class="my-5" style="border-color: transparent">
		</article>
	
	</body>
	<script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/tiempo.js"></script>
	<script src="js/plantillas.js"></script>
	<script src="js/gestion_curso_externo.js"></script>
	<script src="js/scroll.js"></script>
	<script src="js/tooltip.js"></script>
	<script src="js/validacion_in.js"></script>
	<script src="js/interfaz_carga.js"></script>
	<script src="js/accesibilidad.js"></script>
	<script>
		let btn_busqueda = 		 	 	null;
		let btn_busqueda_curso =	 	null;
		let in_nomina = 		 	 	null;
		let in_curso =				 	null;
		let collapse_resultado_emp = 	null;
		let collapse_resultado_cur =	null;
		let collapse_seleccion_emp = 	null;
		let collapse_seleccion_cur = 	null;
		let collapse_curso =		 	null;
		let bs_collapse_resultado_emp = null;
		let bs_collapse_resultado_cur = null;
		let bs_collapse_seleccion_emp = null;
		let bs_collapse_seleccion_cur = null;
		let bs_collapse_curso =		 	null;
		let empleado_seleccionado =  	null;
		let curso_seleccionado =		null;
		let archivo_certificado =		null;

		// Limitar fecha en que se obtuvo certificación
		const fecha_curso = document.querySelector ('#fecha-curso');
		fecha_curso.max = 	ObtenerFechaActual ();
		
		const agregarCertificacion = (boton) => {
			// Recuperar archivo de certificado y convertirlo a Base64
			const reader = new FileReader ();
			const archivo = collapse_curso.querySelector ('input[type="file"]').files [0];
			const fecha =	collapse_curso.querySelector ('input[type="date"]').value;

			if (archivo.type != 'application/pdf') {
				// Formato de archivo incorrecto
				MostrarMensaje (
					'Error con el archivo', 
					'El archivo del certificado seleccionado no corresponde con un PDF.', 
					null, icono_error
				); return;
			}

			if (fecha_curso.value > fecha_curso.max) {
				MostrarMensaje (
					'Error con fecha', 
					'La fecha en que se tomó el curso no puede ser posterior a la fecha actual.', 
					null, icono_error
				); return;
			}

			// Activar botón en modo de carga
			btn_rollback = btnModoCarga (boton);
			
			reader.readAsDataURL (archivo);
			reader.onload = async function () {
				const size = 	 archivo.size;
				const max_size = 1 * 1024 * 1024;
				
				if (size > max_size) {
					// El archivo seleccionado es muy grande
					MostrarMensaje (
						'Error con archivo', 
						'El archivo del certificado seleccionado es muy grande. ' +
						'Considera que el tamaño máximo permitido para este archivo es de 1 Mb.', 
						null, icono_error
					); return;
				}
				
				const formData = {
					empleado: 	 empleado_seleccionado,
					curso: 		 curso_seleccionado,
					certificado: reader.result,
					fecha: 		 fecha
				};
				
				await fetch ('modulos/CursosExternos.php', {
					method: 'POST',
					body: JSON.stringify (formData)
				})
				.then ((resp) => resp.text ())
				.then ((res) => {
					if (res == 'false') {
						// Error al realizar la asignación
						MostrarMensaje (
							'Error al realizar asignación', 
							'Hubo un problema al intentar realizar la asignación. ' + 
							'Por favor, inténtalo de nuevo más tarde.', 
							null, icono_error
						); return;
					}
					// Certificación asignada
					MostrarMensaje (
						'Certificación asignada', 
						'Se asignó correctamente la certificación.', 
						() => window.location.reload (), 
						icono_success
					);
				})
				.catch ((error) => console.error (error))
				// Desactivar modo de carga en el botón
				.finally (() => { btnModoNormal (boton, btn_rollback); });
			};			
		};
		
		function HabilitarBtnBusquedaCurso (input) {
			if (!btn_busqueda_curso) btn_busqueda_curso = document.querySelector ('#btn-buscar-curso');
			if (!in_curso) in_curso = input;
			btn_busqueda_curso.disabled = input.value == '';
		}
		
		function HabilitarBtnBusqueda (input) {
			if (!btn_busqueda) btn_busqueda = document.querySelector ('#btn-buscar');
			if (!in_nomina) in_nomina = input;
			btn_busqueda.disabled = input.value == '';
		}
		
		async function AsignarCertificacion (boton) {
			
			// Validar inputs del curso externo
			const inputs_ce = 		 collapse_curso.querySelectorAll ('input[type="date"], input[type="file"], #fecha-curso');
			const error_validacion = ValidarMultiplesEntradas (inputs_ce);
			
			if(error_validacion) {
				// Error en el llenado del formulario
				IndicarError (error_validacion ['input'], error_validacion ['error']);
				return;
			}
			
			if (!curso_seleccionado || !empleado_seleccionado) {
				MostrarMensaje (
					'No se puede realizar la asignación', 
					'Es necesario seleccionar el empleado y el curso externo ' + 
					'que se asignará, para poder continuar.', 
					null, icono_pregunta, false
				);
				return;
			}
			
			const curso_externo = '<li>' + curso_seleccionado ['nombre'] + '</li>';
			const empleado = 	  '<li>' + empleado_seleccionado ['nombre'] + '</li>';
						
			// Registro válido: Proceder con la asignación
			MostrarMensaje (
				'Realizar asignación', 
				'<p><b>Se asignará la certificación: </b></p><p><ul>' + curso_externo + '</ul></p>' +
				'<p class="mt-3"><b>Al siguiente empleado:</b></p><ul>' + empleado + '</ul>' +
				'<p class="mb-0"><b>¿Deseas continuar?</b></p>', 
				() => agregarCertificacion (boton), icono_pregunta, true, true
			);
		}
		
		function EspecificarCursoExterno () {
			
			const check = document.querySelector ('.indicador-check');
			check.removeAttribute ('hidden');
			
			if (!collapse_curso) {
				// Recuperar collapse para la especificación del curso externo
				collapse_curso = 	document.querySelector ('#info-curso');
				bs_collapse_curso = new bootstrap.Collapse (
					collapse_curso, { toggle: false }
				);
			}
			
			bs_collapse_curso.show ();
			setTimeout (() => {
				window.scrollTo ({ 
					top: document.body.scrollHeight, 
					behavior: "smooth"
				});
			}, 500);
		}
		
		function EliminarSeleccionCurso () {
			// Ocultar collapse de información del curso
			curso_seleccionado = null;
			bs_collapse_seleccion_cur.hide ();
		}
		
		function EliminarSeleccionEmpleado () {
			// Ocultar collapse de selección y de información del curso
			empleado_seleccionado = null;
			bs_collapse_seleccion_emp.hide ();
			if (bs_collapse_curso) {
				bs_collapse_curso.hide ();
			}
		}
		
		function SeleccionarCurso (btn, id_curso) {
			const registro = 		  btn.closest ('tr');
			const nombre_curso = 	  registro.querySelector ('[data-info="nombre-curso"]');
			const descripcion_curso = registro.querySelector ('[data-info="descripcion-curso"]');
			const fecha_curso = 	  registro.querySelector ('[data-info="fecha-curso"]');
			const vigencia_curso = 	  registro.querySelector ('[data-info="vigencia-curso"]');
			
			curso_seleccionado = {
				id_curso:	 id_curso,
				nombre: 	 nombre_curso.textContent,
				descripcion: descripcion_curso.textContent,
				fecha: 		 fecha_curso.textContent,
				vigencia: 	 vigencia_curso.textContent
			};
			
			const nombre_sel = 		collapse_seleccion_cur.querySelector ('[data-info="nombre-curso"]');
			const descripcion_sel = collapse_seleccion_cur.querySelector ('[data-info="descripcion-curso"]');
			const fecha_sel = 		collapse_seleccion_cur.querySelector ('[data-info="fecha-curso"]');
			const vigencia_sel = 	collapse_seleccion_cur.querySelector ('[data-info="vigencia-curso"]');
			
			nombre_sel.textContent = 		nombre_curso.textContent;
			descripcion_sel.textContent = 	descripcion_curso.textContent;
			fecha_sel.textContent = 		fecha_curso.textContent;
			vigencia_sel.textContent = 		vigencia_curso.textContent;
			
			// Mostrar curso seleccionado y hacer scroll hasta la selección
			bs_collapse_seleccion_cur.show ();
			setTimeout (() => {
				collapse_seleccion_cur.scrollIntoView ({ behavior: "smooth", block: "start"});
			}, 200);
		}
		
		function SelccionarEmpleado (btn) {
			const registro = 	 btn.closest ('tr');
			const no_nomina = 	 registro.querySelector ('[data-tipo="id-nomina"]');
			const nombre = 		 registro.querySelector ('[data-tipo="nombre-empleado"]');
			const departamento = registro.querySelector ('[data-tipo="departamento"]');
			
			// Realizar selección de empleado de la lista
			empleado_seleccionado = {
				no_nomina: 	  no_nomina.textContent,
				nombre: 	  nombre.textContent,
				departamento: departamento.textContent
			};
			
			const sel_no_nomina = 	 collapse_seleccion_emp.querySelector ('[data-info="no-nomina"]');
			const sel_nombre = 		 collapse_seleccion_emp.querySelector ('[data-info="nombre-empleado"]');
			const sel_departamento = collapse_seleccion_emp.querySelector ('[data-info="departamento"]');
			
			sel_no_nomina.textContent = no_nomina.textContent;
			sel_nombre.textContent = nombre.textContent;
			sel_departamento.textContent = departamento.textContent;
			
			// Mostrar selección al usuario y hacer scroll hasta la información
			bs_collapse_seleccion_emp.show ();
			setTimeout (() => {
				collapse_seleccion_emp.scrollIntoView ({ behavior: "smooth", block: "start"});
			}, 200);
		}
		
		async function BuscarCurso (boton) {

			if (!collapse_resultado_cur) {
				// Recuperar collapse del resultado de la búsqueda del curso
				collapse_resultado_cur = document.querySelector ('#resultado-busqueda-curso');
				bs_collapse_resultado_cur = new bootstrap.Collapse (
					collapse_resultado_cur, { toggle: false }
				);
			}
			if (!collapse_seleccion_cur) {
				// Recuperar collapse de selección del curso
				collapse_seleccion_cur = document.querySelector ('#curso-seleccionado');
				bs_collapse_seleccion_cur = new bootstrap.Collapse (
					collapse_seleccion_cur, { toggle: false }
				);
			}
			
			const criterio_busqueda = in_curso.value;
			if (!criterio_busqueda) return;
			
			const formData = new FormData ();

			// Bloquear temporalmente botón de búsqueda
			const btn_rollback = btnModoCarga (boton);
	
			formData.append ('accion', 'buscar');
			formData.append ('criterio_busqueda', criterio_busqueda);
			
			// Realizar búsqueda de curso externo
			await fetch ('modulos/CursosExternos.php', {
				method: 'POST',
				body: formData
			})
			.then ((resp) => resp.json ())
			.then ((cursos) => {
				const $tabla =	 document.querySelector   ('#resultado-curso tbody');
				
				// Limpiar tabla de resultados
				$tabla.innerHTML = '';
				
				// Eliminar posible curso seleccionado
				EliminarSeleccionCurso ();
				
				if (!cursos.length) {
					// No se encontraron resultados
					sin_registro = document.createElement ('tr');
					bs_collapse_resultado_cur.hide ();
					MostrarMensaje (
						'Sin resultados', 
						'No se encontró ningún curso que coincidiera con éste criterio de búsqueda.', 
						null, icono_error
					);
					return;
				}
				
				cursos.forEach ((curso) => {
					const registro = 	 $temp_curso_externo.content.cloneNode (true);
					const unidades =	 ["Años", "Meses", "Días"]
					const id_curso = 	 curso ['Id_curso'];
					const nombre = 		 curso ['Nombre'];
					const descripcion =  curso ['Descripcion'];
					const fecha = 	 	 curso ['Fecha'];
					const vigencia_num = curso ['Vigencia'];
					const unidad = 	 	 curso ['Unidad'];
					const vigencia = (vigencia_num) ? 
									  vigencia_num + " " + unidades [unidad] : 
									  'N/A'; 
					
					const nombre_curso = 	  registro.querySelector ('[data-info="nombre-curso"]');
					const descripcion_curso = registro.querySelector ('[data-info="descripcion-curso"]');
					const fecha_curso = 	  registro.querySelector ('[data-info="fecha-curso"]');
					const vigencia_curso = 	  registro.querySelector ('[data-info="vigencia-curso"]');
					const btn_accion = 		  registro.querySelector ('button');
					
					nombre_curso.textContent = 		nombre;
					descripcion_curso.textContent = descripcion;
					fecha_curso.textContent = 		fecha;
					vigencia_curso.textContent = 	vigencia;

					nombre_curso.setAttribute 		('title', nombre);
					descripcion_curso.setAttribute  ('title', descripcion);
					
					btn_accion.innerHTML = 	  '<b>Seleccionar</b>';
					btn_accion.style.border = '1px solid #F00';
					btn_accion.style.color =  '#F00';
					btn_accion.classList.add  ('btn', 'px-3', 'py-2', 'me-2', 'text-truncate');
					btn_accion.setAttribute ('onclick', 'SeleccionarCurso (this, ' + id_curso + ')');
					
					$tabla.appendChild (registro);
				});
				// Mostrar tabla de resultados
				bs_collapse_resultado_cur.show ();
				bs_collapse_seleccion_cur.hide ();
			})
			.finally (() => { btnModoNormal (boton, btn_rollback); });
		}
		
		async function BuscarEmpleado (boton) {
			if (!collapse_resultado_emp) {
				// Recuperar collapse del resultado de la búsqueda del empleado
				collapse_resultado_emp = document.querySelector ('#resultado-busqueda-empleado');
				bs_collapse_resultado_emp = new bootstrap.Collapse (
					collapse_resultado_emp, { toggle: false	}
				);
			}
			if (!collapse_seleccion_emp) {
				// Recuperar collapse de selección de empleado
				collapse_seleccion_emp = document.querySelector ('#empleado-seleccionado');
				bs_collapse_seleccion_emp = new bootstrap.Collapse (
					collapse_seleccion_emp, { toggle: false }
				);
			}

			const criterio_busqueda = in_nomina;

			// Bloquear temporalmente botón de búsqueda
			const btn_rollback = btnModoCarga (boton);
			
			if (!criterio_busqueda) {
				return;
			}
			
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
				
				// Eliminar posible selección previa de empleado
				EliminarSeleccionEmpleado ();
				
				if (!empleados.length) {
					MostrarMensaje (
						'Sin resultados', 
						'No se encontró ningún empleado que coincidiera con éste criterio de búsqueda.', 
						null, icono_error
					);					
					bs_collapse_resultado_emp.hide ();
					return;
				}
				
				// Mostrar como resultado únicamente los empleados que aún no ha sido seleccionados
				empleados.forEach ((empleado) => {

					const registro = 	 $temp_empleado_asignar.content.cloneNode (true);
					const id_nomina =  	 registro.querySelector   ('[data-tipo="id-nomina"]');
					const nombre = 	   	 registro.querySelector   ('[data-tipo="nombre-empleado"]');
					const departamento = registro.querySelector   ('[data-tipo="departamento"]');
					const btn_accion = 	 registro.querySelector   ('button');
					
					id_nomina.innerHTML = 	  empleado ['no_nomina'];
					nombre.innerHTML = 	  	  empleado ['nombre'];
					departamento.innerHTML =  empleado ['area'];
					btn_accion.innerHTML = 	  '<b>Seleccionar</b>';
					btn_accion.style.border = '1px solid #F00';
					btn_accion.style.color =  '#F00';
					btn_accion.classList.add ('btn', 'px-3', 'py-2', 'text-truncate');
					btn_accion.setAttribute ('onclick', 'SelccionarEmpleado (this)');

					$tabla.appendChild (registro);
					bs_collapse_resultado_emp.show ();
				});
			})
			.catch ((error) => console.error(error))
			.finally (() => { btnModoNormal (boton, btn_rollback) });

			// Configurar elementos (tablas) con fade dinámico 
			ConfigurarFadeScroll ();
		}
	</script>
	
</html>