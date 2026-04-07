const $scroll_elemento_top = 	document.querySelector 	  ('.scroll-elemento-top');
const $scroll_elemento_bottom = document.querySelector 	  ('.scroll-elemento-bottom');
const $scroll_top = 			document.querySelector 	  ('.scroll-top');
const $scroll_bottom = 			document.querySelector 	  ('.scroll-bottom');
const $elemento_top = 			document.querySelector 	  ('div[data-pos="elemento-top"]');
const $elemento_bottom =		document.querySelector 	  ('div[data-pos="elemento-bottom"]');

let observadores_fade_scroll = 	[];
let $elementos_fade_scroll =	null;

let objetivo = 	  null; 
let scroll_auto = false;

function ConfigurarFadeScroll () {
	
	const comprobarFadeScroll = (componente) => {
		if (componente.scrollHeight > componente.clientHeight) {
			// Es necesario 
			componente.style.maskImage = 		'linear-gradient(0, rgba(0, 0, 0, 0) 5%, #ffffff, 70%, #ffffff, 80%, rgb(255 255 255))';
			componente.style.webkitMaskImage = 	'linear-gradient(0, rgba(0, 0, 0, 0) 5%, #ffffff, 70%, #ffffff, 80%, rgb(255 255 255))';
			componente.dataset.fade_scroll = 	'1';
		} 
		else {
			// No es necesario 
			componente.style.maskImage = 		'none';
			componente.style.webkitMaskImage = 	'none';
			componente.dataset.fade_scroll = 	'0';
		}
	};
	
	// Obtener todos los elementos de la página con fade dinámico
	$elementos_fade_scroll = document.querySelectorAll ('.fade-scroll');
	$elementos_fade_scroll.forEach ((fs) => {
		
		comprobarFadeScroll (fs);
		
		// Esperar a que elemento cambie de tamaño y determinar si es necesario el efecto
		const observer = new MutationObserver(() => {
			comprobarFadeScroll (fs);
		});
		observadores_fade_scroll.push (observer);
		observer.observe (fs, {childList: true, subtree: true});
		
		fs.addEventListener ("scroll", () => {
			
			if (fs.dataset.fade_scroll === '0') return;
			
			const scrollTop = 	 fs.scrollTop; 	  				// Desplazamiento vertical actual
			const scrollHeight = fs.scrollHeight; 				// Altura total del contenido
			const clientHeight = fs.clientHeight; 				// Altura visible del elemento
			const maxScroll = 	 scrollHeight - clientHeight; 	// Desplazamiento máximo
			
			const fx = (x) => {
				// Efecto exponencial de fade
				return x > 0.8 ? 9 * Math.pow (x - 0.6, 2) : 0;
			}

			// Calcular el porcentaje de desplazamiento
			const scrollRatio = Math.min (scrollTop / maxScroll, 1);

			// Actualizar el efecto "mask-image" en función del desplazamiento
			fs.style.maskImage = 		`linear-gradient(0, rgba(0, 0, 0, ${fx(scrollRatio)}) 5%, #ffffff, 70%, #ffffff, 80%, rgb(255 255 255))`;
			fs.style.webkitMaskImage = 	`linear-gradient(0, rgba(0, 0, 0, ${fx(scrollRatio)}) 5%, #ffffff, 70%, #ffffff, 80%, rgb(255 255 255))`;
		});
	});
}

const observerCallback = (entries) => {
    entries.forEach (entry => {
        if (entry.target === $elemento_top) {
            if (entry.isIntersecting) $scroll_elemento_top.classList.remove ('activo');
            else $scroll_elemento_top.classList.add ('activo');
        } 
		else if (entry.target === $elemento_bottom) {
            if (entry.isIntersecting) $scroll_elemento_bottom.classList.remove ('activo');
            else $scroll_elemento_bottom.classList.add ('activo');
        }
    });
};

const observerOptions = {
    root: null,    // Usa la ventana como viewport
    threshold: 0.1 // Detectar si al menos el 10% del elemento está visible
};

/* 
// Crear observador únicamente si existen elementos de referencia superior
// e inferior para el scroll.
*/
const observer = ($scroll_elemento_top && $scroll_elemento_bottom) ? 
				 new IntersectionObserver (observerCallback, observerOptions) :
				 null;
				 
if (observer) {
	observer.observe ($elemento_top);
	observer.observe ($elemento_bottom);
}

window.onscroll = function (e) {
	if(!$scroll_top || !$scroll_bottom) return;
	
	// Definir scroll por defecto en caso de que no haya elementos de referencia
	let scroll_y = 		window.scrollY,
	doc_height = 		document.documentElement.scrollHeight,
	window_height = 	window.innerHeight;
	porcentaje_scroll = (scroll_y / (doc_height - window_height)) * 100;
	
	if(porcentaje_scroll > 50) $scroll_top.classList.add ('activo');
	else $scroll_top.classList.remove ('activo');
	
	if(porcentaje_scroll > 30) $scroll_bottom.classList.remove ('activo');
	else $scroll_bottom.classList.add ('activo');
}

function scrollAElemento ($elemento, timeout_ms = null) {
	if (!timeout_ms)
		$elemento.scrollIntoView ({ behavior: "smooth", block: "center"});
	else
		setTimeout (() => {
			$elemento.scrollIntoView ({ behavior: "smooth", block: "center"});
		}, timeout_ms);
}

function crearElementoStickyTop (
	elemento_sticky, 
	contenedor_principal, 
	siguiente_elemento, 
	elemento_superior
) {
	let placeholder = null;
	
	const handleScroll = () => {
		if(!elemento_superior) return;
		
		// Bounding Client Rects
		const SE_BCR = 		siguiente_elemento.getBoundingClientRect();
		const ESup_BCR = 	elemento_superior.getBoundingClientRect();
		const EStick_BCR = 	elemento_sticky.getBoundingClientRect();

		const elementoSuperiorBottom = 	ESup_BCR.bottom;
		const SETop = 					SE_BCR.top - ESup_BCR.height * 2;
		const SEBottom = 				SE_BCR.bottom - (EStick_BCR.height * 2);
		
		const posicionRelativa = () => {
			elemento_sticky.style.position = 'relative';
			elemento_sticky.style.top = 	 '';
			elemento_sticky.style.width = 	 '';
			elemento_sticky.style.zIndex = 	 '';
			elemento_sticky.classList.remove ('shadow-sm');
		}
		
		const posicionFija = () => {
			elemento_sticky.style.position = 'fixed';
			elemento_sticky.style.top =    `${elementoSuperiorBottom}px`;
			elemento_sticky.style.transform =   'translateX(-1px)';
			elemento_sticky.style.width =  `${siguiente_elemento.offsetWidth + 2}px`; // Ajusta el ancho
			elemento_sticky.style.zIndex = '9';
			elemento_sticky.classList.add ('shadow-sm');
		}
		
		const agregarPlaceholder = () => {
			if (!placeholder) {
			  // Crear elemento placeholder (en caso de que no exista)
			  placeholder = 			 document.createElement('div');
			  placeholder.style.height = `${elemento_sticky.offsetHeight}px`;
			  contenedor_principal.insertBefore (placeholder, elemento_sticky);
			}
		}
		
		const quitarPlaceholder = () => {
			if (placeholder) {
			  // Quitar placeholder 
			  contenedor_principal.removeChild (placeholder);
			  placeholder = null;
			}
		}

		if (elementoSuperiorBottom >= SETop && 
			SEBottom > elementoSuperiorBottom && 
			SE_BCR.height >= (EStick_BCR.height * 2)) {
			// Activar posición fija para el elemento sticky
			agregarPlaceholder ();
			posicionFija ();
		} 
		
		else if (SEBottom <= elementoSuperiorBottom) {
			// Restaurar posición relativa
			posicionRelativa ();
			quitarPlaceholder ();
		}
		
		else {
			// Mantener elemento sticky dentro del contenedor
			posicionRelativa ();
			quitarPlaceholder ();
		}
	};
	window.addEventListener('scroll', handleScroll);
}

/*function ScrollElementoTop () {
	objetivo = $elemento_top.offsetTop;
	$elemento_top.scrollIntoView ({
		behavior: 'smooth', block: "center" 
	});
}*/
function ScrollElementoBottom () {
	objetivo = $elemento_bottom.offsetTop;
	$elemento_bottom.scrollIntoView ({ 
		behavior: 'smooth', block: "start" 
	});
}
function ScrollTop () {
	objetivo = 0;
	window.scrollTo ({ 
		top: 0, behavior: 'smooth' 
	});
}
function ScrollBottom () {
	objetivo = document.body.scrollHeight;
	window.scrollTo ({ 
		top: document.body.scrollHeight, behavior: 'smooth' 
	});
}

window.addEventListener ("scroll", () => {
	if (window.scrollY >= objetivo && 
		window.scrollY <= objetivo + document.documentElement.clientHeight) {
		// No se está realizando scroll automático
		scroll_auto = false; return;
	}
	// Se está realizando scroll automático
	scroll_auto = true; 
});

window.onmousedown = function () {
	if(scroll_auto) {
		// Detener scroll automático si se da clic
		window.scrollTo({ top: window.scrollY, behavior: 'auto' });
	}
}
