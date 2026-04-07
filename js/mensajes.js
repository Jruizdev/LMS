/* 	La variable 'mensaje' se define en el cuerpo de 
//	la página donde se mostrará el mensaje. 
*/
const icono_error = 		'<svg class="me-4 my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="60"><path fill="#DDD" d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480L40 480c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24l0 112c0 13.3 10.7 24 24 24s24-10.7 24-24l0-112c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>';
const icono_error_usuario = '<svg class="me-4 my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="60"><path fill="#DDD" d="M112 48a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm40 304l0 128c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-223.1L59.4 304.5c-9.1 15.1-28.8 20-43.9 10.9s-20-28.8-10.9-43.9l58.3-97c17.4-28.9 48.6-46.6 82.3-46.6l29.7 0c33.7 0 64.9 17.7 82.3 46.6l44.9 74.7c-16.1 17.6-28.6 38.5-36.6 61.5c-1.9-1.8-3.5-3.9-4.9-6.3L232 256.9 232 480c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-128-16 0zM432 224a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm0 240a24 24 0 1 0 0-48 24 24 0 1 0 0 48zm0-192c-8.8 0-16 7.2-16 16l0 80c0 8.8 7.2 16 16 16s16-7.2 16-16l0-80c0-8.8-7.2-16-16-16z"/></svg>';
const icono_success = 		'<svg class="me-4 my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="60"><path fill="#DDD" d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L369 209z"/></svg>';
const icono_pregunta = 		'<svg class="me-4 my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="60"><path fill="#DDD" d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm169.8-90.7c7.9-22.3 29.1-37.3 52.8-37.3l58.3 0c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24l0-13.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1l-58.3 0c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg>';
const icono_delete = 		'<svg class="me-4 my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="60"><path fill="#DDD" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"/></svg>';

const accion = (mensaje ['accion'] == '') ? 
	  null : function () { window.location.replace (mensaje ['accion']) };

function MostrarMensajePendiente () {
	
	if(!mensaje) return;
	
	switch (mensaje['tipo']) {
		// Mensaje de error
		case 'error': MostrarMensajeError (); break;
		
		// Mensaje de error de usuario
		case 'error_usuario': MostrarMensajeErrUsuario (); break;
		
		// Mensaje al completar algún proceso
		case 'success': MostraMensajeSuccess (); break;
	}
}

function MostrarMensajeErrUsuario () {
	const titulo = (mensaje ['titulo']) ? mensaje ['titulo'] : 'Error';
	  
	MostrarMensaje (
		titulo,
		mensaje ['texto'],
		accion,
		icono_error_usuario
	);
}

function MostrarMensajeError () {
	const titulo = (mensaje ['titulo']) ? mensaje ['titulo'] : 'Error';
	MostrarMensaje (
		titulo, 
		mensaje ['texto'], 
		accion,
		icono_error
	);
}

function MostraMensajeSuccess () {
	MostrarMensaje (
		"Operación realizada", 
		mensaje ['texto'],
		accion,
		icono_success
	);
}

function RemoverAviso (btn) {
	const componente = btn.closest ('.aviso');
	componente.remove ();
}