<?php 
require_once ('../modulos/util/cookiescursos.php');
require_once ('../modulos/GestionUsuarios.php');
if (SesionIniciada ()) RecuperarSesion (); 
?>
<!DOCTYPE html> 
<html lang="es">
	<!-- BARRA DE NAVEGACIÓN PARA INICIO DE SESIÓN -->
	<template id="navbar">
		<nav class="navbar fixed-top p-0" data-bs-theme="light">
		  <div class="container-fluid m-0 p-0">
			<a class="navbar-brand bg-white m-0 p-4" href="index.php">
				<div data-componente="logo-entidad"></div>
			</a>
		  </div>
		</nav>
	</template>

	<!-- BARRA DE NAVEGACIÓN PARA PÁGINA DE CURSOS DE CAPACITACIÓN -->
	<template id="navbar-capacitaciones">
		<nav class="navbar bg-white navbar-expand-xl d-flex flex-column" data-bs-theme="light">
			<div class="d-flex mx-auto">

				<div data-componente="aftermarket-lg"></div>

				<div class="d-flex flex-column mt-3" style="width: 800px">
					<a class="navbar-brand" href="capacitaciones.php">
						<div data-componente="logo-entidad"></div>
					</a>
					<input class="mx-0 my-3 form-control" type="text" placeholder="Buscar cursos..." class="form-control my-auto ms-4">
				</div>
				
			</div>
		</nav>
	</template>
	
	<!-- BARRA DE NAVEGACIÓN PARA USUARIOS ADMINISTRADORES -->
	<template id="navbar-sesion-admin">
		<nav class="navbar fixed-top bg-white shadow navbar-expand-xl d-flex flex-column" data-bs-theme="light">
		  <div class="container-fluid">
			<div data-componente="aftermarket"></div>
			<a class="navbar-brand" href="inicio.php">
				<div data-componente="logo-entidad"></div>
			</a>
			
			<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
			  <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path d="M24 6h-24v-4h24v4zm0 4h-24v4h24v-4zm0 8h-24v4h24v-4z"/></svg>
			</button>
			
			<div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
			  <div class="offcanvas-header">
				<div data-componente="perfil-usuario"></div>
			  </div>
			  <div class="offcanvas-body">
				<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
				  <li class="nav-item">
					<a class="nav-link" aria-current="page" href="inicio.php">Inicio</a>
				  </li>
				  
				  <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">
						Cursos internos
					</a>
					<ul class="dropdown-menu dropdown-menu-dark">
						<div data-componente="opcion-editor-cursos"></div>
						<div data-componente="opcion-cursos-creados"></div>
						<div data-componente="opcion-lista-cursos-i"></div>
					</ul>
				  </li>
				  <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Cursos externos
					</a>
					<ul class="dropdown-menu dropdown-menu-dark">
						<div data-componente="opcion-crear-curso-e"></div>
						<div data-componente="opcion-lista-cursos-e"></div>
					</ul>
				  </li>
				  <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Asignación de cursos
					</a>
					<ul class="dropdown-menu dropdown-menu-dark">
					  <div data-componente="opcion-asignar-interno"></div>
					  <div data-componente="opcion-asignar-externo"></div>
					</ul>
				  </li>
				  <li class="nav-item dropdown me-4">
					<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Consultar
					</a>
					<ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
						<div data-componente="opcion-pendientes"></div>
						<div data-componente="opcion-aprobados"></div>
						<div data-componente="opcion-certificaciones"></div>
						<div data-componente="opcion-consultar-usuario"></div>
						<div data-componente="opcion-usuarios-CP"></div>
						<div data-componente="opcion-usuarios-cert"></div>
						<div data-componente="opcion-usuarios-aprobados"></div>
					</ul>
				  </li>
				  <li class="nav-item dropdown">
					<a class="nav-link d-flex" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
						<div class="d-flex nombre-nav">
							<h6 class="nombre-nav text-dark my-auto me-3">
								<?php if(isset($sesion)) echo $sesion->getNombre(); ?>
							</h6>
						</div>
						<svg class="cuenta" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="48px"><path fill="#262626" d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
						<div class="cuenta dropdown-toggle">Cuenta</div>
					</a>
					<ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
						<div data-componente="opcion-ver-info"></div>
						<div data-componente="opcion-gestion-usuarios"></div>
						<div data-componente="opcion-cambiar-pass"></div>
						<div data-componente="opcion-cerrar-sesion"></div>
					</ul>
					
					<li><hr class="dropdown-divider"></li>
					
					<li class="nav-item d-xl-none">
						<a class="nav-link" aria-current="page" href="inicio.php?cerrar_sesion=true">
							Cerrar sesion
						</a>
					</li>

				  </li>
				</ul>
			  </div>
			</div>
		  </div>
		</nav>
	</template>
	
	<!-- BARRA DE NAVEGACIÓN PARA USUARIOS INSTRUCTORES -->
	<template id="navbar-sesion-instructor">
		<nav class="navbar fixed-top bg-white shadow navbar-expand-xl d-flex flex-column" data-bs-theme="light">
		  <div class="container-fluid">
		  	<div data-componente="aftermarket"></div>
			<a class="navbar-brand" href="inicio.php">
				<div data-componente="logo-entidad"></div>
			</a>
			
			<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
			  <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path d="M24 6h-24v-4h24v4zm0 4h-24v4h24v-4zm0 8h-24v4h24v-4z"/></svg>
			</button>
			
			<div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
			  <div class="offcanvas-header">
				<div data-componente="perfil-usuario"></div>
			  </div>
			  <div class="offcanvas-body">
				<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
				  <li class="nav-item">
					<a class="nav-link" aria-current="page" href="inicio.php">Inicio</a>
				  </li>
				  
				  <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">
						Cursos internos
					</a>
					<ul class="dropdown-menu dropdown-menu-dark">
						<div data-componente="opcion-editor-cursos"></div>
						<div data-componente="opcion-cursos-creados"></div>
					</ul>
				  </li>
				  <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Asignación de cursos
					</a>
					<ul class="dropdown-menu dropdown-menu-dark">
					  <div data-componente="opcion-asignar-interno"></div>
					</ul>
				  </li>
				  <li class="nav-item dropdown me-4">
					<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Consultar
					</a>
					<ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
						<div data-componente="opcion-pendientes"></div>
						<div data-componente="opcion-aprobados"></div>
						<div data-componente="opcion-certificaciones"></div>
						<div data-componente="opcion-usuarios-CP"></div>
						<div data-componente="opcion-usuarios-aprobados"></div>
					</ul>
				  </li>
				  <li class="nav-item dropdown">
					<a class="nav-link d-flex" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
						<div class="d-flex nombre-nav">
							<h6 class="nombre-nav text-dark my-auto me-3">
								<?php if(isset($sesion)) echo $sesion->getNombre(); ?>
							</h6>
						</div>
						<svg class="cuenta" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="48px"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#262626" d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
						<div class="cuenta dropdown-toggle">Cuenta</div>
					</a>
					<ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
						<div data-componente="opcion-ver-info"></div>
						<div data-componente="opcion-cambiar-pass"></div>
						<div data-componente="opcion-cerrar-sesion"></div>
					</ul>
					
					<li><hr class="dropdown-divider"></li>
					
					<li class="nav-item d-xl-none">
						<a class="nav-link" aria-current="page" href="inicio.php?cerrar_sesion=true">
							Cerrar sesion
						</a>
					</li>
					
				  </li>
				  
				</ul>
			  </div>
			</div>
		  </div>
		</nav>
	</template>
	
	<!-- BARRA DE NAVEGACIÓN PARA USUARIOS EMPLEADOS -->
	<template id="navbar-sesion-empleado">
		<nav class="navbar fixed-top bg-white shadow navbar-expand-xl d-flex flex-column" data-bs-theme="light">
		  <div class="container-fluid">
		  	<div data-componente="aftermarket"></div>
			<a class="navbar-brand" href="inicio.php">
				<div data-componente="logo-entidad"></div>
			</a>
			
			<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
			  <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path d="M24 6h-24v-4h24v4zm0 4h-24v4h24v-4zm0 8h-24v4h24v-4z"/></svg>
			</button>
			
			<div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
			  <div class="offcanvas-header">
				<div data-componente="perfil-usuario"></div>
			  </div>
			  <div class="offcanvas-body">
				<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
				  <li class="nav-item">
					<a class="nav-link" aria-current="page" href="inicio.php">Inicio</a>
				  </li>
				  <li class="nav-item dropdown me-4">
					<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Consultar
					</a>
					<ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
						<div data-componente="opcion-pendientes"></div>
						<div data-componente="opcion-aprobados"></div>
						<div data-componente="opcion-certificaciones"></div>
					</ul>
				  </li>
				  <li class="nav-item dropdown">
					<a class="nav-link d-flex" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
						<div class="d-flex nombre-nav">
							<h6 class="nombre-nav text-dark my-auto me-3">
								<?php if(isset($sesion)) echo $sesion->getNombre(); ?>
							</h6>
						</div>
						<svg class="cuenta" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="48px"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#262626" d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
						<div class="cuenta dropdown-toggle">Cuenta</div>
					</a>
					<ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
						<div data-componente="opcion-ver-info"></div>
						<div data-componente="opcion-cerrar-sesion"></div>
					</ul>
					
					<li><hr class="dropdown-divider"></li>
					
					<li class="nav-item d-xl-none">
						<a class="nav-link" aria-current="page" href="inicio.php?cerrar_sesion=true">
							Cerrar sesion
						</a>
					</li>
					
				  </li>
				  
				</ul>
			  </div>
			</div>
		  </div>
		</nav>
	</template>

	<!-- BARRA DE NAVEGACIÓN PARA CURSOS ABIERTOS (NO ES REQUERIDO INICIO DE SESIÓN) -->
	 <template id="navbar-sesion-iniciar">
		<nav class="navbar fixed-top bg-white shadow navbar-expand-xl d-flex flex-column" data-bs-theme="light">
		  <div class="container-fluid">
		  	<div data-componente="aftermarket"></div>
			<a class="navbar-brand" href="inicio.php">
				<div data-componente="logo-entidad"></div>
			</a>
			
			<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
			  <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path d="M24 6h-24v-4h24v4zm0 4h-24v4h24v-4zm0 8h-24v4h24v-4z"/></svg>
			</button>
			
			<div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
			  <div class="offcanvas-header">
				<div data-componente="perfil-usuario"></div>
			  </div>
			  <div class="offcanvas-body">
				<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
					<li class="nav-item">
						<button class="d-flex my-3 py-2 btn btn-navbar" onclick="window.location.replace ('index.php')">
							<svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4"/></svg>
							<span>Iniciar Sesión</span>
						</button>
					</li>
				</ul>
			  </div>
			</div>
		  </div>
		</nav>
	</template>
	
	<!-- LOGO AFTER MARKET -->
	<template id="aftermarket">
		<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
			height="50px" viewBox="0 0 114 496"
			style="position: absolute; left: 0;">
			<g fill="#F00" transform="translate(0.000000,496.000000) scale(0.100000,-0.100000)"
			fill="#000000" stroke="none">
			<path d="M7 4954 c-4 -4 -7 -513 -7 -1132 l0 -1125 93 6 c299 19 595 175 791
			418 86 107 177 288 212 424 35 135 43 330 20 465 -73 415 -346 741 -743 883
			-104 38 -349 78 -366 61z"/>
			<path d="M0 1137 c0 -731 3 -1128 10 -1132 17 -11 209 15 298 41 103 29 247
			96 333 155 138 93 277 249 357 399 129 242 166 550 98 815 -34 134 -125 315
			-212 423 -193 241 -492 399 -791 419 l-93 6 0 -1126z"/>
			</g>
		</svg>
	</template>

	<template id="aftermarket-lg">
		<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
			height="100px" viewBox="0 0 114 496"
			style="position: absolute; left: 0; top: 50%; transform: translateY(-50%);">
			<g fill="#F00" transform="translate(0.000000,496.000000) scale(0.100000,-0.100000)"
			fill="#000000" stroke="none">
			<path d="M7 4954 c-4 -4 -7 -513 -7 -1132 l0 -1125 93 6 c299 19 595 175 791
			418 86 107 177 288 212 424 35 135 43 330 20 465 -73 415 -346 741 -743 883
			-104 38 -349 78 -366 61z"/>
			<path d="M0 1137 c0 -731 3 -1128 10 -1132 17 -11 209 15 298 41 103 29 247
			96 333 155 138 93 277 249 357 399 129 242 166 550 98 815 -34 134 -125 315
			-212 423 -193 241 -492 399 -791 419 l-93 6 0 -1126z"/>
			</g>
		</svg>
	</template>

	<!-- LOGOTIPO DE LA EMPRESA -->
	<template id="logo-entidad">
		<svg xmlns="http://www.w3.org/2000/svg" width="150" viewBox="0 0 196.881 34.857">
		  <defs><style>.entidad-logo-fill-el { fill: #ff0a07; }</style></defs>
		  <g id="entidad_logo" data-name="Group entidad logo" transform="translate(-745.009 -371.143)">
			<path id="logo-b" data-name="Path B" class="entidad-logo-fill-el" d="M757.4,371.143H745.009V406h41.639V381.009H757.4Zm16.851,16.428v11.013H757.4V387.571Z"/>
			<path id="logo-o" data-name="Path O" class="entidad-logo-fill-el" d="M791.131,406H832.77V381.009H791.131Zm12.394-18.429h16.851v11.013H803.525Z"/>
			<path id="logo-s" data-name="Path S" class="entidad-logo-fill-el" d="M837.252,395.824H866.5v2.759H837.252V406h41.639V390.331H849.646v-2.76h29.245v-6.562H837.252Z"/>
			<path id="logo-a" data-name="Path A" class="entidad-logo-fill-el" d="M883.374,387.571h29.245v2.76H883.374V406h41.639V381.009H883.374Zm29.245,11.013H895.768v-2.76h16.851Z"/>
			<rect id="logo-l" data-name="Path L" class="entidad-logo-fill-el" width="12.394" height="34.857" transform="translate(929.496 371.143)"/>
		  </g>
		</svg>
	</template>
	
	<template id="perfil-usuario">
		<svg class="cuenta" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="48px"><path fill="#262626" d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
		<h5 class="offcanvas-title" id="nombre-usuario"><?php echo isset ($sesion) ? $sesion->getNombre() : 'Inicia sesión para ver todas las opciones' ?></h5>
		<button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</template>
	
	<!-- BARRA DE NAVEGACIÓN Al VISUALIZAR UN BORRADOR -->
	<template id="barra-borrador">
		<div class="barra-borrador py-2">
			<p class="ps-5 my-auto">Se está visualizando el curso como borrador.</p>
			<div class="mx-auto"></div>
			<form action="editor.php">
				<button class="py-1 px-3 me-5">Seguir editando</button>
			</form>
		</div>
	</template>
	
	<!-- BARRA DE NAVEGACIÓN SECUNDARIA DEL EDITOR DE CURSOS -->
	<template id="barra-editor">
	</template>
	
	<!-- OPCIÓN PARA CREAR NUEVOS CURSOS (USUARIOS ADMIN E INSTRUCTOR) -->
	<template id="opcion-editor-cursos">
		<div class="accordion accordion-flush">
			<div class="accordion-item">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#opciones-editor" aria-expanded="false">
					Editor de cursos
				</button>
				<div id="opciones-editor" class="accordion-collapse collapse">
					<a href="editor.php">
						<div class="accordion-body">
							Abrir editor
						</div>
					</a>
					<a href="borradores.php">
						<div class="accordion-body">
							Mis borradores
						</div>
					</a>
				</div>
			</div>
		</div>
		<li><hr class="dropdown-divider"></li>
	</template>
	
	<!-- OPCIÓN PARA CREAR NUEVOS CURSOS INTERNOS (USUARIOS ADMIN E INSTRUCTOR) -->
	<template id="opcion-cursos-creados">
		<li><a class="dropdown-item" href="cursos_creados.php">Mis cursos creados</a></li>
	</template>
	
	<!-- OPCIÓN PARA VER TODOS LOS CURSOS INTERNOS (USUARIOS ADMIN) -->
	<template id="opcion-lista-cursos-i">
		<li><hr class="dropdown-divider"></li>
		<li><a class="dropdown-item" href="cursos_internos.php">Lista de cursos internos</a></li>
	</template>
	
	<!-- OPCIÓN PARA CREAR NUEVOS CURSOS EXTERNOS (USUARIOS ADMIN) -->
	<template id="opcion-crear-curso-e">
		<li><a class="dropdown-item" href="crear_externo.php">Crear nuevo curso externo</a></li>
		<li><hr class="dropdown-divider"></li>
	</template>
	
	<!-- OPCIÓN PARA VER TODOS LOS CURSOS EXTERNOS (USUARIOS ADMIN) -->
	<template id="opcion-lista-cursos-e">
		<li><a class="dropdown-item" href="cursos_externos.php">Cursos externos disponibles</a></li>
	</template>
	
	<!-- OPCIÓN PARA ASIGNAR CURSO INTERNO (USUARIOS ADMIN) -->
	<template id="opcion-asignar-interno">
		<li><a class="dropdown-item" href="asignar_interno.php">Asignar curso interno</a></li>
	</template>
	
	<!-- OPCIÓN PARA ASIGNAR CURSO EXTERNO (USUARIOS ADMIN) -->
	<template id="opcion-asignar-externo">
		<li><hr class="dropdown-divider"></li>
		<li><a class="dropdown-item" href="asignar_externo.php">Asignar curso externo</a></li>
	</template>
	
	<!-- OPCIÓN CONSULTAR CURSOS PENDIENTES -->
	<template id="opcion-pendientes">
		<li><a class="dropdown-item" href="cursos_pendientes.php">Mis cursos pendientes</a></li>
	</template>
	
	<!-- OPCIÓN CONSULTAR CURSOS APROBADOS -->
	<template  id="opcion-aprobados">
		<li><hr class="dropdown-divider"></li>
		<li><a class="dropdown-item" href="cursos_aprobados.php">Mis cursos aprobados</a></li>
	</template>
	
	<!-- OPCIÓN CONSULTAR CERTIFICACIONES -->
	<template id="opcion-certificaciones">
		<li><hr class="dropdown-divider"></li>
		<li><a class="dropdown-item" href="certificaciones_externas.php">Mis cursos externos</a></li>
	</template>
	
	<!-- OPCIÓN CONSULTAR USUARIOS -->
	<template id="opcion-consultar-usuario">
		<li><hr class="dropdown-divider"></li>
		<li><a class="dropdown-item" href="consultar_usuario.php">Consultar empleado</a></li>
	</template>
	
	<!-- OPCIÓN CONSULTAR USUARIOS CON CURSOS PENDIENTES -->
	<template id="opcion-usuarios-CP">
		<li><hr class="dropdown-divider"></li>
		<li><a class="dropdown-item" href="usuarios_cursos_pendientes.php">Empleados con cursos pendientes</a></li>
	</template>
	
	<!-- OPCIÓN CONSULTAR USUARIOS CERTIFICADOS -->
	<template id="opcion-usuarios-cert">
		<li><hr class="dropdown-divider"></li>
		<li><a class="dropdown-item" href="usuarios_certificados.php">Empleados con cursos externos</a></li>
	</template>
	
	<!-- OPCIÓN CONSULTAR USUARIOS APROBADOS -->
	<template id="opcion-usuarios-aprobados">
		<li><hr class="dropdown-divider"></li>
		<li><a class="dropdown-item" href="usuarios_aprobados.php">Empleados aprobados</a></li>
	</template>
	
	<!-- OPCIÓN VER INFORMACIÓN DEL PERFIL -->
	<template id="opcion-ver-info">
		<li><a class="dropdown-item" href="perfil.php">Ver mi información</a></li>
	</template>
	
	<!-- OPCIÓN PARA GESTIONAR USUARIOS (CREAR O ELIMINAR) -->
	<template id="opcion-gestion-usuarios">
		<li><hr class="dropdown-divider"></li>
		<div class="accordion accordion-flush">
			 <div class="accordion-item">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#opciones-usuario" aria-expanded="false">
					Gestión de usuarios
				</button>
				<div id="opciones-usuario" class="accordion-collapse collapse">
					<a href="nuevo_usuario.php"><div class="accordion-body">Crear nuevo usuario</div></a>
					<a href="eliminar_usuario.php"><div class="accordion-body">Eliminar usuario existente</div></a>
				</div>
			 </div>
		</div>
	</template>
	
	<!-- OPCIÓN PARA CAMBIAR CONSTRASEÑA (ADMINISTRADOR E INSTRUCTOR) -->
	<template id="opcion-cambiar-pass">
		<li><hr class="dropdown-divider"></li>
		<li><a class="dropdown-item" href="cambiar_password.php">Cambiar contraseña</a></li>
	</template>
	
	
	<!-- OPCIÓN PARA CERRAR SESIÓN -->
	<template id="opcion-cerrar-sesion">
		<li class="d-none d-lg-block"><hr class="dropdown-divider"></li>
		<li class="d-none d-lg-block">
			<a class="dropdown-item" aria-current="page" href="inicio.php?cerrar_sesion=true">
				Cerrar sesion
			</a>
		</li>
	</template>

</html>