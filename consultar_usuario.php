<?php 
require_once ('modulos/GestionUsuarios.php'); 
ValidarSesion ();
ValidarAdmin ($sesion->getTipoUsuario ());
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Consultar empleado</title>
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

			<button class="scroll-top d-flex" onclick="ScrollTop ()">
				<svg class="m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40"><path fill="#fff" d="M233.4 105.4c12.5-12.5 32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L256 173.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l192-192z"/></svg>
			</button>
			<button class="scroll-bottom d-flex" onclick="ScrollBottom ()">
				<svg class="m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40"><path fill="#fff" d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>
			</button>
		
			<div
			data-componente="ventana"
			data-titulo="Consultar empleado"
			data-color="gray">
				<div data-tipo="contenido">
					<div class="personalizado">
						
						<div class="d-flex flex-wrap my-3">
							<div class="form-floating m-0 flex-grow-1" style="overflow: hidden;">
								<input id="in-buscar" type="text" class="form-control" style="border-radius: 5px 0 0 5px" placeholder="Buscar por nombre o número de nómina..." oninput="HabilitarBusqueda (this)"/>
								<label style="max-width: 100%" class="text-truncate" for="buscar-nomina">Ingresa el nombre o número de nómina del empleado</label>
							</div>
							<button id="btn-buscar" class="btn-primario px-5 py-3 flex-grow-1 flex-sm-grow-0" style="border-radius: 0 5px 5px 0" data-tipo="btn-accion" onclick="BuscarEmpleado ()" disabled>
								<span>Consultar</span>
								<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#FFF" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
							</button>
						</div>
						
						<div id="resultado-busqueda-empleado" class="collapse multi-collapse">
							<div>
								<h5 class="text-center resaltar"><b>EMPLEADOS QUE COINCIDEN CON LA BÚSQUEDA:</b></h5>
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
						
					</div>
				</div>
			</div>
						
			<div id="collapse-info" class="collapse multi-collapse" style="animation: insertarComponente 1s">
				<hr class="my-5 border-0">
				<div 
				data-componente="ventana"
				data-titulo="Información del empleado"
				data-color="dark">
					<div data-tipo="contenido">
						<div 
						data-componente="bloque-info-usuario" 
						class="info-xl"></div>
					</div>
				</div>
				
				<div 
				id="cursos-pendientes"
				data-componente="ventana-extendida"
				data-titulo="Cursos pendientes del empleado"
				data-color="orange">
					<div data-tipo="contenido">
						<div data-componente="tabla-CP-admin" class="w-100 mx-0"></div>
					</div>
					<div 
					id="footer-pendientes" 
					data-componente="footer-paginacion"
					data-consulta_usuario="true"
					data-tipo_consulta="pendientes"></div>
				</div>
				
				<div 
				id="cursos-aprobados"
				data-componente="ventana-extendida"
				data-titulo="Cursos aprobados por el empleado"
				data-color="green">
					<div data-tipo="contenido">
						<small data-componente="nota-tabla" class="py-2 px-5 aprobado" hidden="true">Una <b>C</b> al final de la calificación indicará que la calificación fué colaborativa.</small>
						<div class="w-100 mx-0" data-componente="tabla-cursos-aprobados">
							<div data-componente="curso-aprobado"></div>
							<div data-componente="curso-aprobado"></div>
						</div>
					</div>
					<div 
					id="footer-aprobados" 
					data-componente="footer-paginacion"
					data-consulta_usuario="true"
					data-tipo_consulta="aprobados"></div>
				</div>
				
				<div 
				id="cursos-externos"
				data-componente="ventana-extendida"
				data-titulo="Cursos externos del empleado"
				data-color="blue">
					<div data-tipo="contenido">
						<div data-componente="tabla-admin-Cert" class="w-100 mx-0">
							<div data-componente="admin-CE"></div>
							<div data-componente="admin-CE"></div>
						</div>
					</div>
					<div 
					id="footer-externos" 
					data-componente="footer-paginacion"
					data-consulta_usuario="true"
					data-tipo_consulta="externos"></div>
				</div>
				
				<div
				id="cursos-creados"
				data-componente="ventana-extendida"
				data-titulo="Cursos creados por el empleado"
				data-color="dark">
					<div data-tipo="contenido">
						<div data-componente="tabla-CC-admin" class="w-100 mx-0">
							<div data-componente="CC-admin"></div>
						</div>
					</div>
					<div 
					id="footer-creados" 
					data-componente="footer-paginacion"
					data-consulta_usuario="true"
					data-tipo_consulta="creados"></div>
				</div>
			</div>
			<hr class="my-3 border-0">
		</article>
	</body>
	<script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/scroll.js"></script>
	<script src="js/gestion_curso_interno.js"></script>
	<script src="js/gestion_curso_externo.js"></script>
	<script src="js/historial_versiones.js"></script>
	<script src="js/util_movil.js"></script>
	<script src="js/certificados.js"></script>
	<script>
		// Información del usuario
		var cursos_pag = 	10;
		var usuario_priv =  false;
		var usuario = 		null;
		var c_pendientes = 	null;
		var c_aprobados = 	null;
		var c_externos = 	null;
		var c_creados = 	null;
		
		// Componentes de la interfaz
		let in_buscar =  null;
		let btn_buscar = null;
		
		// Elementos collapsables
		let collapse_resultado = null;
		let collapse_info = 	 null;
		
		let bs_collapse_resultado = null;
		let bs_collapse_info = 		null;
		
		function HabilitarBusqueda (input) {
			if (!in_buscar) in_buscar =		input;
			if (!btn_buscar) btn_buscar = 	document.querySelector ('#btn-buscar');
			
			btn_buscar.disabled = in_buscar.value == '';
		}
		
		async function MostrarInfoUsuario (usuario) {
			
			if (!collapse_info) {
				// Recuperar element collapsable con la información del usuario
				collapse_info = document.querySelector ('#collapse-info');
				bs_collapse_info = new bootstrap.Collapse (
					collapse_info, { toggle: false }
				);
			}
			setTimeout (() => {
				bs_collapse_info.show ();
			}, 200);
			setTimeout (() => {
				collapse_info.scrollIntoView ({ behavior: "smooth", block: "start"});
			}, 500);
			
			const tipos_usuario = ['Empleado', 'Instructor', 'Administrador']
			const img = 		  document.querySelector ('img');
			const bloque_info =   document.querySelector ('[data-componente="bloque-info-usuario"]');
			const nombre = 		  bloque_info.querySelector ('[data-info="nombre-empleado"]');
			const tipo = 		  bloque_info.querySelector ('[data-info="tipo-empleado"]');
			const no_nomina = 	  bloque_info.querySelector ('[data-info="nomina-empleado"]');
			const departamento =  bloque_info.querySelector ('[data-info="departamento-empleado"]');
			const email = 		  bloque_info.querySelector ('[data-info="email-empleado"]');
			const tipo_usuario =  usuario [1] ? usuario[1]['Tipo_usuario'] : 0;
			
			// Determinar si se trata de un usuario con privilegios
			usuario_priv = tipo_usuario == 1 || tipo_usuario == 2;
			
			// Mostrar información del empleado en el bloque de información			
			nombre.textContent = 		usuario [0]['nombre'];
			no_nomina.textContent = 	usuario [0]['no_nomina'];
			departamento.textContent = 	usuario [0]['departamento'];
			email.textContent = 		usuario [0]['email'];
			tipo.textContent = 			tipos_usuario [tipo_usuario];
			
			// Recuperar imagen de perfil y mostrarla
			await ObtenerFotoPerfil (img, usuario [0]['no_nomina']);
			
		}
		
		function MostrarCertificaciones (c_externos) {
			// Mostrar certificaciones del empleado
			const tabla_CP = document.querySelector ('[data-componente="tabla-admin-Cert"]');
			const tbody = 	 tabla_CP.querySelector ('tbody');
			const footer = 	 document.querySelector ('#cursos-externos div.footer-paginacion');
			
			// Mostrar footer de paginación al exceder el número de cursos por página
			footer.innerHTML = c_pendientes.length > cursos_pag ? footer.innerHTML : '';
			
			if (!c_externos.length) {
				tbody.innerHTML = 
				'<tr><td colspan="5" class="text-center resaltar py-4">' +
					'No hay certificaciones externas por mostrar' +
				'</td></tr>'; return;
			}
			
			tbody.innerHTML = 		'';
			let cursos_aprobados = 	'';
			
			c_externos.forEach ((curso) => {
				const nuevo = 		 $temp_admin_CE.content.cloneNode (true);
				const nombre = 		 nuevo.querySelector ('[data-info="nombre"]');
				const fecha = 		 nuevo.querySelector ('[data-info="fecha"]');
				const validez = 	 nuevo.querySelector ('[data-info="validez"]');
				const btn_ver =		 nuevo.querySelector ('[data-info="btn-ver"]');
				const btn_eliminar = nuevo.querySelector ('[data-info="btn-eliminar"]');

				// Comprobar validez de la certificación
				if ((new Date (curso ['Validez']) < new Date ()) && curso ['Validez']) {
					validez.style.color = '#F00';
				}
				
				nombre.textContent =  curso ['Nombre'];
				fecha.textContent =	  curso ['Fecha'];
				validez.textContent = curso ['Validez'] ?
									  curso ['Validez'] : 'N/A';
				
				// Configurar botón para visualizar curso
				btn_ver.onclick = () => {
					window.open (
						"ver_certificacion.php?id_certificacion=" + 
						curso ['Id_certificacion'], 
						"_blank"
					);
				};
				btn_eliminar.onclick = () => {
					// Eliminar certificación del registro del empleado
					EliminarCertificacionEmpleado (
						curso ['Nombre'],
						'con nómina ' + curso ['No_nomina'],
						curso ['Id_certificacion']
					);
				};
				
				// Agregar lista de cursos a la tabla
				tbody.appendChild (nuevo);
			});
		}
	
		async function RecuperarInfoUsuario (btn = null, no_nomina = null) {
			let registro =  null;
			let btn_normal = null;
			
			if (btn) {
				registro = 	btn.closest ('tr');
				no_nomina = registro.querySelector ('[data-tipo="id-nomina"]').textContent;
				
				// Cambiar estado del botón a modo de carga
				btn_normal = btnModoCarga (btn);
			}
			const formData =  new FormData ();
			
			formData.append ('consultar_empleado', no_nomina);
			
			// Consultar toda la información del usuario
			await fetch ('modulos/GestionUsuarios.php', {
				method: 'POST',
				body: formData
			})
			.then ((resp) => resp.json ())
			.then ((empleado) => {
				usuario = 		empleado ['informacion'];
				c_pendientes = 	empleado ['cursos_pendientes'];
				c_aprobados =  	empleado ['cursos_aprobados'];
				c_externos =   	empleado ['certificaciones'];
				c_creados = 	empleado ['cursos_creados'];
				
				MostrarInfoUsuario 		(usuario);
				MostrarCursosPendientes (c_pendientes, usuario [0]['no_nomina']);
				MostrarCursosAprobados 	(c_aprobados);
				MostrarCertificaciones 	(c_externos);
				MostrarCursosCreados 	(c_creados);

				// Configurar footers de las consultas
				const footers_consulta = document.querySelectorAll ('[data-consulta_usuario="true"]');
				footers_consulta.forEach ((footer) => {

					// Páginas de consulta de cursos
					const paginas_consulta = {
						pendientes: 'consultar_pendientes.php',
						aprobados: 	'consultar_aprobados.php',
						externos: 	'consultar_certificaciones.php',
						creados: 	'consultar_creados.php'
					};

					const paginacion = 	footer.parentElement.querySelector ('.footer-paginacion');
					const $ver_todos = 	document.createElement ('div');
					const pagina = 		paginas_consulta [footer.dataset.tipo_consulta];
					
					// Configurar consultas de los diferentes tipos de cursos de los empleados
					$ver_todos.classList.add ('d-flex');
					$ver_todos.innerHTML =
					'<button class="btn-ver m-auto d-flex btn btn-svg" onclick="window.open (\'' + pagina + '?nomina=' + no_nomina + '\', \'_blank\')">' +
						'<svg class="me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>' +
						'<h6 class="mx-auto my-auto">Ver todos</h6>' +
					'</button>';
					paginacion.appendChild ($ver_todos);
				});
			})
			.finally (() => { 
				// Regresar botón de acción a su modo normal
				if (btn_normal) btnModoNormal (btn, btn_normal); 
			});
		}
		
		async function BuscarEmpleado () {
			if (!collapse_resultado) {
				collapse_resultado = document.querySelector ('#resultado-busqueda-empleado');
				bs_collapse_resultado = new bootstrap.Collapse (
					collapse_resultado, { toggle: false }
				);
			}

			// Cambiar estado del botón a modo de carga
			const btn_normal = btnModoCarga (btn_buscar);
			
			// Ocultar información de posible consulta anterior
			if (bs_collapse_info) bs_collapse_info.hide ();
		
			const criterio_busqueda = in_buscar;
			
			if (!criterio_busqueda) return;
			
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
					MostrarMensaje (
						'Sin resultados', 
						'No se encontró ningún empleado que coincidiera con éste criterio de búsqueda.', 
						null, icono_error
					);					
					bs_collapse_resultado.hide ();
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
					btn_accion.closest ('td').dataset.info = "ir";
					btn_accion.innerHTML = 	  '<b>Ver información</b>';
					btn_accion.style.border = '1px solid #F00';
					btn_accion.style.color =  '#F00';
					btn_accion.classList.add ('btn', 'px-3', 'py-2', 'd-inline-block', 'text-truncate');
					btn_accion.setAttribute ('onclick', 'RecuperarInfoUsuario (this)');

					$tabla.appendChild (registro);
					bs_collapse_resultado.show ();
				});
			})
			.catch ((error) => console.error(error))
			.finally (() => btnModoNormal (btn_buscar, btn_normal));

			// Configurar elementos (tablas) con fade dinámico 
			ConfigurarFadeScroll ();
		}
	</script>
	<script src="js/plantillas.js"></script>
	<script src="js/accesibilidad.js"></script>

</html>