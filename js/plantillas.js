// Obtener nombre de la página actual
let ruta = 	 window.location.pathname;
let pagina = ruta.split ("/").pop ();

// Elementos de la página que pueden ser animados
let elementos_animados = null;  

// Plantilla principal de componentes
let $plantilla = null;

// Modal para mostrar mensajes de estado
let $modal = 	  	  null;
let $elemento_modal = null;

// Elementos de la página HTML que utilizarán la plantilla
const $ventanas = 			   document.querySelectorAll	('div[data-componente="ventana"]');
const $ventanas_extendidas =   document.querySelectorAll 	('div[data-componente="ventana-extendida"]');
const $bloques = 			   document.querySelectorAll 	('div[data-componente="bloque"]');
const $busqueda_curso = 	   document.querySelectorAll 	('div[data-componente="busqueda-curso"]');
const $nota_tabla =			   document.querySelectorAll	('[data-componente="nota-tabla"]');
const $footers_ventana = 	   document.querySelectorAll 	('div[data-componente="footer-ventana"]');
const $footers_paginacion =	   document.querySelectorAll 	('div[data-componente="footer-paginacion"]')
const $listas = 			   document.querySelectorAll 	('div[data-componente="lista"]');
const $comp_titulo_principal = document.querySelector 		('div[data-componente="comp-titulo-principal"]');
const $stylesheet = 		   document.querySelectorAll	('link[rel="stylesheet"]');
const $page_loader = 		   document.createElement 		('div');

function paginaMostrada (evt){
	// Recarcar página al acceder desde el historial, para revalidar la sesión
	if (evt.persisted) {
		window.location.reload ();
	}
}

// Listener para evitar el robo de sesiones cerradas
window.addEventListener ("pageshow", paginaMostrada, false);

// Agregar preloader a la página
AgregarPreloader ();

// Recuperar componentes de la plantilla que pueden ir en el contenido principal
var $temp_preloader =			  null;
var $temp_ventana = 			  null;
var $temp_ventana_extendida =     null;
var $temp_bloque = 			  	  null;
var $temp_navbar = 			  	  null;
var $temp_navbar_sesion_admin =   null;
var $temp_busqueda_curso = 	  	  null;
var $temp_footer_ventana = 	  	  null;
var $temp_footer_paginacion =     null;
var $temp_lista = 			  	  null;
var $temp_admin_curso_i =		  null;
var $temp_admin_curso_e =		  null;
var $temp_empleado_asignar =	  null;
var $temp_empleado_desasignar =   null;
var $temp_curso_externo =		  null;
var $temp_curso_asignar = 	  	  null;
var $temp_curso_aprobado = 	  	  null;
var $temp_CP_admin =			  null;
var $temp_CC_admin =			  null;
var $temp_admin_CE =			  null;
var $temp_usuario_CP = 		  	  null;
var $temp_usuario_cert =		  null;
var $temp_usuario_apr =		  	  null;
var $temp_mod_msg =				  null;
var $temp_mod_certificado =		  null;
var $temp_comp_titulo_principal = null;
var $temp_toolbar_texto =		  null;
var $temp_pregunta =			  null;
var $temp_pregunta_evaluacion =	  null;
var $temp_opcion_evaluacion =	  null;
var $temp_ventana_eliminable = 	  null;
var $temp_opcion = 				  null;
var $temp_tooltip_input =		  null;
var $temp_ventana_flotante = 	  null;
var $temp_historial_versiones =   null;
var $temp_tag_eliminable =		  null;

function AgregarPreloader () {
	// Elemento de carga de página
	$page_loader.classList.add ('d-flex', 'flex-column');
	$page_loader.id = 'page-loader';
	$page_loader.innerHTML =
	'<div id="loader-logo" class="d-flex m-auto" style="">' +
		'<svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 731 386"><g fill="#F00" transform="translate(0.000000,386.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M5 3778 c-3 -7 -4 -386 -3 -843 l3 -830 60 3 c232 9 477 147 621 348 97 135 143 272 151 450 11 253 -72 470 -244 635 -106 102 -166 141 -293 191 -105 41 -286 69 -295 46z"/><path d="M1450 1905 l0 -395 470 0 470 0 0 280 0 280 -327 2 -328 3 -3 113 -3 112 -139 0 -140 0 0 -395z m660 -100 l0 -125 -190 0 -190 0 0 125 0 125 190 0 190 0 0 -125z"/><path d="M5640 1905 l0 -395 140 0 140 0 0 395 0 395 -140 0 -140 0 0 -395z"/><path d="M2500 1790 l0 -280 470 0 470 0 0 280 0 280 -470 0 -470 0 0 -280z m660 15 l0 -125 -190 0 -190 0 0 125 0 125 190 0 190 0 0 -125z"/><path d="M3540 1905 l0 -165 335 0 335 0 0 -30 0 -30 -335 0 -335 0 0 -85 0 -85 475 0 475 0 0 175 0 175 -335 0 -335 0 0 35 0 35 335 0 335 0 0 70 0 70 -475 0 -475 0 0 -165z"/><path d="M4590 2000 l0 -70 330 0 330 0 0 -35 0 -35 -330 0 -330 0 0 -175 0 -175 470 0 470 0 0 280 0 280 -470 0 -470 0 0 -70z m660 -290 l0 -30 -190 0 -190 0 0 30 0 30 190 0 190 0 0 -30z"/><path fill="#105BDE" d="M6373 2055 c-89 -39 -80 -188 13 -214 48 -13 82 -5 116 27 56 52 42 154 -27 186 -40 19 -60 20 -102 1z m108 -41 c35 -37 33 -98 -4 -130 -77 -66 -170 22 -122 116 17 32 27 38 71 39 21 1 38 -7 55 -25z"/><path fill="#105BDE" d="M6644 2061 c-39 -17 -64 -60 -64 -110 0 -83 62 -132 140 -111 52 14 98 80 55 80 -8 0 -15 -6 -15 -14 0 -21 -39 -46 -71 -46 -33 0 -79 35 -79 61 0 17 8 19 89 19 62 0 90 4 93 13 9 23 -12 77 -36 92 -30 20 -85 27 -112 16z m93 -36 c11 -8 23 -26 25 -40 l5 -25 -78 0 c-67 0 -79 2 -79 17 0 50 80 81 127 48z"/><path fill="#105BDE" d="M6915 2059 c-11 -7 -23 -17 -27 -23 -5 -7 -8 -4 -8 7 0 9 -7 17 -15 17 -12 0 -15 -19 -15 -110 0 -91 3 -110 15 -110 12 0 15 16 15 80 0 91 17 120 68 120 49 0 62 -26 62 -118 0 -63 3 -82 14 -82 10 0 15 20 17 79 5 93 22 121 75 121 49 0 64 -28 64 -122 0 -63 3 -78 15 -78 12 0 15 16 15 86 0 82 -1 87 -30 116 -27 26 -36 30 -68 25 -20 -4 -45 -16 -54 -28 l-18 -21 -17 21 c-21 26 -80 36 -108 20z"/><path fill="#105BDE" d="M6110 2031 c-13 -25 -4 -48 21 -56 38 -12 65 35 37 63 -18 18 -46 14 -58 -7z"/><path fill="#105BDE" d="M6110 1889 c-10 -18 -9 -25 8 -41 27 -28 65 -9 60 30 -4 36 -51 44 -68 11z"/><path d="M12 1772 c-10 -7 -12 -182 -10 -843 l3 -834 60 2 c307 11 623 246 726 540 109 313 38 649 -186 879 -128 131 -264 206 -430 238 -127 24 -149 26 -163 18z"/></g></svg>' +
		'<div></div>' +
	'</div>';
	document.body.appendChild ($page_loader);
}

async function obtenerPlantilla (ruta) {

	let respuesta = await fetch (ruta);
	let contenido = await respuesta.text ();
	
	// Recuperar contenido de la plantilla desde los recursos del proyecto
	let html = new DOMParser ().parseFromString (contenido, 'text/html');
	return html.querySelector ('head');
}

function importarScript (ruta) {
	let script = document.createElement ("script");
	script.src = ruta;
	document.querySelector ("body").appendChild (script);
}

function RemoverLoader () {
	setTimeout (() => {
		if ($page_loader) $page_loader.remove();
		
		if (typeof $elementos_animados === 'undefined' || !$elementos_animados) return;
		// Animar elementos, una vez cargada la página
		/*
			Se esperará más tiempo en el editor y el visualizador de
			cursos para permitir la carga del contenido.
		*/
		$elementos_animados.forEach (elemento => {
			if (typeof mensaje != 'undefined' && mensaje ['tipo']) {
				elemento.style.animation = 'none';
				return;
			}
			elemento.style.animationPlayState = 'running'; // Reanuda las animaciones
		});
	}, 500);
};

// Remover preloader al detectar la carga de la hoja de estilos
if ($stylesheet  [1].sheet) 		RemoverLoader ();
else $stylesheet [1].onload = () => RemoverLoader ();

document.addEventListener ("DOMContentLoaded", async () => {

	// Importar script para la barra de navegación
	importarScript ('js/navbar.js');
	importarScript ('js/interfaz_carga.js');

	// Pausar animaciones de los elementos
	$elementos_animados = document.body.querySelectorAll ('div:not(#page-loader, .spinner-grow)');
	$elementos_animados.forEach (elemento => {
		elemento.style.animationPlayState = 'paused';
	});
	
	// Recuperar componentes de la plantilla que pueden ir en el contenido principal
	$plantilla = 				  await obtenerPlantilla ('plantillas/componentes.php');
	$temp_ventana = 			  $plantilla.querySelector ('#ventana');
	$temp_ventana_extendida = 	  $plantilla.querySelector ('#ventana-extendida');
	$temp_bloque = 				  $plantilla.querySelector ('#bloque');
	$temp_navbar = 				  $plantilla.querySelector ('#navbar');
	$temp_navbar_sesion_admin =   $plantilla.querySelector ('#navbar-sesion-admin');
	$temp_busqueda_curso = 		  $plantilla.querySelector ('#busqueda-curso');
	$temp_footer_ventana = 		  $plantilla.querySelector ('#footer-ventana');
	$temp_footer_paginacion = 	  $plantilla.querySelector ('#footer-paginacion');
	$temp_lista = 				  $plantilla.querySelector ('#lista');
	$temp_admin_curso_i =		  $plantilla.querySelector ('#admin-curso-i');
	$temp_admin_curso_e =		  $plantilla.querySelector ('#admin-curso-e');
	$temp_curso_e =				  $plantilla.querySelector ('#curso-e');
	$temp_empleado_asignar =	  $plantilla.querySelector ('#empleado-asignar');
	$temp_empleado_desasignar =   $plantilla.querySelector ('#empleado-desasignar');
	$temp_curso_externo =		  $plantilla.querySelector ('#curso-externo');
	$temp_curso_asignar = 		  $plantilla.querySelector ('#curso-asignar');
	$temp_curso_aprobado = 		  $plantilla.querySelector ('#curso-aprobado');
	$temp_CP_admin =			  $plantilla.querySelector ('#CP-admin');
	$temp_CC_admin =			  $plantilla.querySelector ('#CC-admin');
	$temp_admin_CE =			  $plantilla.querySelector ('#admin-CE');
	$temp_usuario_CP = 			  $plantilla.querySelector ('#usuario-CP');
	$temp_usuario_cert =		  $plantilla.querySelector ('#usuario-cert');
	$temp_usuario_apr =			  $plantilla.querySelector ('#usuario-apr');
	$temp_borrador =			  $plantilla.querySelector ('#borrador');
	$temp_mod_msg =				  $plantilla.querySelector ('#mod-msg');
	$temp_mod_certificado =		  $plantilla.querySelector ('#mod-certificado');
	$temp_comp_titulo_principal = $plantilla.querySelector ('#comp-titulo-principal');
	$temp_toolbar_texto =		  $plantilla.querySelector ('#toolbar-texto');
	$temp_tooltip_input = 		  $plantilla.querySelector ('#tooltip-input');
	$temp_pregunta_evaluacion =	  $plantilla.querySelector ('#pregunta-evaluacion');
	$temp_ventana_flotante =	  $plantilla.querySelector ('#ventana-flotante');
	$temp_historial_versiones =	  $plantilla.querySelector ('#comp-historial-ver');
	$temp_tag_eliminable =		  $plantilla.querySelector ('#tag-eliminable');
	
	// Agregar listas de elementos
	$listas.forEach (($lista) => {
		AgregarElemento ($lista, $temp_lista);		 // Crear elemento de lista
		AgregarElementosLista ($lista, $plantilla);	 // Recuperar elementos de la lista y agregarlos

		if ($lista.dataset.orientacion && $lista.dataset.orientacion == 'columna') {
			const contenedor = $lista.querySelector ('.contenedor-lista');
			const componente_lista = $lista.querySelector ('ul');

			componente_lista.classList.add ('d-flex','flex-column', 'px-5', 'pt-5', 'pb-5');
			componente_lista.style.gap = '40px';
			contenedor.style.backgroundColor = 'var(--fondo)';
		} 
		else if ($lista.dataset.orientacion && $lista.dataset.orientacion == 'rejilla') {
			const contenedor = $lista.querySelector ('.contenedor-lista');
			const componente_lista = $lista.querySelector ('ul');

			componente_lista.style.display = 'grid';
			componente_lista.style.gridTemplateRows = 'auto';
			componente_lista.style.gap = '40px';
			contenedor.classList.add ('p-5');
			contenedor.style.backgroundColor = 'var(--fondo)';
		}
	});
	
	// Agregar ventanas por defecto, utilizando la plantilla
	$ventanas.forEach (($elemento) => {
		const $contenido = $elemento.querySelector ('div[data-tipo="contenido"]');
		AgregarVentana ($contenido, $elemento.dataset.titulo, $temp_ventana);
	});
	
	// Agregar ventanas extendidas (ventanas más grandes)
	$ventanas_extendidas.forEach (($ventana) => {
		const $contenido = $ventana.querySelector ('div[data-tipo="contenido"]');
		AgregarVentana ($contenido, $ventana.dataset.titulo, $temp_ventana_extendida, false, $ventana.dataset.xl);
	});
	
	// Agregar ventanas de tipo "bloque"
	$bloques.forEach (($bloque) => {
		const $contenido = $bloque.querySelector('div[data-tipo="contenido"]');
		AgregarVentana ($contenido, $bloque.dataset.titulo, $temp_bloque, true);
	});

	// Agregar notas de tablas
	$nota_tabla.forEach (($nota) => {
		AgregarNotaTabla ($nota);
	});
	
	// Agregar barras de búsqueda de cursos de las ventanas
	$busqueda_curso.forEach (($barra) => {
		AgregarBarraBusqueda ($barra, $temp_busqueda_curso);
	});
	
	// Agregar footers de las ventanas (ver más elementos)
	$footers_ventana.forEach (($footer) => {
		AgregarFooter ($footer, $temp_footer_ventana);
	});
	
	// Agregar footers de paginación de las ventanas
	$footers_paginacion.forEach (($footer) => {
		AgregarFooter ($footer, $temp_footer_paginacion);
	});
	
	if($comp_titulo_principal) {
		AgregarElemento ($comp_titulo_principal, $temp_comp_titulo_principal);
	}
	
	const listas_tablas = [
		{tipo_tabla: 'tabla-admin-cursos-e', 	tipo_elemento: 'admin-curso-e', 		plantilla_elemento: $temp_admin_curso_e},
		{tipo_tabla: 'tabla-admin-cursos-e', 	tipo_elemento: 'curso-e',		 		plantilla_elemento: $temp_curso_e},
		{tipo_tabla: 'tabla-empleados-simple', 	tipo_elemento: 'empleado-asignar', 		plantilla_elemento: $temp_empleado_asignar},
		{tipo_tabla: 'tabla-empleados-simple', 	tipo_elemento: 'empleado-desasignar', 	plantilla_elemento: $temp_empleado_desasignar},
		{tipo_tabla: 'tabla-externos', 			tipo_elemento: 'curso-externo', 		plantilla_elemento: $temp_curso_externo},
		{tipo_tabla: 'tabla-admin-cursos-i',	tipo_elemento: 'curso-asignar', 		plantilla_elemento: $temp_curso_asignar},
		{tipo_tabla: 'tabla-admin-cursos-i',	tipo_elemento: 'admin-curso-i', 		plantilla_elemento: $temp_admin_curso_i},
		{tipo_tabla: 'tabla-cursos-aprobados', 	tipo_elemento: 'curso-aprobado', 		plantilla_elemento: $temp_curso_aprobado},
		{tipo_tabla: 'tabla-CP-admin', 			tipo_elemento: 'CP-admin', 				plantilla_elemento: $temp_CP_admin},
		{tipo_tabla: 'tabla-CC-admin', 			tipo_elemento: 'CC-admin', 				plantilla_elemento: $temp_CC_admin},
		{tipo_tabla: 'tabla-admin-CE', 			tipo_elemento: 'admin-CE', 				plantilla_elemento: $temp_admin_CE},
		{tipo_tabla: 'tabla-admin-Cert', 		tipo_elemento: 'admin-CE', 				plantilla_elemento: $temp_admin_CE},
		{tipo_tabla: 'tabla-usuarios-CP', 		tipo_elemento: 'usuario-CP', 			plantilla_elemento: $temp_usuario_CP},
		{tipo_tabla: 'tabla-usuarios-cert', 	tipo_elemento: 'usuario-cert', 			plantilla_elemento: $temp_usuario_cert},
		{tipo_tabla: 'tabla-usuarios-apr', 		tipo_elemento: 'usuario-apr',			plantilla_elemento: $temp_usuario_apr},
		{tipo_tabla: 'tabla-borradores', 		tipo_elemento: 'borrador',				plantilla_elemento: $temp_borrador}
	];
	
	// Obtener notas en el contenido
	const $notas = 	typeof $editor == 'undefined' ? 
					[]:  document.querySelectorAll ('.nota');
	// Obtener imágenes y videos al estar en el editor de cursos
	const $elementos_multimedia = 	typeof $editor == 'undefined' ? 
									[]:  document.querySelectorAll ('video, img');
	
	listas_tablas.forEach (({tipo_tabla, tipo_elemento, plantilla_elemento}) => {
		AgregarListaTabla (tipo_tabla, tipo_elemento, plantilla_elemento);
	});
	
	$notas.forEach (($nota) => {
		// Configurar elementos de nota
		const $btn_eliminar = 			document.createElement ('button');
		$btn_eliminar.contentEditable = false;
		$btn_eliminar.className =		'btn-cerrar';

		$btn_eliminar.onclick = () => {
			// Agregar botón para eliminar formato de nota
			const $contenedor = $btn_eliminar.parentElement;
			$btn_eliminar.remove ();
			const contenidoNota = $contenedor.innerHTML;
			$contenedor.insertAdjacentHTML ('afterend', contenidoNota);
			$contenedor.remove ();
		}
		$btn_eliminar.setAttribute ('title', 'Remover estilo de nota');
		$nota.appendChild ($btn_eliminar);
	});
	
	$elementos_multimedia.forEach (($multimedia) => {
		// Remover elemento en caso de que no exista su recurso (imagen o video)
		existeRecurso ($multimedia.dataset.src).then((existe) => {
			if(!existe) { $multimedia.remove(); return; }

			// Evitar que las imágenes y los videos puedan ser arrastrados
			$multimedia.setAttribute ('draggable', 'false');
			$multimedia.parentElement.style.position = 'relative';
			
			// Configurar la imegen para que sea reescalable y alineable
			if ($multimedia.tagName == 'IMG') {
				HacerElementoReescalable 	($multimedia);
				HacerElementoAlineable 		($multimedia);
				HacerElementoProporcionable ($multimedia);
			}
			
			const $btn_eliminar = document.createElement ('button');
			const titulo = $multimedia.tagName === 'IMG' ? 
							'Eliminar imagen' :
							'Eliminar video';
					
			// Agregar botón para eliminar componente
			$btn_eliminar.classList.add ('btn-float');
			$btn_eliminar.setAttribute  ('title', titulo);
			$btn_eliminar.setAttribute  ('onclick', 'EliminarComponente (this)');
			$btn_eliminar.innerHTML = iconos.remover;
			$multimedia.insertAdjacentElement ('beforebegin', $btn_eliminar);
		});
	});
	
	// Agregar modal para mostrar mensajes de estado
	AgregarElemento (document.querySelector ('body'), $temp_mod_msg);
	
	// Configurar modal
	$elemento_modal = document.querySelector ('#modal-msg');
	$modal = 		  new bootstrap.Modal ($elemento_modal);
	
	// Mostrar mensaje pendiente al cargar página, en caso de que exista alguno
	if (typeof MostrarMensajePendiente == 'function') {
		setTimeout (MostrarMensajePendiente, 500);
	}
	
	// Agregar preguntas de evaluación al Modal (Visualizador de cursos internos)
	AgregarPreguntasEvaluacionModal ();
	
	// Agregar preguntas de evaluación a Ventana (Consulta de evaluación)
	AgregarPreguntasEvaluacionVentana ();
	
	// Agregar eventos a controles de selección en tiempo real
	AgregarListeners ();

	// Recuperar imagen de empleado (al crear usuario)
	RecuperarImagenEmpleado ();

	// Recuperar curso dependiente (cursos modulares, editor)
	RecuperarCursoDependiente ();
});

function RecuperarCursoDependiente () {
	// Se encuentra fuera del editor de cursos
	if (typeof Id_curso_dependiente === 'undefined' || !Id_curso_dependiente) return;

	// Obtener selector de configuración para curso modular
	const selector_cm = document.querySelector ('#seccion-curso-modular select');
	const in_curso_previo = document.querySelector ('#curso-previo');
	
	const collapse_seccion_modular = new bootstrap.Collapse (
		document.querySelector ('#conf-curso-modular'), { toggle: false }
	);

	// Recuperar configuración del curso modular
	collapse_seccion_modular.show ();
	selector_cm.value = '1';
	in_curso_previo.dataset.id = Id_curso_dependiente; 
	in_curso_previo.value = Curso_dependiente;
}

function HacerElementoProporcionable (elemento) {
	const btn_proporcion = document.createElement ('button');
	const proporcional = elemento.dataset.proporcional;

	btn_proporcion.classList.add ('btn-proporcion', 'p-0');
	btn_proporcion.innerHTML = 	proporcional ? 
								iconos.proporcional : 
								iconos.no_proporcional;

	btn_proporcion.onclick = () => {
		if (elemento.hasAttribute ('data-proporcional')) {
			elemento.removeAttribute ('data-proporcional');
			btn_proporcion.innerHTML = iconos.no_proporcional;
		}
		else {
			elemento.setAttribute ('data-proporcional', 'true');
			btn_proporcion.innerHTML = iconos.proporcional;
		};
	}
	elemento.insertAdjacentElement ('afterend', btn_proporcion);
}

function HacerElementoAlineable (elemento) {
	const alineacion_in = document.createElement ('details');

	alineacion_in.setAttribute ('title', 'Cambiar posición de la imagen');
	alineacion_in.setAttribute ('draggable', 'false');
	alineacion_in.classList.add ('btn-alineacion', 'd-flex', 'flex-column', 'm-2');

	// Evitar selección del elemento
	elemento.style.userSelect = 'none';

	// Menú de alineación
	const opciones = document.createElement ('div');
	const btn_alinear_i = 	  document.createElement ('button');
	const btn_alinear_c = 	  document.createElement ('button');
	const btn_alinear_d = 	  document.createElement ('button');
	const alineacion_actual = document.createElement ('summary');

	alineacion_actual.onclick = () => {

		// Reiniciar animaciones de los botones
		btn_alinear_i.classList.add ('animar');
		btn_alinear_c.classList.add ('animar');
		btn_alinear_d.classList.add ('animar');

		if (document.querySelector ('#backdrop-seleccion')) return;

		// Crear backdrop para cerrar menú al dar click fuera de las opciones
		const backdrop = document.createElement ('div');
		backdrop.id = 					 'backdrop-seleccion';
		backdrop.style.position = 		 'fixed';
		backdrop.style.width = 			 '100%';
		backdrop.style.height = 		 '100%';
		backdrop.style.backgroundColor = 'transparent';
		backdrop.style.zIndex = 		 '0';

		backdrop.onclick = () => {
			const detalles = elemento.parentElement.querySelector ('details');

			// Cerrar detalles, en caso de que estén abiertos
			if (detalles.hasAttribute ('open')) alineacion_actual.click ();
			backdrop.remove ();
		}
		document.body.appendChild (backdrop);
	};

	opciones.classList.add ('opc-alineacion');
	alineacion_actual.classList.add ('d-flex', 'm-auto', 'p-2');
	alineacion_actual.innerHTML = iconos.desplegar + iconos.alineacion_centrada;

	// Configurar botones de alineación
	btn_alinear_i.innerHTML = 	iconos.alineacion_izquierda;
	btn_alinear_c.innerHTML = 	iconos.alineacion_centrada;
	btn_alinear_d.innerHTML = 	iconos.alineacion_derecha;

	btn_alinear_i.setAttribute ('title', 'Alinear a la izquierda');
	btn_alinear_c.setAttribute ('title', 'Centrar');
	btn_alinear_d.setAttribute ('title', 'Alinear a la derecha');

	btn_alinear_i.onclick = () => CambiarPosicionElemento (elemento, 'izquierda', alineacion_actual);
	btn_alinear_c.onclick = () => CambiarPosicionElemento (elemento, 'centrado', alineacion_actual);
	btn_alinear_d.onclick = () => CambiarPosicionElemento (elemento, 'derecha', alineacion_actual);

	btn_alinear_i.style.transform = 'translate(0)';
	btn_alinear_c.style.transform = 'translate(0)';
	btn_alinear_d.style.transform = 'translate(0)';

	btn_alinear_i.addEventListener ('animationend', () => btn_alinear_i.classList.remove ('animar'));
	btn_alinear_c.addEventListener ('animationend', () => btn_alinear_c.classList.remove ('animar'));
	btn_alinear_d.addEventListener ('animationend', () => btn_alinear_d.classList.remove ('animar'));

	opciones.appendChild (btn_alinear_i);
	opciones.appendChild (btn_alinear_c);
	opciones.appendChild (btn_alinear_d);
	alineacion_in.appendChild (opciones);
	alineacion_in.appendChild (alineacion_actual);

	alineacion_in.style.position = 'absolute';
	alineacion_in.style.top =  '0';
	alineacion_in.style.left = '0';

	elemento.insertAdjacentElement ('afterend', alineacion_in);
}

function HacerElementoReescalable(elemento) {
    const boton_resize = document.createElement ('button');
    boton_resize.classList.add ('btn-reescalar');
    boton_resize.style.position = 	 'absolute';
    boton_resize.style.top = 	  	 '100%';
    boton_resize.style.right = 	  	 '0';
    boton_resize.innerHTML = 	  	 iconos.reescalar;
    boton_resize.style.cursor =   	 'nw-resize';
    boton_resize.style.touchAction = 'none'; 
    elemento.insertAdjacentElement ('afterend', boton_resize);

    var element = null;
    var startX, startY, startWidth, startHeight;
    var proporcion_inicial = null;
    var proporcional = null;

    function getClientCoords(e) {
        if (e.touches && e.touches.length > 0) {
            return {
                clientX: e.touches[0].clientX,
                clientY: e.touches[0].clientY
            };
        }
        return {
            clientX: e.clientX,
            clientY: e.clientY
        };
    }

    function initDrag(e) {
        e.preventDefault(); // Previene el comportamiento por defecto en móviles
        element = elemento;

        const coords = getClientCoords(e.type.includes('touch') ? e.touches[0] : e);
        startX = coords.clientX;
        startY = coords.clientY;
        startWidth = parseInt(
            document.defaultView.getComputedStyle(element).width, 10
        );
        startHeight = parseInt(
            document.defaultView.getComputedStyle(element).height, 10
        );

        // Agregar listeners para mouse y touch
        document.documentElement.addEventListener("mousemove", doDrag, false);
        document.documentElement.addEventListener("touchmove", doDrag, { passive: false });
        document.documentElement.addEventListener("mouseup", stopDrag, false);
        document.documentElement.addEventListener("touchend", stopDrag, false);

        proporcion_inicial = elemento.clientHeight / elemento.clientWidth;
        proporcional = elemento.dataset.proporcional;
    }

    function doDrag(e) {
        e.preventDefault();
        
        const coords = getClientCoords(e.type.includes('touch') ? e.touches[0] : e);
        const width_actualizado = startWidth + coords.clientX - startX;
        const proporcion_actual = elemento.clientHeight / elemento.clientWidth;
        const height_actualizado = proporcional ? 
                                  width_actualizado * proporcion_inicial : 
                                  startHeight + coords.clientY - startY;

        const ultimo_width = element.offsetWidth;
        const ultimo_height = element.offsetHeight;

        if (proporcional && proporcion_actual < (proporcion_inicial + 0.01)) {
            element.style.width = width_actualizado + 'px';
            element.style.height = height_actualizado + 'px';
        } 
        else if (proporcional && proporcion_actual > (proporcion_inicial)) {
            element.style.width = ultimo_width + 'px';
            element.style.height = ultimo_height + 'px';
        }
        else if (!proporcional) {
            element.style.width = width_actualizado + 'px';
            element.style.height = height_actualizado + "px";
        }
    }

    function stopDrag() {
        // Remover todos los listeners
        document.documentElement.removeEventListener("mousemove", doDrag, false);
        document.documentElement.removeEventListener("touchmove", doDrag, false);
        document.documentElement.removeEventListener("mouseup", stopDrag, false);
        document.documentElement.removeEventListener("touchend", stopDrag, false);
    }

    // Agregar listeners para mouse y touch
    boton_resize.addEventListener("mousedown", initDrag, false);
    boton_resize.addEventListener("touchstart", initDrag, { passive: false });
}

function RecuperarImagenEmpleado () {
	const $img = document.querySelector ('#img-empleado');

	// Mostrar imagen al consultar empleado, durante la creación de un nuevo usuario
	if ((pagina === 'nuevo_usuario.php' || pagina === 'eliminar_usuario.php') && $img) {
		ObtenerFotoPerfil ($img, $img.dataset.no_nomina);
	}
}

async function existeRecurso (url) {
	const formData = new FormData ();
	formData.append ('accion', 'verificar_src');
	formData.append ('src', url);
	
	let respuesta = false;
	
	const resp = await fetch (
	'modulos/util/gestion_archivos.php', { 
		method: 'POST',
		body: formData
	})
	.then ((resp) => resp.text())
	.then ((existe) => {
		respuesta = existe != '' ? existe : false
	});
	return respuesta;
}

async function EventoSeleccionArea (evento) {
	const $tabla_empleados = document.querySelector (
		'div[data-componente="tabla-empleados-simple"] tbody'
	);
	
	// Recuperar todos los empleados activos del área seleccionada
	await fetch ('modulos/GestionUsuarios.php?obtener_area=' + evento.target.value, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json; charset=UTF-8'
		}
	})
	.then ((resp) => resp.json())
	.then ((empleados) => {
		const $tabla_body = 	 document.createElement('tbody');
		const $tabla_excluidos = document.querySelector ('#tabla-excluidos tbody');
		
		$tabla_excluidos.innerHTML = 
		'<tr data-elemento="placeholder">' + 
			'<td class="text-center" colspan="4">' + 
				'<h6>No has excluido a ningún empleado</h6>' +
			'</td>' + 
		'</tr>';
		
		if (empleados.length === 0) {
			$tabla_body.innerHTML = 
			'<tr data-elemento="placeholder">' + 
				'<td class="text-center" colspan="4">' + 
					'<h6>No se encontraron empleados en este departamento</h6>' +
				'</td>' + 
			'</tr>';
		}
		
		empleados.forEach ((empleado) => {
			// Agregar empleados a la tabla
			const $nuevo_registro = $temp_empleado_desasignar.content.cloneNode (true);
			const $no_nomina = 		$nuevo_registro.querySelector ('b[data-tipo="id-nomina"]');
			const $nombre = 		$nuevo_registro.querySelector ('td[data-tipo="nombre-empleado"]');
			const $departamento =	$nuevo_registro.querySelector ('td[data-tipo="departamento"]');
			
			$no_nomina.innerHTML = 	  empleado ['no_nomina'];
			$nombre.innerHTML = 	  empleado ['nombre'];
			$departamento.innerHTML = empleado ['area'];
			
			$tabla_body.appendChild ($nuevo_registro);
		});
		$tabla_empleados.innerHTML = $tabla_body.innerHTML;
	})
	.catch ((error) => {
		$tabla_empleados.innerHTML = 
		'<tr disabled>' + 
			'<td class="text-center" colspan="3">' + 
				'<h6>No se pudieron recuperar los registros</h6>' +
			'</td>' + 
		'</tr>';
	});
	collapse_curso.style.opacity = 0;
	bs_collapse_curso.hide();
}

function AgregarListeners () {
	const $seleccion_area = document.querySelector ('#seleccion-area');
	const $inputFile =		document.querySelector ('#portada-curso');
	
	const CargarInPortadaCurso = async () => {
		try {
			const extension_img = 			portada.split ('.').pop ();
			const formatos_img_aceptados =  $inputFile.getAttribute ('accept').split (',')
				.map ((formato) => formato.toLowerCase().trim ());
			
			// Evitar cargar archivo cuando este no haya sido seleccionado o no sea válido
			if (!formatos_img_aceptados.includes ('.' + extension_img)) return;

			// Obtener el archivo desde la ruta especificada
			const response = await fetch ('uploads/portadas/' + portada);
			const blob = 	 await response.blob();
		
			const file = new File([blob], portada.slice (19), { 
				type: blob.type,
				lastModified: new Date().getTime()
			});
			
			const dataTransfer = new DataTransfer();
			dataTransfer.items.add (file);
			
			// Asignar el archivo de portada al input del editor
			$inputFile.files = dataTransfer.files;
			
			// Disparar evento change
			$inputFile.dispatchEvent (new Event('change', { bubbles: true }));
			return true;
			
		} catch (error) {
			return false;
		}
	};
	
	// Selector de área (asignación de curso)
	$seleccion_area?.addEventListener ('change', (e) => {
		EventoSeleccionArea (e);
	});

	// Input de portada (editor)
	CargarInPortadaCurso ();
}

function AgregarPreguntasEvaluacionVentana () {
	const $preguntas_evaluacion =	document.querySelectorAll	('div[data-tipo="pregunta-evaluacion"]');
	const $contenedor = 			document.querySelector ('#contenedor-preguntas');
	
	for (let i = 0; i < $preguntas_evaluacion.length; i++) {
		
		// Crear nueva instancia de pregunta de evaluación 
		const $elemento_pregunta = 	 $preguntas_evaluacion [i].querySelector ('div[data-tipo="pregunta"]');
		const $elemento_opciones = 	 $preguntas_evaluacion [i].querySelector ('ul[data-tipo="opciones"]');
		const $opciones = 			 $elemento_opciones.querySelectorAll ('div[data-componente="opcion-evaluacion"]');
		const id_pregunta = 		 $preguntas_evaluacion [i].dataset.id_preg;
		const respuesta_usuario = 	 $preguntas_evaluacion [i].dataset.resp_usuario;
		const $nueva_pregunta = 	 $temp_pregunta_evaluacion.content.cloneNode (true);
		const $texto_pregunta = 	 $nueva_pregunta.querySelector ('p[data-tipo="preg_evaluacion"]');
		const $contenedor_opciones = $nueva_pregunta.querySelector ('ul.opciones');
		const $id_pregunta = 		 $nueva_pregunta.querySelector ('.id-pregunta span');
		
		// Mostrar id de la pregunta en la evaluación realizada
		$id_pregunta.innerHTML = id_pregunta;
		
		if (i == 0 || i == $preguntas_evaluacion.length - 1) {
			// Agregar elementos de referencia para el scroll automático
			const $elemento_pregunta = $nueva_pregunta.querySelector('.pregunta-evaluacion');
			$elemento_pregunta.dataset.pos = i == 0 ? 'elemento-top' : 'elemento-bottom';
		}
		
		$opciones.forEach (($opcion, i) => {
			const $nueva_opcion = $temp_opcion_evaluacion.content.cloneNode (true);
			const $etiqueta = 	  $nueva_opcion.querySelector ('label');
			const $radio = 		  $nueva_opcion.querySelector ('input[type="radio"]');
			
			// Desabilitar inputs de selección en la consulta de resultados de evaluación
			$radio.disabled = true;
			
			if ($opcion.textContent == respuesta_usuario || respuesta_usuario == i) {
				const $texto_opcion = $nueva_opcion.querySelector ('label');
				
				// Marcar opción seleccionada por el usuario
				$radio.setAttribute ("checked", true);
				$texto_opcion.style.color = "red";
			}
			$etiqueta.innerHTML = $opcion.innerHTML;
			$contenedor_opciones.appendChild($nueva_opcion);
		});
		$texto_pregunta.innerHTML = $elemento_pregunta.innerHTML;
		
		// Agregar separador horizontal entre preguntas
		$contenedor_opciones.insertAdjacentHTML (
			'beforeend', i < $preguntas_evaluacion.length - 1 ? 
			'<hr class="my-3">' : ''
		);
		
		// Agregar nuevo elemento y eliminar placeholder
		$contenedor.appendChild ($nueva_pregunta);
		$preguntas_evaluacion[i].remove();
		
		const $puntaje = $nueva_pregunta.querySelector ('small');
	}
}

function AgregarPreguntasEvaluacionModal () {
	let lote_preguntas = 		  [];
	let conteo = 				  0;
	let num_pags =				  0;
	const num_peg_pagina =		  5;
	const cont_preguntas = 		  document.createElement ('div');
	const $preguntas_evaluacion = document.querySelectorAll ('div[data-componente="pregunta-evaluacion"]');
	
	// Evitar generación de preguntas cuando la evaluación no se encuentre disponible
	if (typeof curso_abierto != 'undefined' && curso_abierto) return;

	for (let i = 0; i < $preguntas_evaluacion.length; i++) {
		const $nueva_pregunta = 	  $temp_pregunta_evaluacion.content.cloneNode (true);
		const $elemento = 			  $nueva_pregunta.querySelector ('.pregunta-evaluacion');
		const $elemento_preg = 		  $elemento.querySelector ('p[data-tipo="preg_evaluacion"]');
		const $contenedor_opciones =  $elemento.querySelector ('ul');
		const $id_pregunta = 		  $elemento.querySelector ('.id-pregunta span');
		const $contenedor_preguntas = document.querySelector ('#contenido-preguntas');
		const $pag_header =			  document.querySelector ('.modal-header h6');
		
		const $opciones = 			$preguntas_evaluacion[i].querySelectorAll ('div[data-componente="opcion-evaluacion"]');
		const id_pregunta = 		parseInt ($preguntas_evaluacion [i].dataset.id_preg) + 1;
		const $contenedor_paginas = document.querySelector ('.indicadores-nav');
		
		// Agregar opciones de respuesta a la pregunta
		AgregarOpcionesRespuesta ($opciones, $contenedor_opciones);

		$id_pregunta.innerHTML = 	id_pregunta;
		$elemento_preg.innerHTML = 	$preguntas_evaluacion [i].querySelector ('div[data-tipo="pregunta"]').innerHTML;
		
		lote_preguntas.push ($elemento);
		conteo++;
		
		if(conteo >= num_peg_pagina || i == $preguntas_evaluacion.length - 1) {
			const nuevo = document.createElement ('div');
			const $btn =  document.createElement ('button');
			
			// Configurar botón indicador de página
			$btn.classList.add ('indicador-pag');
			$btn.dataset.pagina = num_pags;
			$btn.onclick = () => PagIndicadorEvaluacion ($btn);
			
			nuevo.classList.add ('carousel-item');
			if (i === num_peg_pagina - 1 || 
				$preguntas_evaluacion.length - 1 < num_peg_pagina) 
			{
				nuevo.classList.add ('active');
				$btn.classList.add 	('active');				
			}	
			
			lote_preguntas.forEach ((lote) => {
				nuevo.appendChild (lote);
			});
			
			// Agregar nueva página e indicador de página
			$contenedor_preguntas.appendChild (nuevo);
			$contenedor_paginas.insertAdjacentElement ('beforeend', $btn);
			lote_preguntas = [];
			conteo = 0;
			num_pags++;
		} 
		else {
			// Agregar separador entre preguntas
			$contenedor_opciones.insertAdjacentHTML ('afterend', '<hr class="my-3">');
		}
		$pag_header.textContent = "Página: 1 de " + num_pags; // Indicar el número de páginas
		$preguntas_evaluacion[i].remove (); 				  // Eliminar placeholder
	}
}

function AgregarListaTabla (tipo_tabla, tipo_elementos, $plantilla_elementos) {
	const $tablas_temp = 	document.querySelectorAll ('div[data-componente="'+ tipo_tabla +'"]');
	
	if (!$tablas_temp) return;

	$tablas_temp.forEach (($elemento_tabla) => {
		
		const $tabla = 				$elemento_tabla.querySelector ('table');
		const $body_tabla = 		$tabla.querySelector ('tbody');
		const $elementos = 			$elemento_tabla.querySelectorAll ('div[data-componente="'+ tipo_elementos +'"]');
		const msg_placeholder = 	$elemento_tabla.dataset.msg_placeholder;
		const $tabla_placeholder = 	$elemento_tabla.querySelector ('tr[data-elemento="placeholder"] h6');
		
		if($tabla_placeholder && msg_placeholder)
			$tabla_placeholder.innerHTML = msg_placeholder;
		
		// Eliminar posible placeholder en caso de que existan registros en la tabla
		if ($elementos.length) $body_tabla.innerHTML = '';
		
		$elementos.forEach (($curso_i) => {
			let propiedades = null;
			if (tipo_elementos == 'curso-aprobado') {
				propiedades = {
					no_nomina:	  $curso_i.dataset.no_nomina,
					id_curso:	  $curso_i.dataset.id_curso,
					nombre: 	  $curso_i.dataset.nombre,
					version: 	  $curso_i.dataset.version,
					fecha: 		  $curso_i.dataset.fecha,
					intentos: 	  $curso_i.dataset.intentos,
					puntaje: 	  $curso_i.dataset.puntaje,
					puntaje_max:  $curso_i.dataset.puntaje_max,
					id_curso: 	  $curso_i.dataset.id_curso,
					colaborativo: $curso_i.dataset.colaborativo
				}
			}
			if (tipo_elementos == 'borrador') {
				propiedades = {
					nombre:   $curso_i.dataset.nombre,
					editar:   'editor.php?curso_int=' + $curso_i.dataset.id_curso + '&editar=true',
					id_curso: $curso_i.dataset.id_curso,
					id_int:   $curso_i.dataset.id_int,
					fecha_mod: $curso_i.dataset.fecha_mod
				};
			}
			if (tipo_elementos == 'admin-curso-e') {
				propiedades = {
					id_curso: 	 $curso_i.dataset.id_curso,
					id_ext:   	 $curso_i.dataset.id_ext,
					nombre:	  	 $curso_i.dataset.nombre,
					descripcion: $curso_i.dataset.descripcion,
					fecha:	  	 $curso_i.dataset.fecha,
					validez:  	 $curso_i.dataset.validez
				};
			}
			if (tipo_elementos == 'curso-e') {
				propiedades = {
					id_cert:  $curso_i.dataset.id_cert,
					nombre:	  $curso_i.dataset.nombre,
					fecha:	  $curso_i.dataset.fecha,
					validez:  $curso_i.dataset.validez
				};
			}
			if (tipo_elementos == 'usuario-CP') {
				propiedades = {
					nomina:   		$curso_i.dataset.nomina,
					id_curso: 		$curso_i.dataset.id_curso,
					nombre:   		$curso_i.dataset.nombre,
					curso: 	  		$curso_i.dataset.curso,
					version:  		$curso_i.dataset.version,
					fecha: 	  		$curso_i.dataset.fecha,
					fecha_limite: 	$curso_i.dataset.fecha_limite
				}; 
			}
			if (tipo_elementos == 'usuario-apr') {
				propiedades = {
					id_aprobado: 	$curso_i.dataset.id_aprobado,
					id_curso: 		$curso_i.dataset.id_curso,
					empleado: 		$curso_i.dataset.empleado,
					curso: 			$curso_i.dataset.curso,
					version: 		$curso_i.dataset.version,
					aprobado: 		$curso_i.dataset.aprobado,
					asignado: 		$curso_i.dataset.asignado,
					intentos: 		$curso_i.dataset.intentos,
					puntaje: 		$curso_i.dataset.puntaje,
					puntaje_max:	$curso_i.dataset.puntaje_max,
					colaborativo:	$curso_i.dataset.colaborativo
				};
			}
			if (tipo_elementos == 'usuario-cert') {
				propiedades = {
					id_certificacion: 	$curso_i.dataset.id_certificacion,
					fecha: 				$curso_i.dataset.fecha,
					no_nomina: 			$curso_i.dataset.no_nomina,
					id_curso: 			$curso_i.dataset.id_curso,
					validez: 			$curso_i.dataset.validez,
					empleado: 			$curso_i.dataset.empleado,
					curso: 				$curso_i.dataset.curso
				};
			}
			if (tipo_elementos == 'admin-curso-i') {
				propiedades = {
					nombre:   $curso_i.dataset.nombre,
					usuario:  $curso_i.dataset.usuario,
					version:  $curso_i.dataset.version,
					fecha: 	  $curso_i.dataset.fecha,
					id_curso: $curso_i.dataset.id_curso
				};
			}
			if (tipo_elementos == 'CP-admin') {
				propiedades = {
					id_curso: 	$curso_i.dataset.id_curso,
					usuario:	$curso_i.dataset.usuario,
					curso: 		$curso_i.dataset.curso,
					version: 	$curso_i.dataset.version,
					fecha: 		$curso_i.dataset.fecha
				};
			}
			if (tipo_elementos == 'CC-admin') {
				propiedades = {
					id_curso: 	$curso_i.dataset.id_curso,
					curso:		$curso_i.dataset.curso,
					version: 	$curso_i.dataset.version
				};
			}
			if (tipo_elementos == 'admin-CE') {
				propiedades = {
					id_cert: 	$curso_i.dataset.id_cert,
					usuario:	$curso_i.dataset.usuario,
					curso: 		$curso_i.dataset.curso,
					fecha: 		$curso_i.dataset.fecha,
					vigencia: 	$curso_i.dataset.vigencia,
					nombre_emp: $curso_i.dataset.nombre_empleado
				};
			}
			if ($curso_i.dataset.msg_placeholder != null) {
				// Colocar placehoder, en caso de que se haya definido uno
				const colspan = 	 $curso_i.dataset.colspan;
				const msg = 		 $curso_i.dataset.msg_placeholder;
				const $placeholder = document.createElement ('tr');
				const $tbody = 		 $elemento_tabla.querySelector ('table tbody');
				
				$tbody.innerHTML = 				'';
				$placeholder.dataset.elemento = 'placeholder';
				$placeholder.innerHTML = 
				'<td colspan="' + colspan + '">' + 
					'<h6 class="text-center">' + msg + '</h6>' + 
				'</td>';
				$tbody.appendChild ($placeholder);
				return;
			}
		
			// Agregar cursos enlistados a la tabla
			AgregarElemento ($body_tabla, $plantilla_elementos, tipo_elementos, propiedades);
		});

		const $elemento_vacio = $elemento_tabla.querySelector ('[data-tipo="vacio"]');
		
		if ($elemento_vacio) {
			// Comprobar si hay elemento de tabla vacía
			const $mensaje_row = document.createElement ('tr');
			const $mensaje_col = document.createElement ('td');

			$mensaje_col.setAttribute ('colspan', $elemento_vacio.dataset.colspan);		
			$mensaje_col.appendChild ($elemento_vacio);
			$mensaje_row.appendChild ($mensaje_col);
			$body_tabla.appendChild ($mensaje_row);
		}

	});
}

function AgregarCursosDisponibles ($elementos_cursos, $contenedor) {
	const $temp_curso_disponible = 	$plantilla.querySelector ('#curso-disponible');	// Elementos de Cursos disponibles
	let anim_delay = 				0.3;
	
	$elementos_cursos.forEach (($curso) => {
		AgregarElemento ($curso, $temp_curso_disponible);

		// Obtener propiedades del componente
		const nombre_curso = 	$curso.dataset.nombre;
		const descripcion = 	$curso.dataset.descripcion;
		const fecha = 			$curso.dataset.fecha;
		const portada =			$curso.dataset.portada;

		// Obtener elementos del componente
		const $descripcion = 	$curso.querySelector	('[data-tipo="descripcion"]');
		const $titulo = 		$curso.querySelector 	('[data-tipo="nombre-curso"]');
		const $fecha = 			$curso.querySelector 	('[data-tipo="fecha"]');

		// Asignar propiedades a los elementos
		if ($titulo) 	  $titulo.textContent = 	 nombre_curso;
		if ($descripcion) $descripcion.textContent = descripcion;
		if ($fecha) 	  $fecha.textContent = 		 fecha;

		// Agregar portada al curso (en caso de que exista)
		if (portada && portada != '') {
			const elemento_portada = $curso.querySelector ('svg.portada');
			const img_portada = document.createElement ('img');

			img_portada.classList.add ('portada');
			img_portada.src = 'uploads/portadas/' + portada;
			elemento_portada?.replaceWith (img_portada);
		}

		$curso.classList.add ('curso-disponible', 'shadow');
		$curso.style.animationDelay = anim_delay + 's';
		$curso.style.animationPlayState = 'running';
		$curso.style.animationIterationCount = '1';

		// Agregar componente a la lista
		$contenedor.classList.add ('cursos-disponibles');
		$contenedor.appendChild ($curso);
		anim_delay += 0.1;
	});
}

function AgregarCursosPendientes ($elementos_cursos, $contenedor) {
	const $temp_elemento_lista_CP = $plantilla.querySelector ('#elemento-lista-CP'); // Elementos de Cursos pendientes
	
	$elementos_cursos.forEach (($elemento) => {
		AgregarElemento ($elemento, $temp_elemento_lista_CP);
		
		// Obtener propiedades del componente
		const nombre_curso = $elemento.dataset.nombre;
		const descripcion =	 $elemento.dataset.descripcion;
		const version =		 $elemento.dataset.version;
		const id_curso =	 $elemento.dataset.id_curso;
		const fecha_limite = $elemento.dataset.fecha_limite;
		const asignacion = 	 $elemento.dataset.asignacion;
		const empleado =	 $contenedor.parentElement.parentElement.dataset.no_nomina;
		const portada =		 $elemento.dataset.portada;
		
		// Obtener elementos del componente
		const $descripcion = $elemento.querySelector ('[data-tipo="descripcion"]');
		const $titulo =		 $elemento.querySelector ('[data-tipo="nombre-curso"]');
		const $version =	 $elemento.querySelector ('[data-tipo="version"]');
		const $ver_curso =	 $elemento.querySelector ('.btn-ver-curso');
		const $portada =	 $elemento.querySelector ('a.portada');
		
		if (fecha_limite) {
			// Agregar indicador de fecha límite (en caso de que haya una fecha límite)
			const limite = new Date (fecha_limite + 'T00:00:00.000-06:00');
			const fecha_actual = new Date ();

			if (fecha_actual > limite) 
				$elemento.querySelector ('li').classList.add ('curso-vencido');
			
			const info_fecha_lim = document.createElement ('div');

			info_fecha_lim.classList.add ('ms-auto', 'shadow', 'px-3', 'py-2', 'mb-4');
			info_fecha_lim.style.borderRadius = '5px';
			info_fecha_lim.style.backgroundColor = 'var(--light)';
			info_fecha_lim.innerHTML = 
			'<b style="color: var(--pendiente)">Fecha límite:</b>' +
			'<h6 class="mb-0"> ' + fecha_limite + '</h6>';
			$version.parentElement.insertAdjacentElement ('afterend', info_fecha_lim);
		}
		
		// Asignar propiedades a los elementos
		$titulo.textContent = 		nombre_curso;
		$descripcion.textContent =  descripcion;
		$version.textContent =		version;

		// Agregar portada del curso (en caso de que exista)
		if (portada) {
			const img_default = $elemento.querySelector ('a svg');
			const img_portada = document.createElement ('img');

			img_portada.src = 'uploads/portadas/' + portada;
			img_default.replaceWith (img_portada);
		}

		$portada.setAttribute (
			'href', 
			'visualizador.php?' + 
			'curso_int=' + id_curso + 
			'&visualizar=true&version=' + version
		);
		
		$ver_curso.setAttribute (
			'onclick', 
			// Redirigir al visualizados en la versión especificada del curso 
			'window.location.replace ("visualizador.php?' + 
			'curso_int=' + id_curso + 
			'&visualizar=true&version=' + version + '")'
		);
		
		if (asignacion && asignacion == empleado) {
			// Curso asignado por el mismo empleado (se puede remover)
			const $btn_remover = document.createElement ('button');
			$btn_remover.classList.add ('ms-auto', 'me-3', 'px-3', 'btn-remover');
			$btn_remover.innerHTML = 
			'<span>Remover</span>' +
			'<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20px"><path fill=var(--entidad-secundario) d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"/></svg>';
			
			$btn_remover.setAttribute ('onclick', 
			'MostrarMensaje (' +
				'"Remover curso pendiente", ' +
				'"<p>Se removerá el curso: <b>' + nombre_curso + '</b> de tu lista de Cursos Pendientes.</p><p class=\'mb-0\'><b>¿Deseas continuar?</b></p>",' + 
				'() => RemoverAsignacion (' + id_curso + ', ' + version + ', ' + empleado + ', true), ' + // accion
				'icono_pregunta, true, true' +
			')');

			$ver_curso.classList.remove ('ms-auto');
			$ver_curso.insertAdjacentElement ('beforebegin', $btn_remover);
		}

		$contenedor.setAttribute ('data-num_reg', '3');
		$contenedor.classList.add ('cursos-pendientes');
		$contenedor.appendChild ($elemento);
	});
}

function AgregarElementosLista ($lista, $plantillas) {
	const $contenedor_lista = 			  $lista.querySelector ('.contenedor-lista > ul');
	const $elementos_lista_CC = 		  $lista.querySelectorAll ('div[data-componente="elemento-lista-CC"]');
	let   $elementos_cursos_disponibles = $lista.querySelectorAll ('div[data-componente="curso-disponible"]');
	const $elementos_lista_CP = 		  $lista.querySelectorAll ('div[data-componente="elemento-lista-CP"]');
	
	// Plantillas de elementos de lista
	const $temp_elemento_lista_CC = $plantillas.querySelector ('#elemento-lista-CC');	// Elementos de Cursos creados

	$elementos_lista_CC.forEach (($elemento) => {
		AgregarElemento ($elemento, $temp_elemento_lista_CC);
		
		// Obtener propiedades del componente
		const nombre_curso = 	$elemento.dataset.nombre;
		const descripcion = 	$elemento.dataset.descripcion;
		const fecha = 			$elemento.dataset.fecha;
		const id_curso = 		$elemento.dataset.id_curso;
		const portada =			$elemento.dataset.portada;

		// Obtener elementos del componente
		const $descripcion = 	$elemento.querySelector 	('[data-tipo="descripcion"]');
		const $titulo = 		$elemento.querySelector 	('[data-tipo="nombre-curso"]');
		const $fecha = 			$elemento.querySelector 	('[data-tipo="fecha"]');
		const $eliminar_curso =	$elemento.querySelector		('.eliminar-curso');
		const $editar_curso =	$elemento.querySelector 	('.editar-curso');
		const $ver_curso = 		$elemento.querySelectorAll 	('.ver-curso');
		
		// Asignar propiedades a los elementos
		$titulo.textContent = 		 	 nombre_curso;
		$descripcion.textContent = 	 	 descripcion;
		$fecha.textContent = 		 	 fecha;
		$editar_curso.href =		 	 'editor.php?curso_int=' + id_curso + '&editar=true';
		$eliminar_curso.dataset.id = 	 id_curso;
		$eliminar_curso.dataset.nombre = nombre_curso;

		// Agregar imagen de portada (en caso de que exista)
		if (portada) {
			const img_default = $elemento.querySelector ('svg');
			const img_portada = document.createElement ('img');

			img_portada.src = 'uploads/portadas/' + portada;
			img_default.replaceWith (img_portada);
		}
		
		$ver_curso.forEach (($elemento) => {
			$elemento.href = 'visualizador.php?curso_int=' + id_curso + "&visualizar=true";
		});
		
		// Agregar componente a la lista
		$contenedor_lista.appendChild ($elemento);
	});
	
	AgregarCursosDisponibles ($elementos_cursos_disponibles, $contenedor_lista);
	AgregarCursosPendientes ($elementos_lista_CP, $contenedor_lista);
}

function AgregarNotaTabla ($nota) {
	if (!$nota.parentElement) return;

	// Buscar elemento header en la página
	const $header_ventana = $nota.parentElement.querySelector('div[data-tipo="header"]');
	
	// Agregar barra de búsqueda en el header de la ventana padre
	if($header_ventana) $header_ventana.appendChild ($nota);
}

function AgregarBarraBusqueda ($elemento, $temp) {
	const $barra_busqueda =   $temp.content.cloneNode (true).querySelector('div');
	const pagina = 			  $elemento.dataset.pagina;
	const empleado = 		  $elemento.dataset.empleado;
	const criterio_busqueda = $elemento.dataset.criterio;

	const $in_busqueda = $barra_busqueda.querySelector ('input');
	const $btn_buscar = $barra_busqueda.querySelector ('button');

	if (criterio_busqueda) {
		const $filtrado = document.createElement ('div');
		$filtrado.classList.add ('d-flex', 'mt-3');
		$filtrado.innerHTML = 
		'<span class="my-auto me-2">Filtrado por: </span>' +
		'<div class="tag">' +
		'<span>' + criterio_busqueda + '</span>' +
		'<button class="my-auto" onclick="window.location.replace (\''+ pagina + ((empleado) ? '?nomina=' + empleado : '') + '\')">' + 
		'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="10"><path fill="#888" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>' +
		'</button>' +
		'</div>';
		$barra_busqueda.appendChild ($filtrado);
	}

	$in_busqueda.addEventListener ('input', () => {
		$btn_buscar.disabled = $in_busqueda.value == '';
	});

	$btn_buscar.onclick = () => {
		window.location.replace (
			pagina + ((empleado) ? ('?nomina=' + empleado + '&buscar=') : '?buscar=') + $in_busqueda.value 
		);
	};
	
	// Buscar elemento header en la página
	const $header_ventana = $elemento.parentElement.querySelector('div[data-tipo="header"]');
	
	// Agregar barra de búsqueda en el header de la ventana padre
	if($header_ventana) $header_ventana.appendChild ($barra_busqueda);
}

function AgregarFooter ($elemento, $temp, paginacion = false) {
	const $footer = paginacion ? 
					$temp.content.cloneNode (true).querySelector('a') : 
					$temp.content.cloneNode (true);
		
	let   enlace = 		$footer.querySelector('a');
	const accion = 		$elemento.dataset.accion;
	const mostrar_mas = ($elemento.dataset.mostrar_mas && $elemento.dataset.mostrar_mas == 'true') ? 
						true : false;

	const $comp_paginas = 		$elemento.querySelectorAll ('button.pagina');
	const $contenedor_paginas = $footer.querySelector ('[data-tipo="sel-paginas"]');
	const $btn_siguiente =		$footer.querySelector ('[data-tipo="sel-sig"]');
	const $btn_anterior =		$footer.querySelector ('[data-tipo="sel-ant"]');
	const $pag_actual =			$footer.querySelector ('[data-info="pag-actual"]');
	const busqueda =			$elemento.dataset.busqueda;
	const pagina = 				$elemento.dataset.pagina;
	const empleado = 			$elemento.dataset.empleado;

	// Indicar página actual
	if ($pag_actual) $pag_actual.textContent = 
	'Página ' + $elemento.dataset.pag_actual + 
	' de ' + $elemento.dataset.max_pags;

	// Determinar parámetros adicionales del redireccionamiento
	let parametros = (empleado) ? '&nomina=' + empleado : '';

	// Se incluye un criterio de búsqueda
	if (busqueda) parametros += '&buscar=' + busqueda;
	
	if ($btn_siguiente) $btn_siguiente.onclick = () => {
		// Botón de página siguiente
		if (parseInt ($elemento.dataset.pag_actual) >= parseInt ($elemento.dataset.max_pags)) {
			return;
		} 
		window.location.replace (
			pagina + '?pag=' + 
			(parseInt ($elemento.dataset.pag_actual ) + 1) + parametros
		);
	};
	if ($btn_anterior) $btn_anterior.onclick = () => {
		// Botón de página anterior
		if ($elemento.dataset.pag_actual <= 1) {
			return;
		}
		window.location.replace (
			pagina + '?pag=' + 
			(parseInt ($elemento.dataset.pag_actual ) - 1) + parametros
		);
	};

	$comp_paginas.forEach (($comp_pagina) => {
		// Botones de páginas
		$comp_pagina.onclick = () => {
			if ($comp_pagina.textContent == '...' || $comp_pagina.classList.contains ('actual')) return;
			window.location.replace (pagina + '?pag=' + $comp_pagina.dataset.pag + parametros);
		};
		$contenedor_paginas.appendChild ($comp_pagina);
	});

	if (enlace && !mostrar_mas) { 
		// No hay más cursos por mostrar
		enlace.innerHTML = 			 '<hr class="border-0 my-4">'; 
		enlace.style.pointerEvents = 'none' 
	}
	
	// Agregar enlace a la sección completa (ver más) 
	if(accion) enlace.href = accion;
	
	// Buscar elemento de footer en la página
	const $footer_ventana = $elemento.parentElement.querySelector ('div[data-tipo="footer"]');
	
	// Agregar footer en la parte inferior de la ventana padre
	if ($footer_ventana) $footer_ventana.appendChild ($footer);
	return $footer_ventana;
}

async function AgregarVentana ($elemento, titulo, $temp, bloque = false, xl = null) {

	if (!$elemento) return;
	
	const $ventana = $temp.content.cloneNode (true).querySelector ('div');
	const $titulo = bloque ? 
					$ventana.querySelector 	('h4[data-tipo="titulo"]') : 
					$ventana.querySelector 	('h2[data-tipo="titulo"]');
	const $body = $ventana.querySelector 	('div[data-tipo="body"]');

	if (xl) $ventana.querySelector ('.ventana-extendida').classList.add ('xl');
	
	if ($elemento.parentElement.dataset.subtitulo) {
		const $subtitulo = 		document.createElement ('span');
		$subtitulo.innerHTML =  '<b>Empleado: </b>' + $elemento.parentElement.dataset.subtitulo;
		$subtitulo.classList.add ('subtitulo', 'px-5');
		$titulo.insertAdjacentElement ('afterend', $subtitulo);
	}
	
	// Plantillas de elementos que pueden ir dentro de una ventana
	$temp_pregunta = 			 			$plantilla.querySelector ('#pregunta');
	$temp_opcion_evaluacion =				$plantilla.querySelector ('#opcion-evaluacion');
	$temp_ventana_eliminable = 				$plantilla.querySelector ('#ventana-eliminable');
	$temp_opcion = 			 				$plantilla.querySelector ('#opcion');
	
	const $temp_info_usuario = 		 		$plantilla.querySelector ('#bloque-info-usuario');
	const $temp_info_estadisticas =  		$plantilla.querySelector ('#bloque-info-estadisticas');
	const $temp_info_curso = 		 		$plantilla.querySelector ('#info-version-curso');
	const $temp_banco_preguntas = 	 		$plantilla.querySelector ('#banco-preguntas');
	const $temp_buscar_empleado_nomina =	$plantilla.querySelector ('#buscar-empleado-nomina');
	const $temp_info_empleado =				$plantilla.querySelector ('#info-empleado');
	const $temp_tabla_admin_cursos_i =		$plantilla.querySelector ('#tabla-admin-cursos-i');
	const $temp_tabla_admin_cursos_e =		$plantilla.querySelector ('#tabla-admin-cursos-e');
	const $temp_tabla_externos =			$plantilla.querySelector ('#tabla-externos');
	const $temp_tabla_empleados_simple =	$plantilla.querySelector ('#tabla-empleados-simple');
	const $temp_tabla_cursos_aprobados =	$plantilla.querySelector ('#tabla-cursos-aprobados');
	const $temp_tabla_CP_admin =			$plantilla.querySelector ('#tabla-CP-admin');
	const $temp_tabla_admin_CE =			$plantilla.querySelector ('#tabla-admin-CE');
	const $temp_tabla_admin_Cert =			$plantilla.querySelector ('#tabla-admin-Cert');
	const $temp_tabla_CC_admin =			$plantilla.querySelector ('#tabla-CC-admin');
	const $temp_tabla_usuarios_CP = 		$plantilla.querySelector ('#tabla-usuarios-CP');
	const $temp_tabla_usuarios_cert = 		$plantilla.querySelector ('#tabla-usuarios-cert');
	const $temp_tabla_usuarios_apr = 		$plantilla.querySelector ('#tabla-usuarios-apr');
	const $temp_tabla_borradores = 			$plantilla.querySelector ('#tabla-borradores');
	
	// Obtener componentes por agregar a la ventana
	const $banco_preguntas = 		$elemento.querySelector 	('div[data-componente="banco-preguntas"]');
	const $info_version_curso = 	$elemento.querySelector 	('div[data-componente="info-version-curso"]');
	const $buscar_empleado_nomina =	$elemento.querySelector		('div[data-componente="buscar-empleado-nomina"]');
	const $info_empleado =			$elemento.querySelector		('div[data-componente="info-empleado"]');
	const $tabla_admin_cursos_e = 	$elemento.querySelector 	('div[data-componente="tabla-admin-cursos-e"]');
	const $tabla_externos =			$elemento.querySelector		('div[data-componente="tabla-externos"]');
	const $tabla_cursos_aprobados = $elemento.querySelector 	('div[data-componente="tabla-cursos-aprobados"]');
	const $tabla_CP_admin = 		$elemento.querySelector 	('div[data-componente="tabla-CP-admin"]');
	const $tabla_admin_CE =			$elemento.querySelector 	('div[data-componente="tabla-admin-CE"]');
	const $tabla_admin_Cert =		$elemento.querySelector 	('div[data-componente="tabla-admin-Cert"]');
	const $tabla_CC_admin =			$elemento.querySelector		('div[data-componente="tabla-CC-admin"]');
	const $tabla_usuarios_CP =		$elemento.querySelector		('div[data-componente="tabla-usuarios-CP"]');
	const $tabla_usuarios_cert = 	$elemento.querySelector		('div[data-componente="tabla-usuarios-cert"]');
	const $tabla_usuarios_apr = 	$elemento.querySelector		('div[data-componente="tabla-usuarios-apr"]');
	const $tabla_borradores = 		$elemento.querySelector		('div[data-componente="tabla-borradores"]');
	const $tabla_empleados_simple = $elemento.querySelectorAll 	('div[data-componente="tabla-empleados-simple"]');
	const $tabla_admin_cursos_i = 	$elemento.querySelectorAll 	('div[data-componente="tabla-admin-cursos-i"]');

	// Bloque de información de curso (Editor de cursos)
	if ($info_version_curso) AgregarElemento ($info_version_curso, $temp_info_curso);
	
	if ($banco_preguntas) {
		const propiedades = {
			max_preg: 	$banco_preguntas.dataset.max_preg, 
			calif_min: 	$banco_preguntas.dataset.calif_min
		};
		AgregarElemento ($banco_preguntas, $temp_banco_preguntas, 'banco_preg', propiedades);
			
		const $preguntas = $banco_preguntas.querySelectorAll ('div[data-componente="pregunta"]');
		
		// Agregar las preguntas del banco de preguntas
		for (let i = $preguntas.length; i > 0; i--) {
			AgregarNuevaPregunta ($preguntas [i - 1]);
		}
	}
	
	// Elemento para buscar empleado por número de nómina
	if ($buscar_empleado_nomina) {
		const propiedades = {
			accion: $buscar_empleado_nomina.dataset.action,
			msg: 	$buscar_empleado_nomina.dataset.msg
		};
		AgregarElemento (
			$buscar_empleado_nomina, 
			$temp_buscar_empleado_nomina, 
			'buscar_empleado_nomina',
			propiedades
		);
	}
	
	// Elemento para visualizar información básica de un empleado
	if ($info_empleado) {
		const propiedades = {
			no_nomina: 		$info_empleado.dataset.num_nomina,
			nombre: 		$info_empleado.dataset.nombre,
			departamento: 	$info_empleado.dataset.departamento, 
			email: 			$info_empleado.dataset.email,
			tipo:			$info_empleado.dataset.tipo
		};
		AgregarElemento (
			$info_empleado, 
			$temp_info_empleado, 
			'info-empleado', 
			propiedades
		);
	}

	const tablas = [
		// Definir las plantillas de las diferentes tablas
		{tabla: $tabla_admin_cursos_e, 	 temp: $temp_tabla_admin_cursos_e},
		{tabla: $tabla_externos,		 temp: $temp_tabla_externos},
		{tabla: $tabla_cursos_aprobados, temp: $temp_tabla_cursos_aprobados},
		{tabla: $tabla_CP_admin, 		 temp: $temp_tabla_CP_admin},
		{tabla: $tabla_admin_CE, 		 temp: $temp_tabla_admin_CE},
		{tabla: $tabla_admin_Cert, 		 temp: $temp_tabla_admin_Cert},
		{tabla: $tabla_CC_admin, 		 temp: $temp_tabla_CC_admin},
		{tabla: $tabla_usuarios_CP, 	 temp: $temp_tabla_usuarios_CP},
		{tabla: $tabla_usuarios_cert, 	 temp: $temp_tabla_usuarios_cert},
		{tabla: $tabla_usuarios_apr, 	 temp: $temp_tabla_usuarios_apr},
		{tabla: $tabla_borradores, 		 temp: $temp_tabla_borradores}
	];
	
	if ($tabla_admin_cursos_i) {
		$tabla_admin_cursos_i.forEach (($tabla) => {
			AgregarElemento ($tabla, $temp_tabla_admin_cursos_i, 'tabla-admin-cursos-i');
		});
	}
	if ($tabla_empleados_simple) {
		$tabla_empleados_simple.forEach (($tabla) => {
			AgregarElemento ($tabla, $temp_tabla_empleados_simple);
		});
	}
	
	tablas.forEach (({tabla, temp}) => {
		// Agregar los diferentes tipos de tablas (en caso de que existan en la ventana)
		if(tabla) AgregarElemento (tabla, temp);
	});

	// Configurar contenido de la ventana
	const $elementos_contenido = $elemento.innerHTML;
	$titulo.textContent = 		 titulo;
	$body.innerHTML = 			 $elementos_contenido;
	
	// Elementos que van fuera del body de la ventana
	const $bloque_info_usuario = 		$body.querySelector ('div[data-componente="bloque-info-usuario"]');
	const $bloque_info_estadisticas = 	$body.querySelector ('div[data-componente="bloque-info-estadisticas"]');
	
	// Bloque de información de usuario
	if ($bloque_info_usuario) {
		AgregarElemento ($bloque_info_usuario, $temp_info_usuario, 'bloque-info-estadisticas');
		
		const $img = 		$bloque_info_usuario.querySelector ('img');
		const no_nomina = 	$img.dataset.no_nomina;
		
		// Recuperar foto de perfil y mostrarla al usuario
		await ObtenerFotoPerfil ($img, no_nomina);
	}
	
	// Bloque de información de estadísticas
	if ($bloque_info_estadisticas) {
		const propiedades = {
			aprobados: 	$bloque_info_estadisticas.dataset.aprobados,
			externos: 	$bloque_info_estadisticas.dataset.externos,
			pendientes: $bloque_info_estadisticas.dataset.pendientes
		};
		AgregarElemento (
			$bloque_info_estadisticas, 
			$temp_info_estadisticas, 
			'bloque-info-estadisticas',
			propiedades
		);
	}
	
	// Agregar ventana al contenido de la página
	$elemento.innerHTML = $ventana.innerHTML;
}

async function CalcularRutaFotoPerfil (no_nomina) {
	let src = 		 null;
	const formData = new FormData ();

	formData.append ('accion', 'obtener_foto_perfil');
	formData.append ('no_nomina', no_nomina);
	
	await fetch ('modulos/util/gestion_archivos.php', {
		method: 'POST',
		body: formData
	})
	.then ((resp) => resp.text())
	.then ((ruta) => {
		// Recuperar ruta del archivo
		src = 'uploads/fotos/' + ruta;
	});

	return src;
}

async function ObtenerFotoPerfil ($img, no_nomina) {
	const ruta = await CalcularRutaFotoPerfil (no_nomina);
	$img.src = 		ruta;
	$img.hidden = 	ruta === null ? true : false;
}

function AgregarOpcionesRespuesta ($opciones, $contenedor_opciones) {
	$opciones.forEach (($opcion) => {
		// Transferir información de las opciones al nuevo componente
		const $elemento_opcion = $temp_opcion_evaluacion.content.cloneNode (true);
		const $etiqueta = 	 	 $elemento_opcion.querySelector ('label');
		const $opcion_body = 	 $elemento_opcion.querySelector('input');
		const opcion = 			 $opcion.innerHTML;
		
		$opcion_body.setAttribute ("name", $opcion.id);
		
		$etiqueta.innerHTML = opcion;
		$contenedor_opciones.appendChild ($elemento_opcion);
	});
}

function ConfigurarRegistroCursoCreado ($elemento, propiedades) {
	const $nombre = 	$elemento.querySelector ('[data-info="nombre"]');
	const $usaurio =	$elemento.querySelector ('[data-info="usuario"]');
	const $version = 	$elemento.querySelector ('[data-info="version"]');
	const $fecha = 		$elemento.querySelector ('[data-info="fecha"]');
	const $btn_ver = 	$elemento.querySelector ('[data-tipo="btn-ver"]');

	// Mostrar información del registro de cursos creados
	$nombre.textContent = 	propiedades ['nombre'];
	$usaurio.textContent =	propiedades ['usuario'];
	$version.textContent = 	propiedades ['version'];
	$fecha.textContent = 	propiedades ['fecha'];

	$btn_ver.setAttribute ('onclick', 'window.open ("visualizador.php?curso_int=' + propiedades ['id_curso'] + '&visualizar=true", "_blank")');
}

function ConfigurarRegistroCertificacion ($elemento, propiedades) {

	const $nombre_emp =   $elemento.querySelector ('[data-info="empleado"]');
	const $nombre_curso = $elemento.querySelector ('[data-info="curso"]');
	const $fecha = 		  $elemento.querySelector ('[data-info="fecha"]');
	const $vigencia = 	  $elemento.querySelector ('[data-info="vigencia"]');
	const $btn_eliminar = $elemento.querySelector ('button.btn-quitar');
	const $btn_ver =	  $elemento.querySelector ('[data-info="btn-ver"] button');

	const fecha_vigencia = 	new Date (propiedades ['validez']);
	const fecha_actual = 	new Date ();

	// Mostrar información del registro de la certificación
	$nombre_emp.textContent = 	 propiedades ['empleado'];
	$nombre_curso.textContent =  propiedades ['curso'];
	$fecha.textContent = 		 propiedades ['fecha'];
	$vigencia.textContent = 	 propiedades ['validez'];

	// Evento para realizar la eliminación lógica de la BD
	const eliminarCertificacion = async (id_certificacion) => {

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

	// Configurar botón para visualizar certificación
	$btn_ver.onclick = (e) => {
		window.open (
			'ver_certificacion.php?id_certificacion=' + 
			propiedades ['id_certificacion'],
			'_blank'
		);
	};

	// Configurar botón de eliminación
	$btn_eliminar.onclick = (e) => {
		MostrarMensaje (
			'Eliminar certificación', 
			'Se eliminará la certificación "<b>' + propiedades ['curso'] +
			'</b>", del registro del empleado <b>' + propiedades ['empleado'] + 
			'</b>,<hr class="my-2 border-0"><b>¿Deseas continuar?</b>', 
			() => eliminarCertificacion (propiedades ['id_certificacion']), 
			icono_pregunta, true,	true
		)
	};

	if (!propiedades ['validez']) {
		// Configurar columna para cuando el curso no tiene vigencia
		$vigencia.textContent = 'N/A';
		$vigencia.setAttribute ('title', 'La certificación no tiene vigencia');
	}
	if (fecha_vigencia < fecha_actual) {
		// La certificación se encuentra vencida
		$vigencia.style.color = '#F00';
		$vigencia.setAttribute ('title', 'La certificación está vencida');
	}
}

function ConfigurarRegistroAprobado ($elemento, propiedades) {
	const $nombre_emp = 	$elemento.querySelector ('[data-info="empleado"]');
	const $nombre_curso = 	$elemento.querySelector ('[data-info="curso"]');
	const $version = 		$elemento.querySelector ('[data-info="version"]');
	const $aprobado = 		$elemento.querySelector ('[data-info="aprobado"]');
	const $asignado = 		$elemento.querySelector ('[data-info="asignado"]');
	const $intentos = 		$elemento.querySelector ('[data-info="intentos"]');
	const $calificacion = 	$elemento.querySelector ('[data-info="calificacion"]');
	const $btn_ver = 		$elemento.querySelector ('[data-info="ver"]');

	// Calcular calificación obtenida
	const calificacion = (propiedades ['puntaje'] / propiedades ['puntaje_max']) * 100;

	// Determinar si la calificación fué colaborativa
	const colaborativo = propiedades ['colaborativo'] == '1' ? ' <b>C</b>' : '';

	// Mostrar información del registro de curso aprobado
	$nombre_emp.textContent = 	propiedades ['empleado'];
	$nombre_curso.textContent = propiedades ['curso'];
	$version.textContent = 		propiedades ['version'];
	$aprobado.textContent = 	propiedades ['aprobado'];
	$asignado.textContent = 	propiedades ['asignado'];
	$intentos.textContent = 	propiedades ['intentos'];
	$calificacion.innerHTML = 	parseFloat (calificacion).toFixed (2) + colaborativo;

	$calificacion.style.cursor = 'default';
	$calificacion.setAttribute (
		'title', 
		propiedades ['colaborativo'] == '1' ? 
		'Calificación colaborativa' : 'Calificación individual'
	);

	$btn_ver.setAttribute (
		// Ir al curso al presionar el botón del curso
		'onclick', 
		'window.open (' + 
			'"visualizador.php?curso_int=' + propiedades ['id_curso'] + 
			'&version=' + propiedades ['version'] + 
			'&visualizar=true"' + 
		', "_blank")'
	);
}

function ConfigurarElementoUsuarioCP ($elemento, propiedades) {
	const $nombre =  		$elemento.querySelector ('[data-info="nombre"]');
	const $curso =  		$elemento.querySelector ('[data-info="curso"] p');
	const $version =  		$elemento.querySelector ('[data-info="version"]');
	const $fecha =  		$elemento.querySelector ('[data-info="fecha"]');
	const $btn_ir = 		$elemento.querySelector ('button.btn-ir');
	const $btn_quitar = 	$elemento.querySelector ('button.btn-quitar');
	const $fecha_limite = 	$elemento.querySelector ('[data-info="fecha_limite"]');

	// Identificar los cursos pendientes vencidos
	if (propiedades ['fecha_limite'] != '') {
		// Agregar indicador de fecha límite (en caso de que haya una fecha límite)
		const limite = new Date (propiedades ['fecha_limite'] + 'T00:00:00.000-06:00');
		const fecha_actual = new Date ();

		if (fecha_actual > limite) 
			$elemento.querySelector ('tr').classList.add ('curso-vencido');
	}

	// Definir evento al presionar el botón "ver"
	$btn_ir.setAttribute (
		'onclick', 
		'window.open ("visualizador.php?curso_int=' + 
		propiedades ['id_curso'] + '&version=' +
		propiedades ['version'] + '&visualizar=true", "_blank")'
	);

	// Definir evento al presionar el botón "des-asignar"
	$btn_quitar.setAttribute (
		'onclick', 
		'MostrarMensaje (' + 
			'"Remover curso asignado a empleado", ' + 
			'"Se desasignará el curso pendiente:  ' + 
			'<b>\'' + propiedades ['curso'] + '\'</b>, al empleado:<br>' + 
			propiedades ['nombre'] +'. ' + 
			'<br><br><b>¿Deseas continuar?</b>", ' + 
			'() => RemoverAsignacion (' + 
				propiedades ['id_curso'] + ', ' + 
				propiedades ['version'] +', ' + 
				propiedades ['nomina'] + ', ' +
				'true), ' + 
				'icono_pregunta, true, true, true' + 
		')'
	);

	// Definir tooltip para visualizar el nombre completo al pasar el cursor
	$nombre.setAttribute ('title', propiedades ['nombre']);

	// Mostrar información del curso pendiente en la fila
	$nombre.textContent =    	propiedades ['nombre'];
	$curso.textContent =   	 	propiedades ['curso'];
	$version.textContent =   	propiedades ['version'];
	$fecha.textContent =   	 	propiedades ['fecha'];
	$fecha_limite.textContent = propiedades ['fecha_limite'] == '' ? 
								'N/A' : propiedades ['fecha_limite'];
}

function ConfigurarElementoCE ($elemento, propiedades) {
	const $nombre =  		$elemento.querySelector ('[data-info="nombre-curso"]');
	const $descripcion =  	$elemento.querySelector ('[data-info="descripcion-curso"]');
	const $fecha = 	 		$elemento.querySelector ('[data-info="fecha-curso"]');
	const $validez = 		$elemento.querySelector ('[data-info="validez-curso"]');
	const $btn_eliminar = 	$elemento.querySelector ('button');
	
	// Mostrar información de la certificación en la fila
	$nombre.textContent =   	 propiedades ['nombre'];
	$descripcion.textContent =   propiedades ['descripcion'];
	$fecha.textContent = 		 propiedades ['fecha'];
	$validez.textContent =  	 propiedades ['validez'];
	
	// Configurar botón para visualizar certificación 
	$btn_eliminar.setAttribute (
		'onclick', 
		'EliminarCursoExterno (' + propiedades ['id_curso'] + ', "' 
		+ propiedades ['nombre'] + '")'
	);
}

function ConfigurarElementoCert ($elemento, propiedades) {
	const $nombre =  		$elemento.querySelector ('[data-info="nombre-curso"]');
	const $fecha = 	 		$elemento.querySelector ('[data-info="fecha-curso"]');
	const $validez = 		$elemento.querySelector ('[data-info="validez-curso"]');
	const $btn_ver = 		$elemento.querySelector ('button');
	
	// Mostrar información de la certificación en la fila
	$nombre.textContent =   propiedades ['nombre'];
	$fecha.textContent = 	propiedades ['fecha'];
	$validez.textContent =  propiedades ['validez'] ? 
							propiedades ['validez'] : 'N/A';

	// Comprobar si certificación se encuentra expirada
	if (new Date (propiedades ['validez']) < new Date ()) {
		$validez.style.color = '#F00';
	}
	
	// Configurar botón para visualizar certificación
	$btn_ver.setAttribute (
		'onclick', 
		'window.location.replace ("ver_certificacion.php?id_certificacion=' + 
		propiedades ['id_cert'] + '")'
	);
}

function ConfigurarElementoCIA ($elemento, propiedades) {
	const $nombre =   		 $elemento.querySelector ('[data-info="nombre"]');
	const $version =  		 $elemento.querySelector ('[data-info="version"]');
	const $fecha = 	  		 $elemento.querySelector ('[data-info="fecha"]');
	const $intentos = 		 $elemento.querySelector ('[data-info="intentos"]');
	const $puntaje =  		 $elemento.querySelector ('[data-info="puntaje"]');
	const $btn_ir =  		 $elemento.querySelector ('td[data-info="ir"] button');
	const $btn_certificado = $elemento.querySelector ('td[data-info="certificado"] button');

	// Calcular calificación obtenida
	const calificacion = (propiedades ['puntaje'] / propiedades ['puntaje_max']) * 100;

	// Determinar si la calificación fué colaborativa
	const colaborativo = propiedades ['colaborativo'] == '1' ? ' <b>C</b>' : '';
	
	// Mostrar información del curso en la fila
	$nombre.textContent = 	propiedades['nombre'];
	$version.textContent = 	propiedades['version'];
	$fecha.textContent = 	propiedades['fecha'];
	$intentos.textContent = propiedades['intentos'];
	$puntaje.innerHTML = 	parseFloat (calificacion).toFixed (2) + colaborativo;

	$puntaje.style.cursor = 'default';
	$puntaje.setAttribute (
		'title', 
		propiedades ['colaborativo'] == '1' ? 
		'Calificación colaborativa' : 'Calificación individual'
	);
	
	$btn_ir.setAttribute (
		// Ir al curso al presionar el botón del curso
		'onclick', 
		'window.open (' + 
			'"visualizador.php?curso_int=' + propiedades ['id_curso'] + 
			'&version=' + propiedades ['version'] + 
			'&visualizar=true"' + 
		', "_blank")'
	);
	
	$btn_certificado.setAttribute (
		// Ver certificado de curso aprobado
		'onclick',
		'GenerarCertificado (' + 
			'"' + propiedades ['id_curso'] + '", ' +
			'"' + propiedades ['no_nomina'] + '"' +
		')'
	);
}

function ConfigurarElementoBorrador ($elemento, propiedades) {
	
	// Configurar elemento de lista de borradores
	const $titulo = 		$elemento.querySelector ('td');
	const $fecha =			$elemento.querySelector ('[data-info="fecha-mod"]');
	const $btn_editar = 	$elemento.querySelector ('.editar');
	const $btn_descartar = 	$elemento.querySelector ('.descartar');

	$titulo.textContent = 	propiedades ['nombre'];
	$fecha.textContent =	propiedades ['fecha_mod'];
	
	$btn_editar.onclick = 	function (e) {
		// Opción para continuar editando borrador
		window.location.replace (propiedades ['editar']);
	}
	$btn_descartar.onclick = function (e) {
		// Opción para descartar borrador
		MostrarMensaje (
			'Descartar borrador',
			'Se descartará el borrador del curso "' + 
			propiedades ['nombre'] + 
			'", ¿Deseas continuar?',
			function () {
				DescartarEdicion (
					propiedades ['id_curso'], 
					propiedades ['id_int'], 
					false
				);
			}, icono_delete, true
		);
	}
}

function ConfigurarBusquedaEmpleado ($elemento, propiedades) {

	if (propiedades ['msg']) {
		// Mostrar mensaje personalizado
		const $mensaje =		$elemento.querySelector ('h5');
		$mensaje.textContent = 	propiedades ['msg'];
	}
	
	// Configurar elemento de búsqueda de empleado
	const $formulario =  $elemento.querySelector('form');
	$formulario.action = propiedades ['accion'];
	$formulario.method = 'post';
} 

function ConfigurarElementoInfoEmp ($elemento, propiedades) {
	
	// Configurar elemento de información de empleado
	const $num_nomina =   $elemento.querySelector ('[data-info="no_nomina"]');
	const $nombre = 	  $elemento.querySelector ('[data-info="nombre"]');
	const $departamento = $elemento.querySelector ('[data-info="departamento"]');
	const $email = 		  $elemento.querySelector ('[data-info="email"]');
	const $img =		  $elemento.querySelector ('[data-info="img"]');
	const $tipo_usuario = $elemento.querySelector ('[data-info="tipo"]');
	
	$num_nomina.textContent = 	propiedades ['no_nomina'];
	$nombre.textContent = 		propiedades ['nombre'];
	$departamento.textContent = propiedades ['departamento'];
	$email.textContent = 		propiedades ['email'];

	// Agregar información de número de nómina, para la recuperación de la foto del empleado
	$img.dataset.no_nomina = propiedades ['no_nomina'];

	if (propiedades ['tipo']) {
		// Mostrar tipo de usuario, en caso de que sea un usuario existente
		$tipo_usuario.textContent = propiedades ['tipo'];
		$tipo_usuario.closest('div').hidden = false;
	} 
	else {
		// Ocultar tipo de usuario, en caso de que no sea un usuario existente
		$tipo_usuario.closest('div').hidden = true;
	}
}

function ConfigurarBancoPreg ($elemento, propiedades) {
	
	// Recuperar configuración del banco de preguntas
	const $input_max_preg =  $elemento.querySelector ('#max-preg');
	const $input_calif_min = $elemento.querySelector ('#calif-min');
	
	$input_max_preg.setAttribute ("value", propiedades ['max_preg']);
	$input_calif_min.setAttribute ("value", propiedades ['calif_min']);
}

function ConfigurarBloqueInfo ($elemento, propiedades) {
	if (!propiedades) return;

	const $aprobados = 	$elemento.querySelector ('[data-info="aprobados"]');
	const $externos = 	$elemento.querySelector ('[data-info="certificaciones"]');
	const $pendientes = $elemento.querySelector ('[data-info="pendientes"]');

	$aprobados.textContent =  propiedades ['aprobados'];
	$externos.textContent =   propiedades ['externos'];
	$pendientes.textContent = propiedades ['pendientes'];
}

function ConfigurarCPAdmin ($elemento, propiedades) {
	if (!propiedades) return;

	const $curso = 			$elemento.querySelector ('[data-info="nombre-curso"]');
	const $version = 		$elemento.querySelector ('[data-info="version-curso"]');
	const $fecha = 			$elemento.querySelector ('[data-info="asignado"]');
	const $btn_desasignar = $elemento.querySelector ('[data-info="btn-quitar"]');
	const $btn_ver = 		$elemento.querySelector ('button.btn-ver');

	$curso.textContent = 	propiedades ['curso'];
	$version.textContent = 	propiedades ['version'];
	$fecha.textContent = 	propiedades ['fecha'];

	$btn_desasignar.onclick = () => {
		QuitarCurso (
			propiedades ['id_curso'], 
			propiedades ['curso'],
			propiedades ['version'],
			propiedades ['usuario'],
			true
		);
	};
	$btn_ver.onclick = () => {
		VerCurso (
			propiedades ['id_curso'], 
			propiedades ['version']
		);
	};
}

function ConfigurarAdmiCE ($elemento, propiedades) {
	if (!propiedades) return;

	const $curso = 		  $elemento.querySelector ('[data-info="nombre"]');
	const $fecha = 		  $elemento.querySelector ('[data-info="fecha"]');
	const $validez = 	  $elemento.querySelector ('[data-info="validez"]');
	const $btn_eliminar = $elemento.querySelector ('[data-info="btn-eliminar"]');
	const $btn_ver = 	  $elemento.querySelector ('[data-info="btn-ver"]');

	$curso.textContent =   propiedades ['curso'];
	$fecha.textContent =   propiedades ['fecha'];
	$validez.textContent = propiedades ['vigencia'] ?
						   propiedades ['vigencia'] : 'N/A';

	// Comprobar validez de la certificación
	if (propiedades ['vigencia'] && (new Date (propiedades ['vigencia']) < new Date ())) {
		$validez.style.color = '#F00';
	}

	$btn_eliminar.onclick = () => {
		// Eliminar certificación del registro del empleado
		EliminarCertificacionEmpleado (
			propiedades ['curso'],
			propiedades ['nombre_emp'],
			propiedades ['id_cert']
		);
	};
	$btn_ver.onclick = () => {
		// Ver certificación
		window.open (
			'ver_certificacion.php?id_certificacion=' +
			 propiedades ['id_cert'], 
			 '_blank'
		);
	};
}

function ConfigurarCCAdmin ($elemento, propiedades) {
	if (!propiedades) return;

	const $curso = 		   $elemento.querySelector ('[data-info="nombre-curso"]');
	const $version = 	   $elemento.querySelector ('[data-info="version-curso"]');
	const $btn_ver = 	   $elemento.querySelector ('button.btn-ver');
	const $btn_historial = $elemento.querySelector ('[data-info="btn-historial"]');

	$curso.textContent = 	propiedades ['curso'];
	$version.textContent = 	propiedades ['version'];

	$btn_ver.onclick = () => {
		window.open (
			'visualizador.php?curso_int=' + 
			propiedades ['id_curso'] + 
			'&version=' + propiedades ['version'] + 
			'&visualizar=true', '_blank'
		);
	};
	$btn_historial.onclick = () => {
		AbrirHistorial (
			propiedades ['id_curso'], null, 
			propiedades ['version'], true
		);
	};
}

function HabilitarBtnTag (componente_principal) {
	const in_tag = componente_principal.querySelector ('input');
	const btn_agregar = componente_principal.querySelector ('button');
	
	btn_agregar.disabled = in_tag.value.trimStart () == '';
}

function AgregarTag (btn, bs_collapse_tags = null) {
	const componente_principal = btn.parentElement;
	const in_tag = 			 	 componente_principal.querySelector ('input');
	const tag = 			 	 $temp_tag_eliminable.content.cloneNode (true).querySelector ('div');
	const collapse_tags = 	 	 componente_principal.parentElement.querySelector ('.collapse');
	const fieldset_tags =	 	 collapse_tags.querySelector ('fieldset');

	if (!bs_collapse_tags) {
		bs_collapse_tags = new bootstrap.Collapse (collapse_tags, { toggle: false });
		btn.onclick = () => AgregarTag (btn, bs_collapse_tags);
	}

	const btn_tag = tag.querySelector ('button');
	const txt_tag = tag.querySelector ('[data-info="texto"]');

	// Filtrar caracteres especiales
	txt_tag.textContent = in_tag.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚüÜñÑ\s]/g, '');

	// Configurar tag
	btn_tag.addEventListener ('click', () => {
		EliminarTag (btn_tag, bs_collapse_tags);
	});

	fieldset_tags.appendChild (tag);
	in_tag.value = '';
	btn.disabled = true;
	bs_collapse_tags.show ();
}

function EliminarTag (btn, bs_collapse_tags = null) {
	let collapse_tags = null;
	const NumeroDeTags = () => {
		return document.querySelectorAll ('.tag').length;
	}
	if (!bs_collapse_tags) {
		collapse_tags = 	document.querySelector ('#tags-curso.collapse');
		bs_collapse_tags = 	new bootstrap.Collapse (collapse_tags, { toggle: false });
	}
	btn.parentElement.remove ();
	if (bs_collapse_tags && NumeroDeTags () == 0) {
		bs_collapse_tags.hide ();
	}
}

function AgregarElemento ($elemento, $temp, tipo = null, propiedades = null) {

	// Agregar elementos completos, tal y como vienen definidos en la plantilla
	const $contenido = $temp.content.cloneNode (true);
	
	switch (tipo) {
		case 'borrador': 				 ConfigurarElementoBorrador ($contenido, propiedades); break;
		case 'info-empleado': 			 ConfigurarElementoInfoEmp ($contenido, propiedades); break;
		case 'buscar_empleado_nomina': 	 ConfigurarBusquedaEmpleado ($contenido, propiedades); break;
		case 'banco_preg': 				 ConfigurarBancoPreg ($contenido, propiedades); break;
		case 'curso-aprobado': 			 ConfigurarElementoCIA ($contenido, propiedades); break;
		case 'curso-e': 				 ConfigurarElementoCert ($contenido, propiedades); break;
		case 'admin-curso-e': 			 ConfigurarElementoCE ($contenido, propiedades); break;
		case 'usuario-CP': 				 ConfigurarElementoUsuarioCP ($contenido, propiedades); break;
		case 'usuario-apr': 			 ConfigurarRegistroAprobado ($contenido, propiedades);  break;
		case 'usuario-cert':			 ConfigurarRegistroCertificacion ($contenido, propiedades); break;
		case 'admin-curso-i': 			 ConfigurarRegistroCursoCreado ($contenido, propiedades); break;
		case 'bloque-info-estadisticas': ConfigurarBloqueInfo ($contenido, propiedades); break;
		case 'CP-admin':				 ConfigurarCPAdmin ($contenido, propiedades); break;
		case 'admin-CE':				 ConfigurarAdmiCE ($contenido, propiedades); break;
		case 'CC-admin':				 ConfigurarCCAdmin ($contenido, propiedades); break;
	}
	$elemento.appendChild ($contenido);

	// Obtener color, en caso de que haya sido asignado
	const color = $elemento.dataset.color;
	
	// Asignar contenido al componente (en caso de que se haya especificado)
	if($elemento.dataset.valor != null) {
		const valor = 		  $elemento.dataset.valor;
		const tipo_elemento = $elemento.dataset.componente;
		
		if (tipo_elemento === 'comp-titulo-principal') {
			AsignarValorComponente ($elemento, 'input', valor);
		}
	}

	if ($elemento.querySelector ('table') && color) {
		
		// Aplicar el color a la cabecera de las tablas
		const $cabecera = $elemento.querySelector ('table thead tr');
		$cabecera.classList.add (color);
	}
}

function AsignarValorComponente ($elemento, tipo_input, texto, textarea = false) {
	const $input = $elemento.querySelector (tipo_input);
	
	if(!textarea) { $input.value = texto; return; }
	$input.innerHTML = texto;
}

function MostrarMensaje (
	titulo, 
	mensaje, 
	accion = null, 
	icono = null, 
	opciones = false,
	msg_html = false,
	async_load = false,
	esperar_accion = false
) {
	const $modal_titulo = $elemento_modal.querySelector ('.modal-title');
	const $modal_footer = $elemento_modal.querySelector ('.modal-footer');
	const $modal_msg = 	  $elemento_modal.querySelector ('p[data-tipo="mensaje"]');
	const $modal_cont =   $elemento_modal.querySelector ('.modal-content');

	const footer_opciones = 
	'<div class="w-100 d-flex">' + 
		'<button class="btn btn-cancelar me-3" data-bs-dismiss="modal">Cancelar</button>' +
		'<button id="modal-continuar" type="button" class="btn btn-primario ms-3 continuar">Continuar</button>' +
	'</div>';
	const footer_default = 
		'<div class="d-flex m-0 ms-lg-auto">' + 
			'<button id="btn-aceptar" type="button" class="px-5 btn btn-primario" data-bs-dismiss="modal">Aceptar</button>'
		'</div>'
	;
	
	// Colocar modal en posición inicial
	$modal_cont.style.transform = "TranslateY(-50%)";
	$modal_titulo.textContent =   titulo;
	$modal_msg.textContent 	  =   msg_html ? '' : mensaje;
	if (msg_html) $modal_msg.insertAdjacentHTML ('beforeend', mensaje);
		
	$modal.show ();
	setTimeout (() => {
		// Mostrar modal en posición final (al centro de la pantalla)
		$modal_cont.style.transform = "TranslateY(0)";
		
		// Configurar elementos con fade scroll
		if(typeof ConfigurarFadeScroll === 'function') ConfigurarFadeScroll ();
	}, 200);
	
	// Reiniciar icono
	const $icono = $elemento_modal.querySelector ('.icono');
	$icono.innerHTML = '';
	
	// Asignar nuevo icono
	if (icono) $icono.insertAdjacentHTML ('beforeend', icono);

	// Asignar footer del modal
	$modal_footer.innerHTML = (!opciones) ? footer_default : footer_opciones;
	
	if (accion) {
		// Modal sin opciones
		if(!opciones) {
			$elemento_modal.addEventListener ('hide.bs.modal', () => {
				accion ();
			});
			return;
		}
		// Modal con opción de "continuar"
		const btn_continuar = $elemento_modal.querySelector ('.continuar');
		let btn_rollback = null;

		btn_continuar.addEventListener ('click', async () => {
			btn_continuar.disabled = true;
			if (opciones) {
				// El modal indicará la espera de la acción por realizar
				$btn = $modal_footer.querySelector ('#modal-continuar');
				btn_rollback = btnModoCarga ($btn);
			}
			if (esperar_accion) {
				// Cerrar modal al terminar la ejecución de la acción
				await accion ().then ((resultado) => {
					switch (resultado) {
						case 'reintentar': btnModoNormal ($btn, btn_rollback); break;
						default: 
							$modal.hide ();
					}
				});
			}
			// Cerrar modal al iniciar la ejecución de la acción
			else accion ();
		});
	}
}
