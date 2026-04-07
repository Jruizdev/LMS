// Evento que se ejecutará al detectar una tabla vacía (opcional)
let eventoTablaVacia = null;

function ExcluirRegistro (btn, tabla_original, tabla_exclusion, onTablaVacia = null) {
	const icono_btn = btn.querySelector ('svg');
	const icono_agregar = '<path fill="#569c38" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>';
	
	if (icono_btn) icono_btn.innerHTML = icono_agregar;
	
	// Mover registro de empleado a la tabla de excluidos (asignación de cursos)
	MoverRegistro (btn, tabla_original, tabla_exclusion);
	
	if(!eventoTablaVacia) eventoTablaVacia = onTablaVacia;
	
	if (btn.dataset.eliminar && btn.dataset.eliminar === 'exclusion') {
		const tabla = btn.closest ('tbody');
		btn.closest ('tr').remove (); 
		
		//if (ComprobarTablaVacia (tabla) && eventoTablaVacia) eventoTablaVacia();
		EjecutarEventoTablaVacia (tabla);
		return;
	}
	
	if (btn.dataset.tipo && btn.dataset.tipo === 'no-reversible') {
		const icono = btn.querySelector ('svg');
			
		icono.innerHTML =
		'<path fill="#f00" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM184 232l144 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-144 0c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/>';
		
		btn.onclick = () => {
			const tabla = btn.closest ('tbody');
			btn.closest ('tr').remove ();
			EjecutarEventoTablaVacia (tabla);
			//if (ComprobarTablaVacia (tabla) && eventoTablaVacia) eventoTablaVacia();
		}
		return;
	}
	btn.onclick = () => IncluirRegistro (
		btn, tabla_original, tabla_exclusion
	);
}

function IncluirRegistro (btn, tabla_original, tabla_exclusion) {
	const icono = btn.querySelector ('svg');
	const icono_remover = '<path fill="#f00" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM184 232l144 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-144 0c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/>';
	
	if (icono) icono.innerHTML = icono_remover;
	
	// Regresar registro de empleado a la tabla de empleados (asignación de cursos)
	MoverRegistro (btn, tabla_exclusion, tabla_original);
	btn.onclick = () => ExcluirRegistro (btn, tabla_original, tabla_exclusion);
}

function ComprobarTablaVacia ($tabla) {
	const $padre_tabla = $tabla.closest('div');
	const $filas = 		 $tabla.querySelectorAll ('tr');
	const mensaje = 	 $padre_tabla.dataset.msg_placeholder;
	const colspan = 	 $padre_tabla.dataset.colspan;
	
	if ($filas.length == 0) {
		$tabla.insertAdjacentHTML (
		'beforeend', 
		'<tr data-elemento="placeholder">' + 
			'<td class="text-center" colspan="' + colspan + '">' + 
				'<h6>' + mensaje + '</h6>' + 
			'</td>' + 
		'</tr>'); return true;
	} return false;
}

function MoverRegistro (btn, tabla_actual, tabla_nueva) {
	
	// Mover una fila (registro) a otra tabla
	const $registro_empleado = btn.closest ('tr');
	const $tabla_actual = 	   document.querySelector ('#' + tabla_actual + ' tbody');
	const $tabla_nueva =  	   document.querySelector ('#' + tabla_nueva + ' tbody');
	
	const $placeholder = $tabla_nueva.querySelector ('tr[data-elemento="placeholder"]');
	$placeholder?.remove();
	
	$tabla_nueva.appendChild ($registro_empleado);
	EjecutarEventoTablaVacia ($tabla_actual);
}

function EjecutarEventoTablaVacia (tabla) {
	// Obtener identificador de la tabla actual
	const id_tabla = tabla.closest('div').id;
	
	if (ComprobarTablaVacia (tabla) && 
		eventoTablaVacia && 
		id_tabla != 'cursos-seleccion' &&
		id_tabla != 'resultado-curso') 
	{
		// Ejecutar acción al vaciarse la tabla actual, en caso de que se haya definido alguna
		eventoTablaVacia();
	}
}