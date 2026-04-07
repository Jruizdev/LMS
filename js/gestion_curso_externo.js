async function EliminarCursoExterno (id_curso, nombre) {
	const eliminarCursoBD = async (id_curso) => {
		const formData = new FormData ();
		formData.append ('accion', 'eliminar');
		formData.append ('id_curso', id_curso);

		// Realizar eliminación lógica del registro de curso externo
		await fetch ('modulos/CursosExternos.php', {
			method: 'POST',
			body: formData
		})
		.then ((resp) => resp.text())
		.then ((resultado) => { 
			if (!resultado == true) {
				MostrarMensaje (
					'Error', 
					'No se pudo eliminar el curso debido a un error de conexión. ' +
					'Por favor, inténtalo de nuevo más tarde.', 
					null, icono_error, false
				);
				return;
			}
			MostrarMensaje (
				'Curso externo eliminado', 
				'Se eliminó correctamente el curso externo.', 
				() => window.location.reload (), 
				icono_success, false
			);
		});
	};
	MostrarMensaje (
		'Eliminar curso externo', 
		'Se eliminará el curso externo <b>"' + nombre +'"</b>,<br><b>¿Deseas continuar?</b>', 
		() => eliminarCursoBD (id_curso), icono_pregunta, true, true
	)
}

function EliminarCertificacionEmpleado (curso, usuario, id_certificacion) {
	MostrarMensaje (
		'Eliminar certificación', 
		'Se eliminará la certificación "<b>' + curso +
		'</b>", del registro del empleado <b>' + usuario + 
		'</b>,<hr class="my-2 border-0"><b>¿Deseas continuar?</b>', 
		() => EliminarCertificacion (id_certificacion), 
		icono_pregunta, true, true
	)
}

async function EliminarCertificacion (id_certificacion) {

	const formData = new FormData ();
	formData.append ('accion', 'quitar_certificacion');
	formData.append ('id_certificacion', id_certificacion);

	await fetch ('modulos/CursosExternos.php', {
		method: 'POST',
		body: formData
	})
	.then ((resp) => resp.text ())
	.then ((res) => {
		if (!res) {
			MostrarMensaje (
				'No se pudo eliminar la certificación', 
				'Hubo un error al intentar eliminar la certificación. Por favor, inténtalo de nuevo más tarde.', 
				null, icono_error, false
			); return;				
		}
		MostrarMensaje (
			'Certificación eliminada', 
			'Se eliminó correctamente la certificación del registro', 
			() => window.location.reload (), icono_success, false
		);
	});
};

async function CrearCursoExterno (datos_curso) {
	const formData = new FormData ();
	
	formData.append ('accion', 			'crear');
	formData.append ('nombre', 			datos_curso ['nombre']);
	formData.append ('descripcion', 	datos_curso ['descripcion']);
	formData.append ('fecha', 		   	datos_curso ['fecha']);
	formData.append ('tiene_vigencia', 	datos_curso ['tiene_vigencia']);
	formData.append ('vigencia', 		datos_curso ['vigencia']);
	formData.append ('unidad', 			datos_curso ['unidad']);
	
	// Registrar curso externo en la BD
	await fetch ('modulos/CursosExternos.php', {
		method: 'POST',
		body: formData
	})
	.then ((resp) => resp.text())
	.then ((res) => {
		if (!res) return;
		
		MostrarMensaje (
			'Curso externo creado', 
			'Se creó correctamente el curso externo.', 
			() => window.location.reload(), 
			icono_success
		);
	})
	.catch ((error) => console.error (error));
}
