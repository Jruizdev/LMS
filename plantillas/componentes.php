<?php 
require_once ('../modulos/GestionUsuarios.php'); 
require_once ('../modulos/util/cookiescursos.php'); 
if (SesionIniciada ()) RecuperarSesion ();
?>
<!DOCTYPE html> 
<html lang="es">

	<!-- VENTANA FLOTANTE PARA INDICAR DAR INDICACIONES --> 
	 <template id="ventana-flotante">
	 	<div class="msg-flotante accordion" id="descripcionMsg">
			<div class="accordion-item shadow-sm border-0">
				<button class="btn-x position-absolute" style="top: 0; right: 0; z-index: 10;">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="15"><path fill="#58151c" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
				</button>
				<div class="accordion-header position-relative">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#detalleMsg" aria-expanded="false" aria-controls="detalleMsg">
					<div data-tipo="msg-corto" class="d-flex flex-row"></div>
				</button>
				</d>
				<div id="detalleMsg" class="accordion-collapse collapse" data-bs-parent="#descripcionMsg">
				<div class="accordion-body" data-tipo="msg-detalle"></div>
			</div>
		</div>
	 </template>

	<!-- TOOLTIP UNIVERSAL DE INPUT -->
	<template id="tooltip-input">
		<div>
			<span class="tooltip-input shadow">Tooltip</span>
		</div>
	</template>

	<!-- MODAL PARA VISUALIZAR CERTIFICADOS DE CURSOS INTERNOS -->
	<template id="mod-certificado">
		<div class="modal fade modal-certificado" data-bs-backdrop="static" data-bs-keyboard="false">
			<div class="modal-dialog modal-xl modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header py-3 px-4">
					<h5 class="modal-title">Certificado de curso interno</h5>
					<button type="button" class="btn-close btn-svg" data-componente="btn-cerrar"></button>
				</div>
				<div class="modal-body p-0 m-0">
					<div id="loader-logo" class="d-flex mx-auto" style="width: 250px; overflow-x: hidden; margin: var(--p5) 0;">
						<svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 731 386"><g fill="#F00" transform="translate(0.000000,386.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M5 3778 c-3 -7 -4 -386 -3 -843 l3 -830 60 3 c232 9 477 147 621 348 97 135 143 272 151 450 11 253 -72 470 -244 635 -106 102 -166 141 -293 191 -105 41 -286 69 -295 46z"/><path d="M1450 1905 l0 -395 470 0 470 0 0 280 0 280 -327 2 -328 3 -3 113 -3 112 -139 0 -140 0 0 -395z m660 -100 l0 -125 -190 0 -190 0 0 125 0 125 190 0 190 0 0 -125z"/><path d="M5640 1905 l0 -395 140 0 140 0 0 395 0 395 -140 0 -140 0 0 -395z"/><path d="M2500 1790 l0 -280 470 0 470 0 0 280 0 280 -470 0 -470 0 0 -280z m660 15 l0 -125 -190 0 -190 0 0 125 0 125 190 0 190 0 0 -125z"/><path d="M3540 1905 l0 -165 335 0 335 0 0 -30 0 -30 -335 0 -335 0 0 -85 0 -85 475 0 475 0 0 175 0 175 -335 0 -335 0 0 35 0 35 335 0 335 0 0 70 0 70 -475 0 -475 0 0 -165z"/><path d="M4590 2000 l0 -70 330 0 330 0 0 -35 0 -35 -330 0 -330 0 0 -175 0 -175 470 0 470 0 0 280 0 280 -470 0 -470 0 0 -70z m660 -290 l0 -30 -190 0 -190 0 0 30 0 30 190 0 190 0 0 -30z"/><path fill="#105BDE" d="M6373 2055 c-89 -39 -80 -188 13 -214 48 -13 82 -5 116 27 56 52 42 154 -27 186 -40 19 -60 20 -102 1z m108 -41 c35 -37 33 -98 -4 -130 -77 -66 -170 22 -122 116 17 32 27 38 71 39 21 1 38 -7 55 -25z"/><path fill="#105BDE" d="M6644 2061 c-39 -17 -64 -60 -64 -110 0 -83 62 -132 140 -111 52 14 98 80 55 80 -8 0 -15 -6 -15 -14 0 -21 -39 -46 -71 -46 -33 0 -79 35 -79 61 0 17 8 19 89 19 62 0 90 4 93 13 9 23 -12 77 -36 92 -30 20 -85 27 -112 16z m93 -36 c11 -8 23 -26 25 -40 l5 -25 -78 0 c-67 0 -79 2 -79 17 0 50 80 81 127 48z"/><path fill="#105BDE" d="M6915 2059 c-11 -7 -23 -17 -27 -23 -5 -7 -8 -4 -8 7 0 9 -7 17 -15 17 -12 0 -15 -19 -15 -110 0 -91 3 -110 15 -110 12 0 15 16 15 80 0 91 17 120 68 120 49 0 62 -26 62 -118 0 -63 3 -82 14 -82 10 0 15 20 17 79 5 93 22 121 75 121 49 0 64 -28 64 -122 0 -63 3 -78 15 -78 12 0 15 16 15 86 0 82 -1 87 -30 116 -27 26 -36 30 -68 25 -20 -4 -45 -16 -54 -28 l-18 -21 -17 21 c-21 26 -80 36 -108 20z"/><path fill="#105BDE" d="M6110 2031 c-13 -25 -4 -48 21 -56 38 -12 65 35 37 63 -18 18 -46 14 -58 -7z"/><path fill="#105BDE" d="M6110 1889 c-10 -18 -9 -25 8 -41 27 -28 65 -9 60 30 -4 36 -51 44 -68 11z"/><path d="M12 1772 c-10 -7 -12 -182 -10 -843 l3 -834 60 2 c307 11 623 246 726 540 109 313 38 649 -186 879 -128 131 -264 206 -430 238 -127 24 -149 26 -163 18z"/></g></svg>
						<div></div>
					</div>
				</div>
				<div class="modal-footer px-4">
					<div class="col-10 col-lg-3 ms-auto me-auto me-xl-0">
						<button type="button" class="btn btn-secondary" data-componente="btn-cerrar">Cerrar</button>
					</div>
				</div>
				</div>
			</div>
		</div>
	</template>

	<!-- MODAL UNIVERSAL PARA MOSTRAR MENSAJES DE ESTADO AL USUARIO -->
	<template id="mod-msg">
		<div class="modal fade p-0 m-0 mw-100" id="modal-msg" tabindex="-1" aria-labelledby="ModalMSG">
		  <div class="modal-dialog modal-dialog-centered">
			<div class="modal-content shadow" style="transition: all ease .5s">
			  <div class="modal-header">
				<h1 class="modal-title fs-5">Título del modal</h1>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			  </div>
			  <div class="modal-body d-flex">
				<div class="icono d-flex"></div>
				<p class="my-auto py-2 ms-2 w-100" data-tipo="mensaje">Mensaje</p>
			  </div>
			  <div class="modal-footer">
				<div class="d-flex">
					<button class="mx-auto" type="button" class="btn btn-primario" data-bs-dismiss="modal">Aceptar</button>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	</template>

	<!-- COMPONENTE DE VENTANA POR DEFECTO -->
	<template id="ventana">
		<div>
			<h2 data-tipo="titulo" class="py-3 px-5">Título</h2>
			<div data-tipo="header"></div>
			<!--div data-tipo="body" class="overflow-auto"></div-->
			<div data-tipo="body"></div>
			<div data-tipo="footer"></div>
		</div>
	</template>
	
	<!-- COMPONENTE DE VENTANA EXTENDIDA -->
	<template id="ventana-extendida">
		<div>
			<div class="ventana-extendida">
				<h2 data-tipo="titulo">Título</h2>
				<div data-tipo="header"></div>
				<div data-tipo="body"></div>
				<div data-tipo="footer"></div>
			</div>
		</div>
	</template>
	
	<!-- COMPONENTE DE VENTANA ELIMINABLE -->
	<template id="ventana-eliminable">
		<div>
			<div class="ventana-eliminable shadow">
				<div class="header">
					<h4 data-tipo="titulo">Título</h4>
					<button class="btn-ventana" onclick="EliminarPregunta(this.parentElement.parentElement)">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20"><path fill="#fff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
					</button>
				</div>
				<div data-tipo="body"></div>
				<div data-tipo="footer"></div>
			</div>
		</div>
	</template>
	
	<!-- COMPONENTE DE VENTANA DE TIPO "BLOQUE" -->
	<template id="bloque">
		<div>
			<h4 data-tipo="titulo">Título</h4>
			<div data-tipo="header"></div>
			<div data-tipo="body"></div>
			<div data-tipo="footer"></div>
		</div>
	</template>
	
	<!-- COMPONENTE DE BARRA DE BÚSQUEDA DE CURSOS -->
	<template id="busqueda-curso">
		<div class="busqueda-curso">
			<div class="d-flex flex-row">
				<div class="input-group">
					<input data-tipo="in-buscar" class="form-control" type="text" placeholder="Buscar curso por nombre o fecha..."/>
				</div>
				<button class="d-flex align-items-center" title="Buscar curso" disabled="true" data-tipo="btn-accion">
					Buscar
					<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#fff" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
				</button>
			</div>
		</div>
	</template>
	
	<!-- FOOTER PARA EXPANDIR LISTA EN UNA VENTANA -->
	<template id="footer-ventana">
		<a class="footer-ventana" href="#">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20"><path fill="#2b2b2b" d="M288 80c-65.2 0-118.8 29.6-159.9 67.7C89.6 183.5 63 226 49.4 256c13.6 30 40.2 72.5 78.6 108.3C169.2 402.4 222.8 432 288 432s118.8-29.6 159.9-67.7C486.4 328.5 513 286 526.6 256c-13.6-30-40.2-72.5-78.6-108.3C406.8 109.6 353.2 80 288 80zM95.4 112.6C142.5 68.8 207.2 32 288 32s145.5 36.8 192.6 80.6c46.8 43.5 78.1 95.4 93 131.1c3.3 7.9 3.3 16.7 0 24.6c-14.9 35.7-46.2 87.7-93 131.1C433.5 443.2 368.8 480 288 480s-145.5-36.8-192.6-80.6C48.6 356 17.3 304 2.5 268.3c-3.3-7.9-3.3-16.7 0-24.6C17.3 208 48.6 156 95.4 112.6zM288 336c44.2 0 80-35.8 80-80s-35.8-80-80-80c-.7 0-1.3 0-2 0c1.3 5.1 2 10.5 2 16c0 35.3-28.7 64-64 64c-5.5 0-10.9-.7-16-2c0 .7 0 1.3 0 2c0 44.2 35.8 80 80 80zm0-208a128 128 0 1 1 0 256 128 128 0 1 1 0-256z"/></svg>
			<h6>Ver sección completa</h6>
		</a>
	</template>
	
	<!-- FOOTER DE PAGINACIÓN PARA VENTANA -->
	<template id="footer-paginacion">
		<div class="footer-paginacion">
			<h5 data-info="pag-actual">Página 1 de 1</h5>
			<div class="paginas">
				<button data-tipo="sel-ant" class="selector">Anterior</button>
				<div class="num-pag" data-tipo="sel-paginas"></div>
				<button data-tipo="sel-sig" class="selector">Siguiente</button>
			</di>
		</div>
	</template>
	
	<!-- COMPONENTE PARA ENLISTAR ELEMENTOS (CURSOS O USUARIOS) -->
	<template id="lista">
		<div class="contenedor-lista">
			<ul class="m-0 pb-3"></ul>
		</div>
	</template>

	<!-- PROTOTIPO DE ELEMENTO PARA ENLISTAR LOS CURSOS DISPONIBLES -->
	<template id="curso-disponible">
		<li class="elemento-lista-curso p-0 d-flex flex-column">
			<div class="d-flex" style="min-width: 300px; height: 100%;">
				<svg class="portada p-4" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 66.26" style="enable-background:new 0 0 122.88 66.26" xml:space="preserve" width="68"><style type="text/css"><![CDATA[
					.st0{fill-rule:evenodd;clip-rule:evenodd;}
					]]></style><g><path class="st0" d="M2.73,60.82h10.51c-1-0.26-1.75-1.18-1.75-2.26V2.33c0-1.28,1.05-2.33,2.33-2.33h94.64 c1.28,0,2.33,1.05,2.33,2.33v56.22c0,1.08-0.74,2-1.75,2.26h11.12c1.5,0,2.73,1.22,2.73,2.73l0,0c0,1.5-1.22,2.73-2.73,2.73H2.73 c-1.5,0-2.73-1.22-2.73-2.73l0,0C0,62.04,1.22,60.82,2.73,60.82L2.73,60.82L2.73,60.82z M29.91,10.97h24.38v29.24 c-0.05,0.82-1.1,0.84-2.24,0.79H29.52c-1.58,0-2.87,1.29-2.87,2.87c0,1.58,1.29,2.87,2.87,2.87h23.57v-3.05h2.24v3.88 c0,0.71-0.58,1.28-1.28,1.28H29.63c-2.84,1.05-5.16-1.27-5.16-4.11V16.41C24.48,13.42,26.92,10.97,29.91,10.97L29.91,10.97z M66.73,47.29c-0.81,0-1.47-0.71-1.47-1.58s0.66-1.58,1.47-1.58h29.29c0.81,0,1.47,0.71,1.47,1.58s-0.66,1.58-1.47,1.58H66.73 L66.73,47.29z M66.52,12.78h32.27c0.69,0,1.26,0.57,1.26,1.26v4.5c0,0.68-0.57,1.26-1.26,1.26H66.52c-0.68,0-1.26-0.56-1.26-1.26 v-4.5C65.26,13.34,65.83,12.78,66.52,12.78L66.52,12.78z M66.73,29.63c-0.81,0-1.47-0.71-1.47-1.58c0-0.87,0.66-1.58,1.47-1.58 h27.28c0.81,0,1.47,0.71,1.47,1.58c0,0.87-0.66,1.58-1.47,1.58H66.73L66.73,29.63z M66.73,38.46c-0.81,0-1.47-0.71-1.47-1.58 s0.66-1.58,1.47-1.58h23.03c0.81,0,1.47,0.71,1.47,1.58s-0.66,1.58-1.47,1.58H66.73L66.73,38.46z M30.92,15.22h0.91 c0.46,0,0.84,0.31,0.84,0.68v21.37c0,0.37-0.38,0.68-0.84,0.68h-0.91c-0.46,0-0.84-0.31-0.84-0.68V15.9 C30.08,15.52,30.46,15.22,30.92,15.22L30.92,15.22z M15.47,3.65h91.65v54.24H15.47V3.65L15.47,3.65L15.47,3.65z M59.15,61.84h7.67 c0.72,0,1.31,0.59,1.31,1.31l0,0c0,0.72-0.59,1.31-1.31,1.31h-7.67c-0.72,0-1.31-0.59-1.31-1.31l0,0 C57.84,62.42,58.43,61.84,59.15,61.84L59.15,61.84L59.15,61.84z"/></g>
				</svg>
				<div class="d-flex flex-column w-100 py-3 px-4">
					<h4 class="mb-3"><b data-tipo="nombre-curso">Nombre del curso</b></h4>
					<p class="mt-3" data-tipo="descripcion">Descripción del curso Descripción del curso Descripción del curso Descripción del curso Descripción del curso</p>
					<div>
						<h6><b>Última modificación: </b></h6>
						<h6 data-tipo="fecha">dd/mm/aa</h6>
					</div>
					<div class="d-flex mt-auto">
						<button class="btn-primario ms-auto me-0 my-auto py-2 d-flex flex-row" onclick="AgregarCursoInteres (this, <?php echo $sesion->getNumNomina() ?>)">
							<span class="flex-fill">Me interesa</span>
							<svg class="ms-2" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" fill="none">
								<path fill="#FFF" fill-rule="evenodd" clip-rule="evenodd" d="M7.49991 0.876892C3.84222 0.876892 0.877075 3.84204 0.877075 7.49972C0.877075 11.1574 3.84222 14.1226 7.49991 14.1226C11.1576 14.1226 14.1227 11.1574 14.1227 7.49972C14.1227 3.84204 11.1576 0.876892 7.49991 0.876892ZM1.82707 7.49972C1.82707 4.36671 4.36689 1.82689 7.49991 1.82689C10.6329 1.82689 13.1727 4.36671 13.1727 7.49972C13.1727 10.6327 10.6329 13.1726 7.49991 13.1726C4.36689 13.1726 1.82707 10.6327 1.82707 7.49972ZM7.50003 4C7.77617 4 8.00003 4.22386 8.00003 4.5V7H10.5C10.7762 7 11 7.22386 11 7.5C11 7.77614 10.7762 8 10.5 8H8.00003V10.5C8.00003 10.7761 7.77617 11 7.50003 11C7.22389 11 7.00003 10.7761 7.00003 10.5V8H4.50003C4.22389 8 4.00003 7.77614 4.00003 7.5C4.00003 7.22386 4.22389 7 4.50003 7H7.00003V4.5C7.00003 4.22386 7.22389 4 7.50003 4Z" fill="#000000"/>
							</svg>
						</button>
					</div>
				</div>
			</div>
		</li>
	</template>
	
	<!-- PROTOTIPO DE ELEMENTO DE CURSO QUE SE UTILIZARÁ PARA ENLISTAR CURSOS CREADOS -->
	<template id="elemento-lista-CC">
		<li class="elemento-lista-curso">
			<div class="info d-flex flex-row px-1 w-100">
				<a class="portada d-flex ver-curso" href="#">
					<svg class="m-auto" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 66.26" style="enable-background:new 0 0 122.88 66.26" xml:space="preserve" width="68"><style type="text/css"><![CDATA[
					.st0{fill-rule:evenodd;clip-rule:evenodd;}
					]]></style><g><path class="st0" d="M2.73,60.82h10.51c-1-0.26-1.75-1.18-1.75-2.26V2.33c0-1.28,1.05-2.33,2.33-2.33h94.64 c1.28,0,2.33,1.05,2.33,2.33v56.22c0,1.08-0.74,2-1.75,2.26h11.12c1.5,0,2.73,1.22,2.73,2.73l0,0c0,1.5-1.22,2.73-2.73,2.73H2.73 c-1.5,0-2.73-1.22-2.73-2.73l0,0C0,62.04,1.22,60.82,2.73,60.82L2.73,60.82L2.73,60.82z M29.91,10.97h24.38v29.24 c-0.05,0.82-1.1,0.84-2.24,0.79H29.52c-1.58,0-2.87,1.29-2.87,2.87c0,1.58,1.29,2.87,2.87,2.87h23.57v-3.05h2.24v3.88 c0,0.71-0.58,1.28-1.28,1.28H29.63c-2.84,1.05-5.16-1.27-5.16-4.11V16.41C24.48,13.42,26.92,10.97,29.91,10.97L29.91,10.97z M66.73,47.29c-0.81,0-1.47-0.71-1.47-1.58s0.66-1.58,1.47-1.58h29.29c0.81,0,1.47,0.71,1.47,1.58s-0.66,1.58-1.47,1.58H66.73 L66.73,47.29z M66.52,12.78h32.27c0.69,0,1.26,0.57,1.26,1.26v4.5c0,0.68-0.57,1.26-1.26,1.26H66.52c-0.68,0-1.26-0.56-1.26-1.26 v-4.5C65.26,13.34,65.83,12.78,66.52,12.78L66.52,12.78z M66.73,29.63c-0.81,0-1.47-0.71-1.47-1.58c0-0.87,0.66-1.58,1.47-1.58 h27.28c0.81,0,1.47,0.71,1.47,1.58c0,0.87-0.66,1.58-1.47,1.58H66.73L66.73,29.63z M66.73,38.46c-0.81,0-1.47-0.71-1.47-1.58 s0.66-1.58,1.47-1.58h23.03c0.81,0,1.47,0.71,1.47,1.58s-0.66,1.58-1.47,1.58H66.73L66.73,38.46z M30.92,15.22h0.91 c0.46,0,0.84,0.31,0.84,0.68v21.37c0,0.37-0.38,0.68-0.84,0.68h-0.91c-0.46,0-0.84-0.31-0.84-0.68V15.9 C30.08,15.52,30.46,15.22,30.92,15.22L30.92,15.22z M15.47,3.65h91.65v54.24H15.47V3.65L15.47,3.65L15.47,3.65z M59.15,61.84h7.67 c0.72,0,1.31,0.59,1.31,1.31l0,0c0,0.72-0.59,1.31-1.31,1.31h-7.67c-0.72,0-1.31-0.59-1.31-1.31l0,0 C57.84,62.42,58.43,61.84,59.15,61.84L59.15,61.84L59.15,61.84z"/></g></svg>
				</a>
				<div class="w-100">
					<h4 class="mb-3"><b data-tipo="nombre-curso">Nombre del curso</b></h4>
					<h6><b>Última modificación: </b></h6><h6 data-tipo="fecha">dd/mm/aa</h6>
					<p class="mt-3" data-tipo="descripcion">Descripción del curso Descripción del curso Descripción del curso Descripción del curso Descripción del curso</p>
					<div class="opciones">
						<button class="btn-primario eliminar-curso" onclick="ConfirmarEliminacionCurso(this)">Eliminar</button>
						<a class="btn-secundario editar-curso" href="#"><p>Editar</p></a>
					</div>
				</div>
			</div>
			<div class="m-0 px-2 d-flex">
				<a class="ver-curso m-o d-flex" href="#">
					<svg class="my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="30px"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#DDD" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
				</a>
			</div>
		</li>
	</template>
	
	<!-- PROTOTIPO DE ELEMENTO DE CURSO QUE SE UTILIZARÁ PARA ENLISTAR CURSOS PENDIENTES -->
	<template id="elemento-lista-CP">
		<li class="elemento-lista-curso">
			<div class="info d-flex flex-row px-1 w-100">
				<a class="d-flex portada">
					<svg class="m-auto" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 66.26" style="enable-background:new 0 0 122.88 66.26" xml:space="preserve" width="80"><style type="text/css"><![CDATA[
					.st0{fill-rule:evenodd;clip-rule:evenodd;}
					]]></style><g><path class="st0" d="M2.73,60.82h10.51c-1-0.26-1.75-1.18-1.75-2.26V2.33c0-1.28,1.05-2.33,2.33-2.33h94.64 c1.28,0,2.33,1.05,2.33,2.33v56.22c0,1.08-0.74,2-1.75,2.26h11.12c1.5,0,2.73,1.22,2.73,2.73l0,0c0,1.5-1.22,2.73-2.73,2.73H2.73 c-1.5,0-2.73-1.22-2.73-2.73l0,0C0,62.04,1.22,60.82,2.73,60.82L2.73,60.82L2.73,60.82z M29.91,10.97h24.38v29.24 c-0.05,0.82-1.1,0.84-2.24,0.79H29.52c-1.58,0-2.87,1.29-2.87,2.87c0,1.58,1.29,2.87,2.87,2.87h23.57v-3.05h2.24v3.88 c0,0.71-0.58,1.28-1.28,1.28H29.63c-2.84,1.05-5.16-1.27-5.16-4.11V16.41C24.48,13.42,26.92,10.97,29.91,10.97L29.91,10.97z M66.73,47.29c-0.81,0-1.47-0.71-1.47-1.58s0.66-1.58,1.47-1.58h29.29c0.81,0,1.47,0.71,1.47,1.58s-0.66,1.58-1.47,1.58H66.73 L66.73,47.29z M66.52,12.78h32.27c0.69,0,1.26,0.57,1.26,1.26v4.5c0,0.68-0.57,1.26-1.26,1.26H66.52c-0.68,0-1.26-0.56-1.26-1.26 v-4.5C65.26,13.34,65.83,12.78,66.52,12.78L66.52,12.78z M66.73,29.63c-0.81,0-1.47-0.71-1.47-1.58c0-0.87,0.66-1.58,1.47-1.58 h27.28c0.81,0,1.47,0.71,1.47,1.58c0,0.87-0.66,1.58-1.47,1.58H66.73L66.73,29.63z M66.73,38.46c-0.81,0-1.47-0.71-1.47-1.58 s0.66-1.58,1.47-1.58h23.03c0.81,0,1.47,0.71,1.47,1.58s-0.66,1.58-1.47,1.58H66.73L66.73,38.46z M30.92,15.22h0.91 c0.46,0,0.84,0.31,0.84,0.68v21.37c0,0.37-0.38,0.68-0.84,0.68h-0.91c-0.46,0-0.84-0.31-0.84-0.68V15.9 C30.08,15.52,30.46,15.22,30.92,15.22L30.92,15.22z M15.47,3.65h91.65v54.24H15.47V3.65L15.47,3.65L15.47,3.65z M59.15,61.84h7.67 c0.72,0,1.31,0.59,1.31,1.31l0,0c0,0.72-0.59,1.31-1.31,1.31h-7.67c-0.72,0-1.31-0.59-1.31-1.31l0,0 C57.84,62.42,58.43,61.84,59.15,61.84L59.15,61.84L59.15,61.84z"/></g></svg>
				</a>
				<div class="d-flex flex-column w-100">
					<div>
						<h4><b data-tipo="nombre-curso">Nombre del curso</b></h4>
						<div class="my-2 d-flex"><div class="flex-column"><b>Versión del curso: </b><h6 class="ms-3 my-auto" data-tipo="version">1.0</h6></div></div>
						<p data-tipo="descripcion">Descripción del curso Descripción del curso Descripción del curso Descripción del curso Descripción del curso</p>
					</div>
					<div class="d-flex flex-row w-100">
						<button class="btn-ver-curso ms-auto w-100 d-flex m-0">
							<span style="white-space: nowrap;">Ir al curso</span>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20px"><path fill="#ffffff" d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
						</button>
					</div>
				</div>
			</div>
		</li>
	</template>
	
	<!-- ELEMENTO PARA MOSTRAR DETALLES (INFORMACIÓN) DE USUARIO -->
	<template id="bloque-info-usuario">
		<div class="bloque-ventana d-flex m-3 flex-wrap justify-content-between">
			<div class="d-flex m-auto flex-fill img-perfil" style="max-width: 300px">
				<div class="mx-auto my-3">
					<img src="" data-no_nomina="<?php echo $sesion->getNumNomina() ?>" hidden/>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#DDD" d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
				</div>
			</div>
			<div class="bloque-datos d-lg-flex flex-column flex-fill">
				<div class="d-flex flex-column mt-2"><div class="info-row"><h5 class="etiqueta"><b>Nombre: </b></h5></div><div class="info-row"><p data-info="nombre-empleado"><?php echo $sesion->getNombre() ?></p></div></div>
				<div class="d-flex flex-column"><div class="info-row"><h5 class="etiqueta"><b>Tipo de usuario: </b></h5></div><div><p class="info-row" data-info="tipo-empleado"><?php echo $sesion->getTipoUsuarioInfo() ?></p></div></div>
				<div class="d-flex flex-column"><div class="info-row"><h5 class="etiqueta"><b>Número de nómina: </b></h5></div><div class="info-row"><p data-info="nomina-empleado"><?php echo $sesion->getNumNomina() ?></p></div></div>
				<div class="d-flex flex-column"><div class="info-row"><h5 class="etiqueta"><b>Departamento: </b></h5></div><div class="info-row"><p data-info="departamento-empleado"><?php echo $sesion->getDepartamento() ?></p></div></div>
				<div class="d-flex flex-column"><div class="info-row"><h5 class="etiqueta"><b>Email: </b></h5></div><div class="info-row"><p data-info="email-empleado"><?php echo $sesion->getEmail() ?></p></div></div>
			</div>
		</div>
	</template>
	
	<!-- ELEMENTO PARA MOSTRAR ESTADÍSTICAS DE CURSOS DEL USUARIO -->
	<template id="bloque-info-estadisticas">
		<div class="bloque-ventana mb-3 d-flex flex-wrap w-100">
						
			<div class="info-general flex-column d-flex flex-md-row flex-grow-1 flex-column flex-md-row flex-xl-column">
				<div class="d-flex flex-column mb-4 flex-fill">
					<div class="d-flex w-100"><h6><b>Cursos aprobados:</b></h6><h6 class="ms-auto" data-info="aprobados">0 de 0</h6></div>
					<div class="d-flex w-100"><h6><b>Cursos externos:</b></h6><h6 class="ms-auto" data-info="certificaciones">0</h6></div>
					<div class="d-flex w-100"><h6><b>Cursos pendientes:</b></h6><h6 class="ms-auto" data-info="pendientes">0</h6></div>
				</div>
				<hr class="mx-4 my-1 border-0">
			</div>

			<!--hr class="mx-4 mx-lg-0"-->
			<div class="d-flex flex-fill">
				<button class="w-100 ms-auto btn-icono shadow d-flex flex-column py-3" style="background-color: transparent; border: 1px solid #ccc; color: #555;" onclick="window.location.replace('cursos_aprobados.php')">
					<svg class="mx-auto mt-auto mb-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="40"><path fill="#656565" d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9l0 28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5l0-24.6c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z"/></svg>
					<span class="mb-auto">Mis cursos aprobados</span>
				</button>
				<hr class="mx-2">
				<button class="w-100 me-auto btn-icono shadow d-flex flex-column py-3" style="background-color: transparent; border: 1px solid #ccc; color: #555;" onclick="window.location.replace('certificaciones_externas.php')">
					<svg class="mx-auto mt-auto mb-2" width="35" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 344.963 344.963" xml:space="preserve">
						<g>
							<path fill="#f00" style="fill: #656565;" d="M321.847,86.242l-40.026-23.11l-23.104-40.02h-46.213l-40.026-23.11l-40.026,23.11H86.239
								l-23.11,40.026L23.11,86.242v46.213L0,172.481l23.11,40.026v46.213l40.026,23.11l23.11,40.026h46.213l40.02,23.104l40.026-23.11
								h46.213l23.11-40.026l40.026-23.11v-46.213l23.11-40.026l-23.11-40.026V86.242H321.847z M156.911,243.075
								c-3.216,3.216-7.453,4.779-11.671,4.72c-4.219,0.06-8.455-1.504-11.671-4.72l-50.444-50.444c-6.319-6.319-6.319-16.57,0-22.889
								l13.354-13.354c6.319-6.319,16.57-6.319,22.889,0l25.872,25.872l80.344-80.35c6.319-6.319,16.57-6.319,22.889,0l13.354,13.354
								c6.319,6.319,6.319,16.57,0,22.889L156.911,243.075z"></path>
						</g>
					</svg>
					<span class="mb-auto">Mis cursos externos</span>
				</button>
			</div>

		</div>
	</template>

	<!-- COMPONENTE ELIMINABLE DE TAG -->
	<template id="tag-eliminable">
		<div class="tag mx-2 my-2">
			<span data-info="texto">Creación</span>
			<button>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="var(--entidad-secundario)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/></svg>
			</button>
		</div>
	</template>
	
	<!-- BLOQUE DE INFORMACIÓN DEL CURSO EN EL EDITOR -->
	<template id="info-version-curso">
		<div class="bloque-ventana">

			<div class="d-flex flex-column" data-componente="campo-descripcion">
				<div class="form-floating">
					<textarea id="descripcion-curso" maxlength="500" oninput="ValidarLongitudCampo (this, 500)" data-tipo="in-descripcion" class="form-control" placeholder="Descripción del curso..."><?php DescripcionCurso () ?></textarea>
					<label for="descripcion-curso">Descripción del curso</label>
				</div>
				<small class="ms-auto"><?php echo isset ($c_curso_interno) ? mb_strlen (htmlspecialchars_decode ($c_curso_interno->getDescripcion()), 'UTF-8') : '0' ?> / <b>Max:</b> 500</small>
			</div>

			<div class="d-flex flex-column mt-4" data-componente="campo-objetivo">
				<div class="form-floating">
					<textarea id="objetivo-curso" maxlength="500" oninput="ValidarLongitudCampo (this, 500)" data-tipo="in-objetivo" class="form-control" placeholder="Objetivo del curso..."><?php ObjetivoCurso () ?></textarea>
					<label for="objetivo-curso">Objetivo del curso</label>
				</div>
				<small class="ms-auto"><?php echo isset ($c_curso_interno) ? mb_strlen (htmlspecialchars_decode ($c_curso_interno->getObjetivo()), 'UTF-8') : '0' ?> / <b>Max:</b> 500</small>
			</div>

			<div class="d-flex flex-column mt-4 mb-3" data-componente="campo-img-curso">
				<h6 class="mb-3">Portada del curso (opcional):</h6>
				<input id="portada-curso" data-opcional="true" class="me-auto" type="file" accept=".jpg, .jpeg, .png">
			</div>
			
			<div class="d-flex flex-column mt-4 mb-3" data-componente="campo-comentarios">
				<div class="form-floating">
					<textarea id="comentarios-version" maxlength="300" oninput="ValidarLongitudCampo (this, 300)" data-tipo="in-comentarios" class="form-control" placeholder="Comentarios de la versión..."><?php ComentariosVersion ()?></textarea>
					<label for="descripcion-curso">Comentarios de la nueva versión</label>
				</div>
				<small class="ms-auto">0 / <b>Max:</b> 300</small>
			</div>

			<hr class="my-4">

			<div id="seccion-curso-modular">
				<h5>Asignación modular del curso</h5>
				<div class="d-flex flex-column">
					<span class="mt-3">¿Éste curso deberá ser asignado de manera automática después de aprobar otro curso?</span>
					<select class="form-control mt-2" onchange="DesplegarConfiguracionModular (this)">
						<option value="0">No</option>
						<option value="1">Si</option>
					</select>
				</div>
			
				<div id="conf-curso-modular" class="collapse">
					<div class="d-flex flex-column">
						<hr class="border-0 my-2">
						<label>El curso se asignará automáticamente al aprobar:</label>
						<div class="input-list" style="position: relative">
							<input id="curso-previo" class="form-control mt-3" width="1000px" data-id="-1" oninput="BuscarCursos (this)" onfocus="MostrarSugerencias ()" onblur="OcultarSugerencias ()" placeholder="Escribe o búsca el título del curso..."/>
							<ul class="shadow custom-datalist" hidden></ul>
						</div>
					</div>
				</div>
				
			</div>

			<hr class="my-4">
			<div>
				<h5 class="mb-2">Tags del curso</h5>
				<small>Agregar tags a tu curso facilitará su búsqueda para otros usuarios, una vez que tu curso sea publicado.</small>
				<hr class="my-2 border-0">
				<div class="agregar-tag">
					<input class="form-control" oninput="HabilitarBtnTag (this.parentElement)" placeholder="Escribe un tag aplicable a tu curso...">
					<button onclick="AgregarTag (this)" disabled>
						<span>Agregar</span>
						<svg class="m-auto ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#FFF" d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>
					</button>
				</div>
				<?php $info_tags = ObtenerTagsCurso () ?>
				<div id="tags-curso" class="collapse <?php echo $info_tags ['num_tags'] > 0 ? 'show' : '' ?>">
					<fieldset class="collapsable mt-3 d-flex flex-wrap">
						<legend class="muted">Tags aplicados</legend>
						<?php if (isset ($info_tags ['tags'])) echo $info_tags ['tags'] ?>
					</fieldset>
				</div>
			</div>

		</div>
	</template>
	
	<!-- COMPONENTE - TÍTULO PRINCIPAL DEL EDITOR DE CURSOS -->
	<template id="comp-titulo-principal">
		<div class="componente" data-tipo="titulo-principal">
			
			<div class="componente-body">
				<div class="contenido my-4">
					<input class="form-control" type="text" data-tipo="in-nombre-curso" placeholder="Ingresa el nombre del curso..."/>
				</div>
			</div>
			
		</div>
	</template>
	
	<!-- COMPONENTE DEL BANCO DE PREGUNTAS DEL EDITOR DE CURSOS -->
	<template id="banco-preguntas">
		<div class="banco-preguntas d-grid gap-4">
			<div>		
				<h5 class="mb-3"><b>Máximo número de preguntas por evaluación:</b></h5>
				<input id="max-preg" data-tipo="in-max-preguntas" type="number" min="5" max="50" value="5" onchange="DefinirMaxPreguntas (this.value);" class="form-control" placeholder="Indique la cantidad de preguntas que se mostrarán al usuario"/>
			</div>
			<div>
				<h5 class="mb-3"><b>Calificación mínima aprobatoria:</b></h5>
				<input id="calif-min" data-tipo="in-calif-min" type="number" onchange="DefinirCalificacionMin (this.value)" min="60" max="100" value="60" class="form-control"></input>
			</div>
			
			<div class="alert alert-primary d-flex align-items-center px-4 mb-0" role="alert">
				<svg class="me-3 mb-auto mt-2" style="float: left" fill="currentColor" viewBox="0 0 16 16" width="60">
					<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
				</svg>
			  <div>
				<p>En este apartado, debes crear todas las preguntas de evaluación que formarán parte del banco de preguntas para este curso. </p>
				<p><b>Nota:</b> Recuerda que el sistema seleccionará de manera aleatoria las preguntas para cada evaluación. 
				Asegúrate de incluir una variedad adecuada de preguntas para cubrir todos los temas y objetivos del curso.</p>
			  </div>
			</div>
			
			<div class="d-flex mx-auto my-2"><button class="btn-agregar mx-auto" onclick="AgregarNuevaPregunta ()">
				<svg class="me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#F00" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg>
				Agregar nueva pregunta
			</button></div>
			<div id="preguntas"></div>
		</div>
	</template>
	
	<!-- ELEMENTO DE PREGUNTA EN UNA EVALUACIÓN -->
	<template id="pregunta-evaluacion">
		<div class="pregunta-evaluacion d-flex">
			<div>
				<div class="id-pregunta">
					<svg fill="#000000" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
						<path fill="#555" d="M246.65625,132.43713l-45.625,68.43848.001-.001a15.96649,15.96649,0,0,1-13.31348,7.125H40a16.01833,16.01833,0,0,1-16-16v-128a16.01833,16.01833,0,0,1,16-16H187.71875a15.9687,15.9687,0,0,1,13.31348,7.126l45.624,68.43652A7.99771,7.99771,0,0,1,246.65625,132.43713Z"/>
					</svg>
					<span>1</span>
				</div>
			</div>
			<div class="flex-fill ms-4">
				<b><p data-tipo="preg_evaluacion">Pregunta</p></b>
				<ul class="opciones"></ul>
			</div>
		</div>
	</template>
	
	<!-- ELEMENTO DE PREGUNTA EN EL BANCO DE PREGUNTAS -->
	<template id="pregunta">
		<div class="pregunta" data-id_preg="1">
			<input data-tipo="pregunta" type="text" class="form-control mb-3" placeholder="Escriba la pregunta..."/>
			<div class="form-floating mb-3">
			  <input data-tipo="puntaje" name="puntajeCorrecta" type="number" min="5" max="20" class="form-control mb-3" placeholder="Valor en puntaje de respuesta correcta">
			  <label for="puntajeCorrecta">Valor en puntaje de respuesta correcta</label>
			</div>
			<p><b>Selecciona la respuesta correcta:</b></p>
			<div class="opciones">
				<div data-componente="opcion"></div>
			</div>
			<button class="btn-agregar d-flex" onclick="AgregarOpcionPregunta(this)">
				<svg class="me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#F00" d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>
				Agregar opcion
			</button>
		</div>
	</template>
	
	<!-- ELEMENTO DE OPCIÓN DE RESPUESTA EN EVALUACIÓN -->
	<template id="opcion-evaluacion">
		<li class="opcion-evaluacion my-3">
			<input class="form-check-input" style="border: 1px solid black;height: 1.6rem; width: 1.6rem;" type="radio">
			<label class="form-check-label ms-3">Opción</label>
		</li>
	</template>
	
	<!-- ELEMENTO DE OPCIÓN DE RESPUESTA EN BANCO DE PREGUNTAS -->
	<template id="opcion">
		<div class="opcion d-flex">
			<div class="mb-2 d-flex w-75">
				<input name="op_1" type="radio" class="form-check-input m-auto"/>
				<input type="text" class="form-control ms-4" placeholder="Escribe la opción..." data-alineacion="horizontal">
				<button class="eliminar-opcion" onclick="EliminarOpcionPegunta(this)">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#AAA" d="M170.5 51.6L151.5 80l145 0-19-28.4c-1.5-2.2-4-3.6-6.7-3.6l-93.7 0c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80 368 80l48 0 8 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-8 0 0 304c0 44.2-35.8 80-80 80l-224 0c-44.2 0-80-35.8-80-80l0-304-8 0c-13.3 0-24-10.7-24-24S10.7 80 24 80l8 0 48 0 13.8 0 36.7-55.1C140.9 9.4 158.4 0 177.1 0l93.7 0c18.7 0 36.2 9.4 46.6 24.9zM80 128l0 304c0 17.7 14.3 32 32 32l224 0c17.7 0 32-14.3 32-32l0-304L80 128zm80 64l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg>
				</button>
			</div>
		</div>
	</template>
	
	<!-- COMPONENTE DE TABLA - CURSOS INTERNOS (ADMINISTRACIÓN) -->
	<template id="tabla-admin-cursos-i">
		<table class="table">
			<thead>
				<tr>
					<th class="text-truncate" scope="col" style="vertical-align: middle">Curso</th>
					<th class="text-truncate" scope="col" style="vertical-align: middle">Creado por</th>
					<th class="text-truncate" scope="col">Version actual</th>
					<th class="text-truncate" scope="col">Última modificación</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<tr data-elemento="placeholder">
					<td colspan="5" class="text-center py-3"><h6 class="mb-0">No hay elementos</h6></td>
				</tr>
			</tbody>
		</table>
	</template>
	
	<!-- COMPONENTE DE TABLA - ASIGNACIÓN DE CURSOS EXTERNOS -->
	<template id="tabla-externos">
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Curso</th>
					<th scope="col">Descripción</th>
					<th scope="col">Fecha</th>
					<th scope="col" class="text-truncate">Vigencia</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<tr data-elemento="placeholder">
					<td colspan="5" class="text-center"><h6>No hay elementos</h6></td>
				</tr>
			</tbody>
		</table>
	</template>

	<!-- COMPONENTE DE TABLA - CURSOS EXTERNOS (CERTIFICACIONES) -->
	<template id="tabla-admin-cursos-e">
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Curso</th>
					<th scope="col">Descripción</th>
					<th scope="col">Fecha</th>
					<th scope="col">Validez</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<tr data-elemento="placeholder">
					<td colspan="5" class="text-center py-3"><h6 class="mb-0">No hay elementos</h6></td>
				</tr>	
			</tbody>
		</table>
	</template>
	
	<!-- COMPONENTE DE TABLA - CURSOS EXTERNOS (ADMINISTRACIÓN) -->
	<template id="tabla-admin-CE">
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Curso</th>
					<th scope="col">Fecha</th>
					<th scope="col">Validez</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</template>

	<!-- COMPONENTE DE TABLA - CERTIFICACIONES (ADMINISTRACIÓN) -->
	<template id="tabla-admin-Cert">
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Curso</th>
					<th></th>
					<th scope="col">Fecha</th>
					<th scope="col">Validez</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</template>
	
	<!--COMPONENTE DE HISTORIAL DE VERSIONES -->
	<template id="comp-historial-ver">
		<div>
		<div id="historial-versiones" class="modal fade" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5">Historial de versiones</h1>
					<button id="btn-cerrar" class="btn-svg ms-auto" type="button" data-bs-dismiss="modal" title="Cerrar historial" onclick="CerrarHistorial ()">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20"><path fill="#FFF" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
					</button>
				</div>
				<div class="modal-body py-4">
					<div class="d-flex flex-column">
						<div class="d-flex flex-column">
							<h6> Nombre del curso:</h6>
							<span data-info="curso">Curso</span>
						</div>
						<hr class="my-3">
						<div class="d-flex flex-column">
							<h6>Curso creado por:</h6>
							<span data-info="creado">Usuario</span>
						</div>
						<hr class="my-3">
						<div class="d-flex flex-column">
							<h6>Versión:</h6>
							<select class="form-select"></select>
						</div>
						<hr class="my-3 border-0">
						<div class="d-flex flex-column">
							<h6>Actualizado por:</h6>
							<span data-info="actualizado">Usuario</span>
						</div>
						<hr class="my-3 border-0">
						<div class="d-flex flex-column">
							<h6>Comentarios:</h6>
							<span data-info="comentarios">Comentarios de la versión</span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" title="Visualizar versión del curso" class="btn btn-svg py-2" onclick="VisualizarVersion ()">
						<span class="my-auto">Visualizar versión</span>
						<svg class="ms-2 my-autp" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>
					</button>
				</div>
				</div>
			</div>
		</div>
		</div>
	</template>
	<!-- COMPONENTE DE TABLA SIMPLE - EMPLEADOS -->
	<template id="tabla-empleados-simple">
		<table class="table">
			<thead>
				<tr class="text-break">
					<th class="text-center text-truncate" scope="col">Número de nómina</th>
					<th class="text-center text-start text-truncate" scope="col">Nombre</th>
					<th class="text-center text-start text-truncate" scope="col">Departamento</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<tr data-elemento="placeholder">
					<td colspan="5" class="text-center"><h6>No hay elementos</h6></td>
				</tr>
			</tbody>
		</table>
	</template>
	
	<!-- COMPONENTE DE TABLA - CURSOS APROBADOS -->
	<template id="tabla-cursos-aprobados">
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Curso</th>
					<th scope="col">Certificado</th>
					<th scope="col">Versión</th>
					<th scope="col">Aprobado</th>
					<th scope="col">Intentos</th>
					<th scope="col">Calificación</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</template>
	
	<!-- COMPONENTE DE TABLA - USUARIOS CERTIFICADOS (ADMMINISTRACIÓN) -->
	<template id="tabla-usuarios-cert">
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Nombre del empleado</th>
					<th scope="col">Nombre del curso</th>
					<th scope="col"></th>
					<th scope="col">Fecha</th>
					<th scope="col">Vigencia</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<tr data-elemento="placeholder">
					<td colspan="6" class="text-center"><h6 class="py-3">No hay elementos</h6></td>
				</tr>
			</tbody>
		</table>
	</template>
	
	<!-- COMPONENTE DE TABLA - CURSOS PENDIENTES (ADMINISTRADOR) -->
	<template id="tabla-CP-admin">
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Curso</th>
					<th scope="col"></th>
					<th scope="col">Versión</th>
					<th scope="col">Asignado</th>
					<th scope="col">Vencimiento</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</template>
	
	<!-- COMPONENTE DE TABLA - CURSOS CREADOS (ADMINISTRADOR) -->
	<template id="tabla-CC-admin">
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Curso</th>
					<th scope="col">Versión actual</th>
					<th scope="col">Historial de versiones</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</template>
	
	<!-- COMPONENTE DE TABLA - USUARIOS CON CURSOS PENDIENTES (ADMINISTRADOR) -->
	<template id="tabla-usuarios-CP">
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Nombre</th>
					<th scope="col" class="col-5">Curso</th>
					<th scope="col">Versión</th>
					<th scope="col"></th>
					<th scope="col">Asignado</th>
					<th scope="col">Vencimiento</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<tr data-elemento="placeholder">
					<td colspan="7" class="text-center"><h6 class="py-3">No hay elementos</h6></td>
				</tr>
			</tbody>
		</table>
	</template>
	
	<!-- COMPONENTE DE TABLA - USUARIOS APROBADOS (ADMINISTRADOR) -->
	<template id="tabla-usuarios-apr">
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Nombre</th>
					<th scope="col">Curso</th>
					<th scope="col">Versión</th>
					<th scope="col">Aprobado</th>
					<th scope="col">Asignado</th>
					<th scope="col">Intentos</th>
					<th scope="col">Calificación</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
			<tr data-elemento="placeholder">
					<td colspan="8" class="text-center"><h6 class="py-3">No hay elementos</h6></td>
				</tr>
			</tbody>
		</table>
	</template>
	
	<!-- COMPONENTE DE TABLA - BORRADORES (ADMINISTRADOR E INSTRUCTOR) -->
	<template id="tabla-borradores">
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Curso</th>
					<th scope="col">Última modificación</th>
					<th scope="col"></th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</template>
	
	<!-- ELEMENTO DE TABLA - CURSOS EXTERNOS -->
	<template id="curso-externo">
		<tr>
			<td class="text-truncate" style="vertical-align: middle;"><b data-info="nombre-curso">Nombre del curso</b></td>
			<td data-info="descripcion-curso" class="text-truncate col-3" style="max-width: 50px">Descripcion</td>
			<td data-info="fecha-curso" class="text-truncate">DD/MM/AAA</td>
			<td data-info="vigencia-curso" class="text-truncate">DD/MM/AAA</td>
			<td><button></button></td>
		</tr>
	</template>
	
	<!-- ELEMENTO DE TABLA - BORRADORES (ADMINISTRADOR E INSTRUCTOR) -->
	<template id="borrador">
		<tr>
			<td class="align-middle col-6">Nombre del curso</td>
			<td data-info="fecha-mod" class="align-middle col-3">DD/MM/AA</td>
			<td><button class="editar btn-primary px-3 py-2 min-vw-75 text-nowrap d-flex" href="#">
				Seguir editando
				<svg class="ms-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#fff" d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"/></svg>
			</button></td>
			<td><button class="descartar py-2 px-3 min-vw-50 text-nowrap d-flex me-3">
				Descartar
				<svg class="ms-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#fff" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"/></svg>
			</button></td>
		</tr>
	</template>
	
	<!-- ELEMENTO DE TABLA - USUARIOS APROBADOS (ADMINISTRADOR) -->
	<template id="usuario-apr">
		
		<tr>
			<td class="col-4 text-start text-truncate align-middle" style="max-width: 200px !important" data-info="empleado">Nombre del empleado</td>
			<td class="col-4 text-start text-truncate" style="max-width: 200px !important" data-info="curso">Nombre del curso</td>
			<td data-info="version">1.0</td>
			<td class="text-truncate" data-info="aprobado">dd/mm/aa</td>
			<td class="text-truncate" data-info="asignado">dd/mm/aa</td>
			<td data-info="intentos">1</td>
			<td data-info="calificacion">100</td>
			<td class="pe-3">
				<button data-info="ver" class="btn btn-svg" title="Ver curso">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20px"><path fill="#CCC" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
				</button>
			</td>
		</tr>

	</template>
	
	<!-- ELEMENTO DE TABLA - USUARIOS CON CURSOS PENDIENTES (ADMINISTRADOR) -->
	<template id="usuario-cert">
		
		<tr>
			<td class="align-middle text-truncate" data-info="empleado">Nombre del empleado</td>
			<td data-info="curso" class="text-start text-truncate">Nombre del curso</td>
			<td data-info="accion">
				<button class="btn-quitar btn w-100" style="border: 1px solid rgb(255, 0, 0); color: rgb(255, 0, 0);">
					<b>Eliminar</b>
				</button>
			</td>
			<td data-info="fecha" class="text-truncate">dd/mm/aa</td>
			<td data-info="vigencia" class="text-truncate">dd/mm/aa</td>
			<td data-info="btn-ver">
				<button class="btn btn-svg">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20px"><path fill="#CCC" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
				</button>
			</td>
		</tr>

	</template>
	
	<!-- ELEMENTO DE TABLA - USUARIOS CON CURSOS PENDIENTES (ADMINISTRADOR) -->
	<template id="usuario-CP">
		
		<tr>
			<td class="text-start align-middle text-truncate" data-info="nombre">Nombre del empleado</td>
			<td class="text-start align-middle" data-info="curso"><p class="mb-0">Nombre del curso</p></td>
			<td class="align-middle text-truncate" data-info="version">Versión</td>
			<td class="align-middle" data-info="btn-eliminar"><button class="btn btn-quitar" style="border: 1px solid rgb(255, 0, 0); color: rgb(255, 0, 0);"><b>Remover</b></button></td>
			<td class="align-middle text-truncate" data-info="fecha">dd/mm/aa</td>
			<td class="align-middle text-truncate" data-info="fecha_limite">dd/mm/aa</td>
			<td>
				<button class="btn-ir btn btn-svg">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20px"><path fill="#CCC" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
				</button>
			</td>
		</tr>

	</template>
	
	<!-- ELEMENTO DE TABLA - CURSO CREADO (ADMINISTRADOR) -->
	<template id="CC-admin">
		
		<tr>
			<td data-info="nombre-curso">Nombre del curso</td>
			<td data-info="version-curso">1.0</td>
			<td>
				<button data-info="btn-historial" class="btn px-5" style="background-color: transparent; border: 1px solid rgb(255, 0, 0); color: rgb(255, 0, 0);">
					<b>Abrir</b>
				</button>
			</td>
			<td>
				<button class="btn-ver btn btn-svg no-sombra" style="background-color: transparent">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20px"><path fill="#CCC" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
				</button>
			</td>
		</tr>

	</template>
	
	<!-- ELEMENTO DE TABLA - CURSO PENDIENTE (ADMINISTRADOR) -->
	<template id="CP-admin">
		
		<tr>
			<td data-info="nombre-curso">Nombre del curso</td>
			<td>
				<button data-info="btn-quitar" class="btn-quitar btn w-100" style="border: 1px solid rgb(255, 0, 0); color: rgb(255, 0, 0);">
					<b>Remover</b>
				</button>
			</td>
			<td data-info="version-curso">1.0</td>
			<td data-info="asignado">dd/mm/aa</td>
			<td data-info="vencimiento">dd/mm/aa</td>
			<td>
				<button class="btn-ver btn btn-svg">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20px"><path fill="#CCC" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
				</button>
			</td>
		</tr>

	</template>
	
	<!-- ELEMENTO DE TABLA - CURSO EXTERNO (ADMINISTRADOR, CONSULTA USUARIO) -->
	<template id="admin-CE">
		
		<tr>
			<td data-info="nombre">Nombre del curso</td>
			<td><button class="w-100" data-info="btn-eliminar">Eliminar</button></td>
			<td data-info="fecha">dd/mm/aa</td>
			<td data-info="validez">dd/mm/aa</td>
			<td>
				<button data-info="btn-ver">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20px"><path fill="#CCC" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
				</button>
			</td>
		</tr>

	</template>
	
	<!-- ELEMENTO DE TABLA - CURSOS APROBADO -->
	<template id="curso-aprobado">
		
		<tr>
			<td data-info="nombre">Nombre del curso</td>
			<td data-info="certificado">
				<button class="btn-svg px-2" style="color: #000; border: 1px solid #000;">
					<span><b>Ver</b></span>
					<svg class="ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20"><path fill="#000" d="M288 80c-65.2 0-118.8 29.6-159.9 67.7C89.6 183.5 63 226 49.4 256c13.6 30 40.2 72.5 78.6 108.3C169.2 402.4 222.8 432 288 432s118.8-29.6 159.9-67.7C486.4 328.5 513 286 526.6 256c-13.6-30-40.2-72.5-78.6-108.3C406.8 109.6 353.2 80 288 80zM95.4 112.6C142.5 68.8 207.2 32 288 32s145.5 36.8 192.6 80.6c46.8 43.5 78.1 95.4 93 131.1c3.3 7.9 3.3 16.7 0 24.6c-14.9 35.7-46.2 87.7-93 131.1C433.5 443.2 368.8 480 288 480s-145.5-36.8-192.6-80.6C48.6 356 17.3 304 2.5 268.3c-3.3-7.9-3.3-16.7 0-24.6C17.3 208 48.6 156 95.4 112.6zM288 336c44.2 0 80-35.8 80-80s-35.8-80-80-80c-.7 0-1.3 0-2 0c1.3 5.1 2 10.5 2 16c0 35.3-28.7 64-64 64c-5.5 0-10.9-.7-16-2c0 .7 0 1.3 0 2c0 44.2 35.8 80 80 80zm0-208a128 128 0 1 1 0 256 128 128 0 1 1 0-256z"/></svg>
				</button>
			</td>
			<td data-info="version">1.0</td>
			<td data-info="fecha" style="white-space: nowrap;">dd/mm/aa</td>
			<td data-info="intentos">1</td>
			<td data-info="puntaje">100</td>
			<td data-info="ir">
				<button>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20px"><path fill="#CCC" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
				</button>
			</td>
		</tr>

	</template>
	
	<!-- ELEMENTO DE TABLA - CURSOS INTERNOS (ADMINISTRACIÓN) -->
	<template id="admin-curso-i">
		
		<tr>
			<td class="text-truncate" data-info="nombre">Nombre del curso</td>
			<td class="text-truncate" data-info="usuario">Nombre del usuario</td>
			<td data-info="version">1.0</td>
			<td class="text-truncate" data-info="fecha">dd/mm/aa</td>
			<td>
				<button data-tipo="btn-ver" class="btn btn-svg">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20px"><path fill="#CCC" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
				</button>
			</td>
		</tr>

	</template>

	<!-- ELEMENTO DE TABLA - CURSOS EXTERNOS (ADMINISTRACIÓN) -->
	<template id="admin-curso-e">
		
		<tr>
			<td data-info="nombre-curso">Nombre del curso</td>
			<td data-info="descripcion-curso" class="text-truncate" style="max-width: 150px;">Descripción</td>
			<td data-info="fecha-curso">dd/mm/ss</td>
			<td data-info="validez-curso">dd/mm/aa</td>
			<td data-info="eliminar">
				<button class="btn btn-svg" title="Eliminar curso externo">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#AAA" d="M170.5 51.6L151.5 80l145 0-19-28.4c-1.5-2.2-4-3.6-6.7-3.6l-93.7 0c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80 368 80l48 0 8 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-8 0 0 304c0 44.2-35.8 80-80 80l-224 0c-44.2 0-80-35.8-80-80l0-304-8 0c-13.3 0-24-10.7-24-24S10.7 80 24 80l8 0 48 0 13.8 0 36.7-55.1C140.9 9.4 158.4 0 177.1 0l93.7 0c18.7 0 36.2 9.4 46.6 24.9zM80 128l0 304c0 17.7 14.3 32 32 32l224 0c17.7 0 32-14.3 32-32l0-304L80 128zm80 64l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg>
				</button>
			</td>
		</tr>

	</template>

	<!-- ELEMENTO DE TABLA - CURSOS EXTERNOS (USUARIOS) -->
	<template id="curso-e">
		
		<tr>
			<td data-info="nombre-curso">Nombre del curso</td>
			<td data-info="fecha-curso">dd/mm/ss</td>
			<td data-info="validez-curso">dd/mm/aa</td>
			<td data-info="ir">
				<button class="btn btn-svg" title="Ver certificación">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20px"><path fill="#CCC" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
				</button>
			</td>
		</tr>

	</template>

	<!-- ELEMENTO DE TABLA - ASIGAR EMPLEADOS SIMPLE (EN POSIBLE DESUSO) -->
	<template id="empleado-asignar">
		
		<tr>
			<td class="text-center text-truncate" style="vertical-align: middle;"><b data-tipo="id-nomina">0000</b></td>
			<td class="text-start text-truncate" data-tipo="nombre-empleado">Nombre del empleado</td>
			<td class="text-center" data-tipo="departamento">Departamento</td>
			<td>
				<button onclick="ExcluirRegistro (this, 'resultado-empleado', 'empleados-agregados', () => bs_collapse_curso.hide())">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30px"><path fill="#569c38" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg>
				</button>
			</td>
		</tr>

	</template>

	<!-- ELEMENTO DE TABLA - DESASIGNAR EMPLEADOS -->
	<template id="empleado-desasignar">
		
		<tr>
			<td><b data-tipo="id-nomina">0000</b></td>
			<td class="text-start" data-tipo="nombre-empleado">Nombre del empleado</td>
			<td class="text-start" data-tipo="departamento">Departamento</td>
			<td>
				<button onclick="ExcluirRegistro (this, 'tabla-empleados', 'tabla-excluidos', () => bs_collapse_curso.hide())">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30"><path fill="#f00" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM184 232l144 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-144 0c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg>
				</button>
			</td>
		</tr>

	</template>

	<!-- ELEMENTO DE TABLA - ASIGAR CURSO -->
	<template id="curso-asignar">
		
		<tr>
			<td class="text-truncate align-middle col-5" style="max-width: 100px;"><b data-info="nombre-curso">Nombre del curso</b></td>
			<td data-info="usuario" class="col-3 text-truncate text-start" style="max-width: 50px">Nombre usuario</td>
			<td data-info="version">1.0</td>
			<td data-info="fecha">dd/mm/aa</td>
			<td class="d-flex">
				<button data-info="visualizar" class="btn-primario me-4" title="Previsualizar curso">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="30"><path fill="#999" d="M288 80c-65.2 0-118.8 29.6-159.9 67.7C89.6 183.5 63 226 49.4 256c13.6 30 40.2 72.5 78.6 108.3C169.2 402.4 222.8 432 288 432s118.8-29.6 159.9-67.7C486.4 328.5 513 286 526.6 256c-13.6-30-40.2-72.5-78.6-108.3C406.8 109.6 353.2 80 288 80zM95.4 112.6C142.5 68.8 207.2 32 288 32s145.5 36.8 192.6 80.6c46.8 43.5 78.1 95.4 93 131.1c3.3 7.9 3.3 16.7 0 24.6c-14.9 35.7-46.2 87.7-93 131.1C433.5 443.2 368.8 480 288 480s-145.5-36.8-192.6-80.6C48.6 356 17.3 304 2.5 268.3c-3.3-7.9-3.3-16.7 0-24.6C17.3 208 48.6 156 95.4 112.6zM288 336c44.2 0 80-35.8 80-80s-35.8-80-80-80c-.7 0-1.3 0-2 0c1.3 5.1 2 10.5 2 16c0 35.3-28.7 64-64 64c-5.5 0-10.9-.7-16-2c0 .7 0 1.3 0 2c0 44.2 35.8 80 80 80zm0-208a128 128 0 1 1 0 256 128 128 0 1 1 0-256z"/></svg>
				</button>
				<button data-eliminar="exclusion" title="Agregar curso a la lista" onclick="IncluirRegistro (this, 'cursos-seleccion', 'resultado-curso')">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30px"><path fill="#569c38" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg>
				</button>
			</td>
		</tr>
 
	</template>
	
	<!-- ELEMENTO DE VENTANA PARA BUSCAR EMPLEADO POR NÚMERO DE NÓMINA -->
	<template id="buscar-empleado-nomina">
		<form>
			<div class="personalizado my-5 d-flex flex-col flex-xl-row ms-xl-0">
				<div class="w-100 d-flex ms-3 flex-column my-auto">
					<div class="d-grid gap-2">
						<h5>Selecciona el empleado para crear el usuario:</h5>
						<input type="number" name="no_nomina" class="form-control" placeholder="Número de nómina" />
					</div>
					<div class="d-flex flex-column w-100">
					<button class="btn btn-svg btn-secondary ms-md-auto mt-4 py-3 px-5" data-tipo="btn-accion">
						<span>Buscar empleado</span>
						<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#FFF" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
					</button>
					</div>
				</div>
			</div>
		</form>
	</template>
	
	<!-- ELEMENTO PARA MOSTRA INFORMACIÓN BÁSICA DE UN EMPLEADO -->
	<template id="info-empleado">

		<div class="d-flex flex-wrap">
		<div class="d-flex mx-auto ms-lg-3 my-auto img-perfil order-1 order-md-2 px-3" style="max-width: 250px;">
			<div class="mx-auto my-3 position-relative">
				<img id="img-empleado" style="min-width: 100%" data-info="img" src=""/>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#DDD" d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
			</div>
		</div>
		<div class="flex-fill my-auto order-2 order-md-1">
			<div class=" mt-3 gap-3 flex-fill">
				<div class="row"><h5 class="me-3 col w-50 text-truncate"><b>Número de nómina:</b></h5><h6 class="text-truncate col" data-info="no_nomina">0000</h6></div>
				<div class="row"><h5 class="me-3 col w-50 text-truncate"><b>Nombre:</b>		 	</h5><h6 class="text-truncate col" data-info="nombre">Nombre del empleado</h6></div>
				<div class="row"><h5 class="me-3 col w-50 text-truncate"><b>Departamento:</b>	</h5><h6 class="text-truncate col" data-info="departamento">Departamento</h6></div>
				<div class="row"><h5 class="me-3 col w-50 text-truncate"><b>Email:</b>			</h5><h6 class="text-truncate col" data-info="email">Correo electrónico</h6></div>
				<div class="row"><h5 class="me-3 col w-50 text-truncate"><b>Tipo de usuario:</b></h5><h6 class="text-truncate col" data-info="tipo">Tipo</h6></div>
			</div>
		</div>
		</div>

	</template>
	
</html>