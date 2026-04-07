// Elementos para la paginación en modal
const $modal_evaluacion =	document.querySelector 	  ('#modal-evaluacion');
const $nav_carrusel = 		document.querySelector 	  ('.nav-btns');
const $nav_enviar = 		document.querySelector 	  ('.ultima-pag');
const $header_pag =			document.querySelector 	  ('.modal-header h6');
const $btns_nav_carrusel = 	document.querySelectorAll ('.nav-evaluacion button');

let $indicadores_carrusel = null;
let $paginas_modal = 		null;
let total_paginas = 		null;
let pag_actual = 	   		0;

$modal_evaluacion?.addEventListener ('show.bs.modal', () => {
	CalcularPaginasModal ();
});

function PagSigEvaluacion () {
	CalcularPaginasModal ();
	
	// Calcular página anterior y página actual en carrusel
	pag_actual =           pag_actual < total_paginas - 1 ? (pag_actual + 1) : 0;
    const pag_anterior =   pag_actual > 0 ? pag_actual - 1 : total_paginas - 1;
	
	IrAPaginaEvaluacion (pag_anterior, pag_actual, 1);
}

function PagAntEvaluacion () {
	CalcularPaginasModal ();
	
	// Calcular página siguiente y página actual en carrusel
	pag_actual = 	pag_actual > 0 ? pag_actual - 1 : total_paginas - 1;
    const pag_sig = pag_actual < total_paginas - 1 ? pag_actual + 1 : 0;
	
	IrAPaginaEvaluacion (pag_sig, pag_actual, -1);
}

function PagIndicadorEvaluacion ($btn_indicador) {
	const pagina_nueva = $btn_indicador.dataset.pagina;
	if (pagina_nueva == pag_actual) return;
	
	CalcularPaginasModal ();
	
	// Ir a la página indicada, calculando la dirección de navegación
	IrAPaginaEvaluacion (pag_actual, pagina_nueva, (pagina_nueva > pag_actual ? 1 : -1));
	pag_actual = parseInt(pagina_nueva);
}

function CalcularPaginasModal () {
	$paginas_modal = $paginas_modal ? 
					 $paginas_modal : 
					 document.querySelectorAll ('.carousel-item');
					 
	total_paginas = total_paginas ? 
					total_paginas : 
					$paginas_modal.length;
	
	if (total_paginas <= 1) {
		// La evaluación únicamente contiene 1 página y no es necesaria la paginación
		$nav_carrusel.classList.remove ('visible');
		$nav_enviar.classList.add ('visible');
		
		// Eliminar botón de página anterior, en caso de que no haya sido eliminado
		$btn_pag_anterior = document.querySelector (
			'#modal-evaluacion .ultima-pag .btn-secundario'
		);
		if ($btn_pag_anterior) $btn_pag_anterior.remove();
	}
}

function ObtenerIndicadoresCarrusel () {
	return $indicadores_carrusel ? $indicadores_carrusel : 
			document.querySelectorAll ('.indicadores-nav button');
}

function ActivarNavCarrusel (ultima_pagina) {
	if (ultima_pagina) {
		$nav_carrusel.classList.remove ('visible');
		$nav_enviar.classList.add ('visible');
	} else {
		$nav_enviar.classList.remove ('visible');
		$nav_carrusel.classList.add ('visible');
	}
}

function RealizarCambioPagina (
	$paginas_modal, pag_actual, pag_nueva, pag_entrada, pag_salida
) {

	$paginas_modal [pag_actual].classList.add (pag_salida);
    $paginas_modal [pag_nueva].classList.add (pag_entrada);
    $paginas_modal [pag_nueva].classList.add ('active');
	
	$btns_nav_carrusel.forEach (($btn) => { $btn.disabled = true; });
	$indicadores_carrusel [pag_nueva].classList.add ('active');
	$indicadores_carrusel [pag_actual].classList.remove ('active');

	ConfigurarFadeScroll ();
	
	setTimeout (() => {
        $paginas_modal.forEach (($pagina) => { 
			$pagina.classList.remove ('active', 
			pag_salida, pag_entrada); 
		});
		$btns_nav_carrusel.forEach (($btn) => { $btn.disabled = false; });
        $paginas_modal [pag_nueva].classList.add ('active');
		
		// Comprobar "fade scroll" después de cambiar página
		ConfigurarFadeScroll ();
    }, 400);

	// Realizar scroll hasta la parte superior de la página activa de la evaluación
	const contenedor_evaluacion = document.querySelector('.fade-scroll');
	contenedor_evaluacion.scrollTo ({top: 0});

	// Actualizar indicador de página actual en el header del modal
	$header_pag.textContent = "Página: " + (parseInt(pag_nueva) + 1) + " de " + $paginas_modal.length;
}

function IrAPaginaEvaluacion (pag_actual, pag_nueva, direccion) {
	
	const pag_salida = 		direccion === 1 ? 
							'sig-preg-out' : direccion === -1 ? 
							'prev-preg-out' : 
							'';
	const pag_entrada = 	direccion === 1 ? 
							'sig-preg-in' : direccion === -1 ? 
							'prev-preg-in' : 
							'';
	$indicadores_carrusel = ObtenerIndicadoresCarrusel ();
	
	ActivarNavCarrusel (pag_nueva == total_paginas - 1);

	// Error al calcular las páginas
	if (!$paginas_modal [pag_actual] || 
		!$paginas_modal [pag_nueva]) return false;
	
	// Realizar cambio de página con animación
	RealizarCambioPagina (
		$paginas_modal, pag_actual, pag_nueva, pag_entrada, pag_salida
	);
	
	return true;
}