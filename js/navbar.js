// Plantillas de las barras de navegación
const $navbar = 			 	  document.querySelector ('div[data-componente="navbar"]');
const $navbar_capacitaciones =	  document.querySelector ('div[data-componente="navbar-capacitaciones"]');
const $navbar_sesion_admin = 	  document.querySelector ('div[data-componente="navbar-sesion-admin"]');
const $navbar_sesion_instructor = document.querySelector ('div[data-componente="navbar-sesion-instructor"]');
const $navbar_sesion_empleado =   document.querySelector ('div[data-componente="navbar-sesion-empleado"]');
const $navbar_sesion_iniciar =    document.querySelector ('div[data-componente="navbar-sesion-iniciar"]');

CargarNavbar ();

async function CargarNavbar () {
	
	const $plantilla_navbar = await CargarPlantillaNavbar ('plantillas/navbar.php');
	
	// Plantillas de las barras de navegación
	const $temp_navbar = 			  		$plantilla_navbar.querySelector ('#navbar');
	const $temp_navbar_capacitaciones = 	$plantilla_navbar.querySelector ('#navbar-capacitaciones');
	const $temp_navbar_sesion_admin = 		$plantilla_navbar.querySelector ('#navbar-sesion-admin');
	const $temp_navbar_sesion_instructor = 	$plantilla_navbar.querySelector ('#navbar-sesion-instructor');
	const $temp_navbar_sesion_empleado = 	$plantilla_navbar.querySelector ('#navbar-sesion-empleado');
	const $temp_navbar_sesion_iniciar = 	$plantilla_navbar.querySelector ('#navbar-sesion-iniciar');

	if ($navbar) 			  		{ AgregarNavbar ($navbar, $temp_navbar); }
	if ($navbar_capacitaciones) 	{ AgregarNavbar ($navbar_capacitaciones, $temp_navbar_capacitaciones); }
	if ($navbar_sesion_admin) 		{ AgregarNavbar ($navbar_sesion_admin, $temp_navbar_sesion_admin); }
	if ($navbar_sesion_instructor) 	{ AgregarNavbar ($navbar_sesion_instructor, $temp_navbar_sesion_instructor); }
	if ($navbar_sesion_empleado) 	{ AgregarNavbar ($navbar_sesion_empleado, $temp_navbar_sesion_empleado); }
	if ($navbar_sesion_iniciar) 	{ AgregarNavbar ($navbar_sesion_iniciar, $temp_navbar_sesion_iniciar); }

	// Cargar lista de opciones de la barra de navegación
	CargarOpciones ($plantilla_navbar);
	
	// Cargar complementos de la barra de navegación (en caso de que existan)
	CargarComplementos ($plantilla_navbar);
}

function CargarComplementos ($plantilla) {
	
	// Obtener complementos agregados
	const $barra_editor = document.querySelector ('div[data-componente="barra-editor"]');
	const $barra_borrador = document.querySelector ('div[data-componente="barra-borrador"]');
	
	// Cargar plantillas de los complementos
	const $temp_barra_editor = 		$plantilla.querySelector ('#barra-editor');
	const $temp_barra_borrador = 	$plantilla.querySelector ('#barra-borrador');
	
	const complementos = [
		{complemento: $barra_editor, plantilla: $temp_barra_editor},
		{complemento: $barra_borrador, plantilla: $temp_barra_borrador}
	];
	
	const $nbar = document.querySelector ('nav');
	
	// Agregar complementos
	complementos.forEach (({complemento, plantilla}) => {
		if (complemento) {
			const elemento = plantilla.content.cloneNode (true);
			$nbar.appendChild (elemento);
			complemento.remove();
		}
	});
}

function CargarOpciones ($plantilla_navbar) {
	
	const $temp_logo_entidad =			    $plantilla_navbar.querySelector ('#logo-entidad');
	const $temp_logo_aftermarket =			$plantilla_navbar.querySelector ('#aftermarket');
	const $temp_logo_aftermarket_lg =		$plantilla_navbar.querySelector ('#aftermarket-lg');
	const $temp_perfil_usuario = 		    $plantilla_navbar.querySelector ('#perfil-usuario');
	const $temp_opcion_editor =			    $plantilla_navbar.querySelector ('#opcion-editor-cursos');
	const $temp_opcion_cursos_creados =     $plantilla_navbar.querySelector ('#opcion-cursos-creados');
	const $temp_opcion_lista_cursos_i =     $plantilla_navbar.querySelector ('#opcion-lista-cursos-i');
	const $temp_opcion_crear_curso_e =	    $plantilla_navbar.querySelector ('#opcion-crear-curso-e');
	const $temp_opcion_lista_cursos_e =     $plantilla_navbar.querySelector ('#opcion-lista-cursos-e');
	const $temp_opcion_asignar_interno =    $plantilla_navbar.querySelector ('#opcion-asignar-interno');
	const $temp_opcion_asignar_externo =    $plantilla_navbar.querySelector ('#opcion-asignar-externo');
	const $temp_opcion_pendientes =		    $plantilla_navbar.querySelector ('#opcion-pendientes');
	const $temp_opcion_aprobados =		    $plantilla_navbar.querySelector ('#opcion-aprobados');
	const $temp_opcion_certificaciones =    $plantilla_navbar.querySelector ('#opcion-certificaciones');
	const $temp_opcion_consultar_usuario =  $plantilla_navbar.querySelector ('#opcion-consultar-usuario');
	const $temp_opcion_usuarios_CP =	    $plantilla_navbar.querySelector ('#opcion-usuarios-CP');
	const $temp_opcion_usuarios_cert =	    $plantilla_navbar.querySelector ('#opcion-usuarios-cert');
	const $temp_opcion_usuarios_aprobados = $plantilla_navbar.querySelector ('#opcion-usuarios-aprobados');
	const $temp_opcion_ver_info =			$plantilla_navbar.querySelector ('#opcion-ver-info');
	const $temp_opcion_gestion_usuarios =	$plantilla_navbar.querySelector ('#opcion-gestion-usuarios');
	const $temp_opcion_cambiar_pass = 		$plantilla_navbar.querySelector ('#opcion-cambiar-pass');
	const $temp_opcion_cerrar_sesion =		$plantilla_navbar.querySelector ('#opcion-cerrar-sesion');
	
	const $snav = document.querySelector ('nav');

	if (!$snav) return;
	
	const $logo_entidad =				   $snav.querySelector ('div[data-componente="logo-entidad"]');
	const $logo_aftermarket =		   $snav.querySelector ('div[data-componente="aftermarket"]');
	const $logo_aftermarket_lg =	   $snav.querySelector ('div[data-componente="aftermarket-lg"]');
	const $perfil_usuario =	 		   $snav.querySelector ('div[data-componente="perfil-usuario"]');
	const $opcion_editor_cursos = 	   $snav.querySelector ('div[data-componente="opcion-editor-cursos"]');
	const $opcion_cursos_creados = 	   $snav.querySelector ('div[data-componente="opcion-cursos-creados"]');
	const $opcion_lista_cursos_i = 	   $snav.querySelector ('div[data-componente="opcion-lista-cursos-i"]');
	const $opcion_crear_curso_e =	   $snav.querySelector ('div[data-componente="opcion-crear-curso-e"]');
	const $opcion_lista_cursos_e =	   $snav.querySelector ('div[data-componente="opcion-lista-cursos-e"]');
	const $opcion_asignar_interno =	   $snav.querySelector ('div[data-componente="opcion-asignar-interno"]');
	const $opcion_asignar_externo =	   $snav.querySelector ('div[data-componente="opcion-asignar-externo"]');
	const $opcion_pendientes =		   $snav.querySelector ('div[data-componente="opcion-pendientes"]');
	const $opcion_aprobados =		   $snav.querySelector ('div[data-componente="opcion-aprobados"]');
	const $opcion_certificaiones =	   $snav.querySelector ('div[data-componente="opcion-certificaciones"]');
	const $opcion_consultar_usuario =  $snav.querySelector ('div[data-componente="opcion-consultar-usuario"]');
	const $opcion_usuarios_CP = 	   $snav.querySelector ('div[data-componente="opcion-usuarios-CP"]');
	const $opcion_usuarios_cert =	   $snav.querySelector ('div[data-componente="opcion-usuarios-cert"]');
	const $opcion_usuarios_aprobados = $snav.querySelector ('div[data-componente="opcion-usuarios-aprobados"]');
	const $opcion_ver_info =		   $snav.querySelector ('div[data-componente="opcion-ver-info"]');
	const $opcion_gestion_usuarios =   $snav.querySelector ('div[data-componente="opcion-gestion-usuarios"]');
	const $opcion_cambiar_pass =	   $snav.querySelector ('div[data-componente="opcion-cambiar-pass"]');
	const $opcion_cerrar_sesion =	   $snav.querySelector ('div[data-componente="opcion-cerrar-sesion"]');

	const opciones = [
		{opcion: $logo_aftermarket, 		 plantilla: $temp_logo_aftermarket},
		{opcion: $logo_aftermarket_lg, 		 plantilla: $temp_logo_aftermarket_lg},
		{opcion: $logo_entidad, 			  	 plantilla: $temp_logo_entidad},
		{opcion: $perfil_usuario, 		  	 plantilla: $temp_perfil_usuario},
		{opcion: $opcion_editor_cursos,   	 plantilla: $temp_opcion_editor},
		{opcion: $opcion_cursos_creados,  	 plantilla: $temp_opcion_cursos_creados},
		{opcion: $opcion_lista_cursos_i,  	 plantilla: $temp_opcion_lista_cursos_i},
		{opcion: $opcion_crear_curso_e,	  	 plantilla: $temp_opcion_crear_curso_e},
		{opcion: $opcion_lista_cursos_e,  	 plantilla: $temp_opcion_lista_cursos_e},
		{opcion: $opcion_asignar_interno, 	 plantilla: $temp_opcion_asignar_interno},
		{opcion: $opcion_asignar_externo, 	 plantilla: $temp_opcion_asignar_externo},
		{opcion: $opcion_pendientes, 	  	 plantilla: $temp_opcion_pendientes},
		{opcion: $opcion_aprobados, 	  	 plantilla: $temp_opcion_aprobados},
		{opcion: $opcion_certificaiones,  	 plantilla: $temp_opcion_certificaciones},
		{opcion: $opcion_consultar_usuario,  plantilla: $temp_opcion_consultar_usuario},
		{opcion: $opcion_usuarios_CP,  		 plantilla: $temp_opcion_usuarios_CP},
		{opcion: $opcion_usuarios_cert, 	 plantilla: $temp_opcion_usuarios_cert},
		{opcion: $opcion_usuarios_aprobados, plantilla: $temp_opcion_usuarios_aprobados},
		{opcion: $opcion_ver_info, 			 plantilla: $temp_opcion_ver_info},
		{opcion: $opcion_gestion_usuarios, 	 plantilla: $temp_opcion_gestion_usuarios},
		{opcion: $opcion_cambiar_pass, 		 plantilla: $temp_opcion_cambiar_pass},
		{opcion: $opcion_cerrar_sesion, 	 plantilla: $temp_opcion_cerrar_sesion}
	];
	
	opciones.forEach (({opcion, plantilla}) => {
		if (opcion) AgregarOpcion (opcion, plantilla);
	});
}

async function CargarPlantillaNavbar (ruta) {
	let respuesta = await fetch (ruta);
	let contenido = respuesta.text ();
	
	// Recuperar contenido de la plantilla desde los recursos del proyecto
	let html = new DOMParser ().parseFromString (await contenido, 'text/html');
	return html.querySelector ('head');
}

function AgregarOpcion ($opcion, $plantilla) {
	const $contenido = $plantilla.content.cloneNode (true);
	$opcion.replaceWith ($contenido);
}

function AgregarNavbar ($elemento, $plantilla) {
	// Agregar barra de navegación al elemento
	const $contenido = $plantilla.content.cloneNode (true);
	$elemento.appendChild ($contenido);
}