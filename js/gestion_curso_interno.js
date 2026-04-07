async function RemoverAsignacion (id_curso, version, no_nomina, recargar = false) {
	const formData = new FormData ();
	formData.append ('desasignar', 'curso_interno');
	formData.append ('id_curso', id_curso);
	formData.append ('version', version);
	formData.append ('no_nomina', no_nomina);

	const MensajeError = () => {
		MostrarMensaje (
			'No se pudo remover el curso pendiente', 
			'Hubo un error al intentar remover el curso pendiente. ' + 
			'Por favor, inténtalo de nuevo más tarde.', 
			null, icono_error
		);
	};

	const MensajeCorrecto = () => {
		MostrarMensaje (
			'Curso removido', 
			'Se removió correctamente la asignación del curso.', 
			recargar ? 
			() => window.location.reload() : 
			() => RecuperarInfoUsuario (null, no_nomina), 
			icono_success
		);
	}; 
	
	// Eliminar registro de curso pendiente de la BD
	await fetch ('modulos/CursosInternos.php', {
		method: 'POST',
		body: formData
	})
	.then ((resp) => resp.text())
	.then ((res) => {
		if (res != 'true') {
			MensajeError ();
			return;
		}
		MensajeCorrecto ();
	})
	.catch ((error) => console.log(error));
}

function QuitarCurso (curso_id, curso_nombre, curso_version, no_nomina, ondelete_reload = false) {
	// Evento al seleccionar "des-asignar curso"
	MostrarMensaje (
		"Des-asignar curso a empleado",
		"Se desasignará el curso pendiente:  <b>" + curso_nombre + "</b>, al empleado. <br><br><b>¿Deseas continuar?</b>",
		() => {
			// Remover asignación de curso y actualizar información del empleado
			RemoverAsignacion 	 (curso_id , curso_version,  no_nomina, ondelete_reload);
			RecuperarInfoUsuario (null, no_nomina);
		}, icono_pregunta, true, true
	);
}

function VerCurso (id_curso, version) {
	// Evento al presionar botón "Ver curso"
	window.open ("visualizador.php?curso_int=" + 
		id_curso + "&version=" + 
		version + "&visualizar=true", "_blank"
	);
}

function MostrarCursosPendientes (c_pendientes, no_nomina) {
	// Mostrar cursos pendientes del empleado
	const tabla_CP = document.querySelector ('[data-componente="tabla-CP-admin"]');
	const tbody = 	 tabla_CP.querySelector ('tbody');
	const footer = 	 document.querySelector ('#cursos-pendientes div.footer-paginacion');
	
	// Mostrar footer de paginación al exceder el número de cursos por página
	footer.innerHTML = c_pendientes.length > cursos_pag ? footer.innerHTML : '';
	
	if (!c_pendientes.length) {
		tbody.innerHTML = 
		'<tr><td colspan="6" class="text-center resaltar py-4">' +
			'Actualmente, el empleado no cuenta con cursos pendientes' +
		'</td></tr>'; return;
	}
	
	tbody.innerHTML = 		'';
	//let cursos_pendientes = '';
	
	c_pendientes.forEach ((curso) => {
		const nuevo = 		 	$temp_CP_admin.content.cloneNode (true);
		const nombre_curso = 	nuevo.querySelector ('[data-info="nombre-curso"]');
		const version_curso  =	nuevo.querySelector ('[data-info="version-curso"]');
		const asignado = 	 	nuevo.querySelector ('[data-info="asignado"]');
		const vencimiento = 	nuevo.querySelector ('[data-info="vencimiento"]');
		const btn_quitar = 		nuevo.querySelector ('button.btn-quitar');
		const btn_ver =			nuevo.querySelector ('button.btn-ver');
		
		nombre_curso.textContent = 	curso ['Nombre'];
		version_curso.textContent = curso ['Version'];
		asignado.textContent = 		curso ['Fecha'];
		vencimiento.textContent =	curso ['Fecha_limite'] ? 
									curso ['Fecha_limite'] : 'N/A';

		// Identificar cursos vencidos
		if (curso ['Fecha_limite']) {
			const fecha_actual = new Date ();
			const fecha_limite = new Date (curso ['Fecha_limite'] + 'T00:00:00.000-06:00');
			if (fecha_actual > fecha_limite)  nuevo.querySelector ('tr').classList.add ('curso-vencido');
		}
		
		// Configurar botón para des-asignar curso
		btn_quitar.onclick = () => {
			QuitarCurso (curso ['Id_curso'], curso ['Nombre'], curso ['Version'], no_nomina)
		}
		
		// Configurar botón para visualizar curso
		btn_ver.onclick = () => {
			VerCurso (curso ['Id_curso'], curso ['Version']);
		};
		
		// Agregar lista de cursos a la tabla
		tbody.appendChild (nuevo);
	});
}

function MostrarCursosAprobados (c_aprobados) {
	// Mostrar cursos aprobados por el empleado
	const tabla_CP = document.querySelector ('[data-componente="tabla-cursos-aprobados"]');
	const tbody = 	 tabla_CP.querySelector ('tbody');
	const footer = 	 document.querySelector ('#cursos-aprobados div.footer-paginacion');
	
	// Mostrar footer de paginación al exceder el número de cursos por página
	footer.innerHTML = c_pendientes.length > cursos_pag ? footer.innerHTML : '';
	
	if (!c_aprobados.length) {
		tbody.innerHTML = 
		'<tr><td colspan="7" class="text-center resaltar py-4">' +
			'No hay cursos aprobados por mostrar' +
		'</td></tr>'; return;
	}
	
	tbody.innerHTML = 		'';
	
	c_aprobados.forEach ((curso) => {
		const nuevo = 		 	$temp_curso_aprobado.content.cloneNode (true);
		const nombre_curso = 	nuevo.querySelector ('[data-info="nombre"]');
		const version_curso  =	nuevo.querySelector ('[data-info="version"]');
		const fecha = 	 		nuevo.querySelector ('[data-info="fecha"]');
		const puntaje = 		nuevo.querySelector ('[data-info="puntaje"]');
		const intentos = 	 	nuevo.querySelector ('[data-info="intentos"]');
		const btn_ver =			nuevo.querySelector ('td[data-info="ir"] button');
		const btn_certificado = nuevo.querySelector ('td[data-info="certificado"] button');
		const nota_colab =		document.querySelector ('#cursos-aprobados [data-componente="nota-tabla"]');

		// Calcular calificación obtenida
		const calificacion = (curso ['Puntaje'] / curso ['Puntaje_max']) * 100;

		// Determinar si la calificación fué colaborativa
		let colaborativo = '';
		if (curso ['Colaborativo'] === '1') {
			colaborativo = ' <b>C</b>';
			nota_colab.removeAttribute ('hidden');
		}
		
		nombre_curso.textContent = 	curso ['Nombre'];
		version_curso.textContent = curso ['No_version'];
		fecha.textContent = 		curso ['Aprobado'];
		intentos.textContent = 		curso ['Intentos'];
		puntaje.innerHTML = 		parseFloat (calificacion).toFixed (2) + colaborativo;

		puntaje.style.cursor = 'default';
		puntaje.setAttribute (
			'title', 
			curso ['colaborativo'] == '1' ? 
			'Calificación colaborativa' : 'Calificación individual'
		);
		
		// Configurar botón para visualizar curso
		btn_ver.setAttribute (
			'onclick', 
			'window.open ("visualizador.php?curso_int=' + 
			curso ['Id_curso'] + '&version=' + 
			curso ['No_version'] + '&visualizar=true", "_blank")', 
		);

		btn_certificado.setAttribute (
			// Ver certificado de curso aprobado
			'onclick',
			'GenerarCertificado (' + 
				'"' + curso ['Id_curso'] + '", ' +
				'"' + curso ['No_nomina'] + '"' +
			')'
		);
		
		// Agregar lista de cursos a la tabla
		tbody.appendChild (nuevo);
	});
}

function MostrarCursosCreados (c_creados) {
			
	// Mostrar cursos creados por el empleado
	const ventana_cc = document.querySelector ('#cursos-creados');
	const tabla_CP = document.querySelector ('[data-componente="tabla-CC-admin"]');
	const tbody = 	 tabla_CP.querySelector ('tbody');
	const footer = 	 document.querySelector ('#cursos-creados div.footer-paginacion');
	
	if (!usuario_priv) {
		// Ocultar tabla de cursos creados
		ventana_cc.hidden = true;
		return;
	}
	
	// Mostrar tabla de cursos creados
	ventana_cc.hidden = false;
	
	// Mostrar footer de paginación al exceder el número de cursos por página
	footer.innerHTML = c_pendientes.length > cursos_pag ? footer.innerHTML : '';
	
	if(!c_creados.length) {
		tbody.innerHTML = 
		'<tr><td colspan="4" class="text-center resaltar py-4">' +
			'El usuario no ha creado ningún curso todavía' +
		'</td></tr>'; return;
	}
	
	tbody.innerHTML = '';
	
	c_creados.forEach ((curso) => {
		const nuevo = 		 	$temp_CC_admin.content.cloneNode (true);
		const nombre_curso = 	nuevo.querySelector ('[data-info="nombre-curso"]');
		const version_curso = 	nuevo.querySelector ('[data-info="version-curso"]');
		const btn_ver =			nuevo.querySelector ('button.btn-ver');
		const btn_historial =	nuevo.querySelector ('button[data-info="btn-historial"]')
		
		nombre_curso.textContent = 	curso ['Nombre'];
		version_curso.textContent = curso ['Version'];
		
		// Configurar botón para visualizar curso
		btn_ver.setAttribute (
			'onclick',
			'window.open("visualizador.php?curso_int=' + 
			curso ['Id_curso'] + '&version=' + 
			curso ['Version'] + '&visualizar=true", "_blank")'
		);

		// Configurar botón para abrir historial de versiones
		btn_historial.setAttribute (
			'onclick',
			'AbrirHistorial (' + curso ['Id_curso'] + ', ' + 
				null + ', ' 
				+ curso ['Version'] + ', ' + 
				'true' + 
			')'
		);
		
		// Agregar lista de cursos a la tabla
		tbody.appendChild (nuevo);
	});
}

async function ObtenerContenidoCurso (anterior, id_curso, id_int) {
	return new Promise (async (resolve) => {
		if (id_curso == null || id_int == null) resolve ([]);
		
		const formData = new FormData ();
		formData.append ('ultimo_ci', id_int);
		formData.append ('id_curso', id_curso);
		if (anterior) formData.append ('anterior', 'true');
		
		// Recuperar los elementos de imagen y video del contenido del curso
		await fetch ('modulos/CursosInternos.php', {
			method: 'POST',
			body: formData
		})
		.then ((resp) => resp.text ())
		.then ((curso) => {
			// No se encontró contenido del curso
			if (!curso.length) resolve ([]);
			
			const parser = 	new DOMParser ();
			const html = 	parser.parseFromString (JSON.parse (curso), 'text/html');
			
			resolve (html.querySelectorAll ('video, img'));
		})
		.catch (() => { });
	});
}

async function NuevoCurso () {
	const formData = new FormData ();
	formData.append ('nuevo', 'true');

	// Preparar el editor para iniciar la creación de un nuevo curso
	await fetch ('editor.php', {
		method: 'POST',
		body: formData
	})
	.then ((resp) => resp.json ())
	.then ((resultado) => {
		if (resultado) {
			window.location.replace ('editor.php');
		}
	});
}

async function DescartarEdicion (
	id_curso = 	null, 
	id_int = 	null,
	recargar = 	false
) {
	const RevertirPortadaCurso = async () => {
		const formPortada = new FormData ();
		formPortada.append ('revertir_portada', true);
		formPortada.append ('id_curso', id_curso);

		await fetch ('modulos/CursosInternos.php', {
			method: 'POST',
			body: formPortada
		})
		.then ((resp) => resp.json ())
		.then (async (estatus) => {
			await EliminarPortada (estatus.portada_edicion);
		});
	};
	
	// Asegurar la eliminación de todos los recursos multimedia del borrador
	const descartarEdicion = async () => {
		const descartar = (id_curso != null) ? 
						  id_curso : 
						  'editar-curso';
		// Recuperar portada anterior de curso, previo a la edición
		RevertirPortadaCurso ();

		// Recuperar elementos multimedia de borrador y de última versión del curso		
		const contenido_ant =  Array.from (await ObtenerContenidoCurso (true, id_curso, id_int));
		const contenido_cur =  Array.from (await ObtenerContenidoCurso (false, id_curso, id_int));

		// Obtener las rutas de las imágenes y videos
		const src_anteriores = new Set (contenido_ant.map (elemento => {
			return elemento.tagName == 'IMG' ? 
			elemento.src : elemento.querySelector ('source').src
		}));

		// Filtrar las imágenes y videos que no se encuentren en la última versión del curso
		const cambios_multimedia = contenido_cur.filter (
			multimedia => !src_anteriores.has (multimedia.src)
		);
		
		await cambios_multimedia.forEach (async (nuevo) => {
			let src = null;
			
			// Revertir todos los cambios realizados (en imágenes y videos)
			/*
				Nota. Se comparará el contenido del borrador con la última versión del curso,
				para determinar qué cambios fueron realizados.
			*/
			if (nuevo.tagName == 'IMG') src = nuevo.src;
			else src = nuevo.querySelector ('source').src;
			
			const url = new URL (src);
			const segmentos_url = url.pathname.split('/').filter(segmento => segmento !== '');
			
			// Convertir ruta absoluta del archivo en ruta relativa del servidor.
			segmentos_url.shift ();
			const src_relativo = decodeURIComponent (segmentos_url.join ('/'));
			
			const formData = new FormData ();
			formData.append ('accion', 'eliminar_recurso');
			formData.append ('src', '../../' + src_relativo);
			
			// Remover cambios
			await fetch ('modulos/util/gestion_archivos.php', {
				method: 'POST',
				body: formData
			})
			.then ((resp) => console.log (resp.text()))
			.catch ((error) => console.error (error));
		});
	
		await fetch("editor.php", {
			method: "post",
			headers: new Headers({
				'Content-Type': 'application/x-www-form-urlencoded'
			}),
			body: 'descartar=' + descartar + (id_int != null ? '&id_int=' + id_int : '')
		})
		.then (() => {
			if (typeof RevertirCambiosMultimedia === 'function') {
				// Revertir cambios multimedia no guardados
				RevertirCambiosMultimedia ();
			}
			if(!recargar) { window.location.replace('inicio.php'); return; }
			window.location.replace ('editor.php');
		});
	};
	
	MostrarMensaje (
		'Descartar borrador', 
		'Se descartarán los cambios realizados a este curso, ¿Deseas continuar?', 
		descartarEdicion, icono_pregunta, true
	);
}

function ConfirmarEliminacionCurso ($elemento) {
	const id_curso = $elemento.dataset.id;
	const nombre = 	 $elemento.dataset.nombre;
	
	MostrarMensaje (
		'Eliminar curso',
		'Se eliminará el curso: "' + nombre + '", ¿Desea continuar?',
		function () { EliminarCurso (id_curso) },
		icono_delete, true
	);
}

async function EliminarCurso (id_curso) {
	
	await fetch('editor.php', {
		method: 'post',
		headers: new Headers ({
			'Content-Type': 'application/x-www-form-urlencoded'
		}),
		body: 'eliminar=curso&id_curso=' + id_curso
	})
	.then((resp) => resp.text())
	.then((estado) => {
		if(estado) window.location.reload ();
	});
}

async function PublicarCurso (boton, departamento = null) {
	
	let guardado_correcto = false;
	const min_num_preg = 	5;

	// Validar que haya al menos 5 reguntas en el banco de preguntas
	if (!numPregValido (min_num_preg)) {

		const irAPreguntas = () => {
			const contenedor_preg = document.querySelector ('#preguntas');
			contenedor_preg.scrollIntoView ({block: 'start', inline: 'nearest'});
		};

		MostrarMensaje (
			'No se pudo publicar el curso', 
			'Para publicar un curso, el número mínimo de preguntas ' +
			'disponibles en el banco deberá ser de ' + min_num_preg + '.', 
			() => irAPreguntas (), icono_error
		); return;
	}
	
	// Guardar curso como Borrador
	await AgregarBorradorBD (departamento)
	.then ((resolve) => {
		// Recuperar estado de guardado del borrador del curso
		guardado_correcto = resolve === null ? false : true;
	})
	.catch (() => {
		// Error de conexión al intentar guardar el borrador
		boton.disabled = false;
		boton.textContent = "Guardar";
		MostrarMensaje (
			"Error", 
			"Hubo un problema al intentar publicar el curso. " + 
			"Por favor, intenta más tarde.",
			null,
			icono_error
		);
	});
	
	if (!guardado_correcto) return;
	
	const publicarCurso = async () => {
		
		// Comprobar si se desea reasignar a empleados de versiones anteriores
		const reasignar = 	document.querySelector ('#reasignar-empleados') != null ?
							document.querySelector ('#reasignar-empleados').checked :
							false;

		const AplicarCambiosPortada = async () => {
			const formPortada = new FormData ();
			formPortada.append ('actualizar_portada', true);
			formPortada.append ('id_curso', id_curso);

			await fetch ('modulos/CursosInternos.php', {
				method: 'POST',
				body: formPortada
			})
			.then ((resp) => resp.json ())
			.then (async (estatus) => {
				await EliminarPortada (estatus.portada_anterior);
			});
		};

		const realizarReasignacion = async (version_nueva) => {
			const formReasigacion = new FormData ();

			formReasigacion.append ('reasignar_empleados', 'true');
			formReasigacion.append ('id_curso', id_curso);
			formReasigacion.append ('version', version_nueva)
			
			await fetch ('modulos/AsignacionCursos.php', {
				method: 'POST',
				body: formReasigacion 
			})
			.then ((resp) => resp.json())
			.then ((info) => {
				// Notificar a usuarios previamente asignados
				NotificarUsuarios (info ['correos'], info ['cursos'], 'true');
			})
			.catch ((error) => console.error (error));
		};

		// Enviar petición al editor para publicar curso
		await fetch('editor.php', {
			method: 'post',
			headers: new Headers({
				 'Content-Type': 'application/x-www-form-urlencoded',
			}),
			body: 'publicar=publicar-curso'
		})
		.then((resp) => resp.json())
		.then((json) => {
			if (json.respuesta) {

				// Actualizar portada del curso
				AplicarCambiosPortada ();

				// Reasignar curso a los empleados asignados a versiones anteriores
				if (reasignar) realizarReasignacion (json ['respuesta']);

				MostrarMensaje (
					"Operación realizada",
					"Se publicó el curso correctamente",
					function () { window.location.replace ('cursos_creados.php') },
					icono_success
				);
			}
		});
	};

	// Agregar opción de reasignación si hay más de una versión del curso 
	const msg_reasignar = (tiene_version_prev) ? '<hr>' +
	'<small><b>Opciones adicionales:</b></small>' +
	'<hr class="my-2 border-0">' +
	'<div class="d-flex"><input id="reasignar-empleados" class="me-3" type="checkbox" name="reasignar">' +
	'<small for="reasignar">Reasignar a empleados asignados a versiones anteriores del curso.</small></div>'
	: '';
	
	MostrarMensaje (
		'Publicar nueva versión', 
		'A partir de ahora, el contenido de este curso estará disponible en plataforma,<hr class="my-2 border-0">¿Deseas continuar?' +
		msg_reasignar, publicarCurso, icono_pregunta, true, true, true
	);
}

function EliminarPortada (archivo) {
	return new Promise (async (resolve, ) => {
		const formEliminar = new FormData ();
		formEliminar.append ('accion', 'eliminar_recurso');
		formEliminar.append ('src', '../../uploads/portadas/' + archivo);

		await fetch ('modulos/util/gestion_archivos.php', {
			method: 'POST',
			body: formEliminar
		})
		.then ((resp) => resp.text ())
		.then (() => {
			resolve (true);
		});
	});
}

async function AgregarBorradorBD (departamento = null) {
	
	// Guardar curso como Borrador
	curso = GenerarBorrador (departamento);
	
	return new Promise (async function (resolve, reject) {
		try {
			// Hubo un error al recuperar el curso (posible error de validación)
			if (curso == null) { resolve (null); return; }
			
			let respuesta = null; 

			// Subir imagen de portada (en caso de que haya sido seleccionada)
			const SubirPortada = () => {
				return new Promise (async (resolve, reject) => {
					const in_portada = 	 document.querySelector ('#portada-curso');

					if (!in_portada.files.length) {
						resolve (null); return;
					}

					const archivo = 	 in_portada.files [0];
					const timestamp = 	 new Date().toISOString().replace(/[-:.]/g,"");
					const formData = 	 new FormData();
					const directorio = 	'../../uploads/portadas/';

					formData.append ('accion', 'subir_imagen');
					formData.append ('archivo', archivo);
					formData.append ('nombre_archivo', archivo.name);
					formData.append ('timestamp', timestamp);
					formData.append ('directorio', directorio);

					await fetch ('modulos/util/gestion_archivos.php', {
						method: 'POST',
						body: formData
					})
					.then ((resp) => resp.text ())
					.then ((ruta) => {
						const nombre_archivo = ruta.split ('/').pop ();
						resolve (nombre_archivo);
					});
				});
			};

			await SubirPortada ()
			.then (async (portada) => {

				// Agregar archivo de portada al cuerpo del curso
				curso = {...curso, portada: portada};

				// Enviar petición al editor para guardar borrador, una vez guardada la portada
				await fetch ("editor.php", {
					method: "POST",
					body: JSON.stringify (curso),
					headers: {
						"Content-Type": "application/json; charset=UTF-8"
					}
				})
				.then ((resp) => resp.json())
				.then (async (json) => {
					respuesta = json;
					
					if (json.respuesta.portada_actual && 
						json.respuesta.portada_actual != json.respuesta.portada_anterior &&
						json.respuesta.portada_anterior
					) {
						await EliminarPortada (
							json.respuesta.portada_actual
						);
					}
				});
			});
			
			// Enviar petición para aplicar cambios realizados (imagenes y videos)
			let formData = new FormData ();
			formData.append ('accion', 'aplicar_cambios');
			
			await fetch ('modulos/util/gestion_archivos.php', {
				method: 'POST',
				body: formData
			})
			.then((resp) => resolve (respuesta));
		}
		// Error de conexión al intentar guardar el borrador
		catch (error) { console.log (error); reject (error); }
	});
}