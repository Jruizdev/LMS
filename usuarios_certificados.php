<?php 
require_once  ('modulos/GestionUsuarios.php'); 
require_once  ('modulos/CursosExternos.php'); 
require_once  ('modulos/util/paginado.php');
ValidarSesion (); 
ValidarAdmin  ($sesion->getTipoUsuario ());

// Crear paginación
$tam_pag = 	 10; // Número de registros por página
$paginado =  new Paginado ('usuarios_certificados.php', $tam_pag);

// Recuperar criterio de búsqueda, en caso de que exista
$criterio_busqueda = isset ($_GET ['buscar']) ? $_GET ['buscar'] : null;

$registros = ObtenerEmpleadosCertificados (
	$paginado->getPagActual (), 
	$tam_pag,
	urldecode ($criterio_busqueda)
);

// Configurar paginado
$paginado->setTotalRegistros ($registros ['total_uc']);
$paginado->validarPagActual  ();

// Obtener lista de usuarios certificados
$registros_certificados = $registros ['lista_uc'];
?>
<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title>Empleados con cursos externos</title>
		<link href="css/bootstrap@5.3.3/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/estilos.css" rel="stylesheet"/>
		<link href="css/font-awesome.min.css" rel="stylesheet"/>
		<script src="js/bootstrap@5.3.3/bootstrap.bundle.min.js"></script>
	</head>
	<body style="height: 100vh !important;">
		<header>
			<div data-componente = "navbar-sesion-<?php echo $sesion->getTipoUsuario()?>"></div>
		</header>
		<article style="height: 100vh;">
			<div 
			data-componente="ventana"
			data-titulo="Empleados con cursos externos"
			data-color="blue">
				<div 
				data-componente="busqueda-curso"
				data-pagina="usuarios_certificados.php"
				data-criterio="<?php echo $criterio_busqueda ?>"></div>
				<div data-tipo="contenido">
					<div 
					data-componente="tabla-usuarios-cert" 
					data-msg_placeholder="No hay registros de certificaciones" 
					style="overflow-x: auto">
						<?php
						foreach ($registros_certificados as $registro) {
							echo '
							<div 
							data-componente="usuario-cert"
							data-id_certificacion="'.$registro ['Id_certificacion'].'"
							data-fecha="'.$registro ['Fecha'].'"
							data-no_nomina="'.$registro ['No_nomina'].'"
							data-id_curso="'.$registro ['Id_curso'].'"
							data-validez="'.$registro ['Validez'].'"
							data-empleado="'.$registro ['nombre'].'"
							data-curso="'.htmlspecialchars ($registro ['Nombre']).'"></div>
							';
						}
						?>
					</div>
					<div <?php echo !isset ($registros_certificados) || count ($registros_certificados) == 0 || $sesion->getTipoUsuario () != 'admin' ? 'hidden' : '' ?>>
						<div class="mx-5 my-3 d-flex flex-column">
							<button class="btn btn-svg mx-md-auto btn-secondary" data-bs-target="#modalConfiguracion" data-bs-toggle="modal">
								Generar reporte en PDF
								<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30"><path fill="#FFF" d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 144-208 0c-35.3 0-64 28.7-64 64l0 144-48 0c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"></path></svg>
							</button>
						</div>
					</div>
				</div>
				<div 
				data-componente="footer-paginacion"
				data-max_pags="<?php echo $paginado->getMaxPags () ?>"
				data-pag_actual="<?php echo $paginado->getPagActual () ?>"
				data-pagina="usuarios_certificados.php"
				data-busqueda="<?php echo $criterio_busqueda ?>">
					<?php $paginado->crearPaginado (); ?>
				</div>
			</div>
		</article>

		<div class="modal fade" id="modalConfiguracion" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header px-4">
					<h1 class="modal-title fs-5" id="exampleModalToggleLabel">Generación de Reporte</h1>
					<button type="button" class="btn-svg btn-derecha d-flex" data-bs-dismiss="modal" aria-label="Close">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20"><path fill="#FFF" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-floating">
						<select class="form-select" id="definirRango" aria-label="Floating label select example">
							<option value="0" selected>Incluir todos los registros</option>
							<option value="1">Definir periodo</option>
						</select>
						<label for="definir-rango">Periodo de consulta:</label>
					</div>
					<div class="collapse" id="collapsePeriodo">
						<hr class="my-2 border-0">
						<div class="card card-body">
							<h6 class="mx-auto mb-3">Periodo de consulta</h6>
							<div class="d-flex">
								<span class="w-50 text-end me-2">Consultar desde:</span>
								<input id="fechaInicio" name="fechaInicio" class="form-control" type="date" min="2025-01-01" value="2025-01-01">
							</div>
							<div class="d-flex mt-3">
								<span class="mt-2 me-2 w-50 text-end">Hasta:</span>
								<input id="fechaFin" name="fechaFin" class="form-control" type="date" min="2025-01-01">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div>
						<button class="btn btn-svg btn-cerrar px-5 py-2" onclick="GenerarReporte (this, 'Empleados con Cursos Externos', 'certificados', consulta_inicio, consulta_fin)">
							<span>Continuar</span>
							<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#FFF" d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
						</button>
					</div>
				</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modalReporte" aria-hidden="true" aria-labelledby="labelModalReporte" tabindex="-1" data-bs-backdrop="static">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="labelModalReporte">Reporte de Empleados Certificados</h1>
					<button type="button" class="btn-svg btn-derecha d-flex" data-bs-dismiss="modal" aria-label="Close">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20"><path fill="#FFF" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
					</button>
				</div>
				<div class="modal-body">
					<iframe id="contenido-pdf"></iframe>
				</div>
				<div class="modal-footer">
					<div>
						<button class="btn btn-cerrar btn-svg px-5 py-2" data-bs-target="#modalReporte" data-bs-toggle="modal">
							<span>Descartar reporte</span>
							<svg class="ms-2 my-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"><path fill="#FFF" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"/></svg>
						</button>
					</div>
				</div>
				</div>
			</div>
		</div>
	</body>
	<script src="js/tiempo.js"></script>
	<script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
		const bs_collapse_periodo = new bootstrap.Collapse (
			'#collapsePeriodo', { toggle: false }
		);

		const sel_periodo = 	document.querySelector ('#definirRango');
		const consulta_inicio = document.querySelector ('#fechaInicio');
		const consulta_fin = 	document.querySelector ('#fechaFin');
		let fecha_actual =		new Date ();
		
		// Corregir fecha del servidor
		fecha_actual.setDate(fecha_actual.getDate() - 1);
		fecha_actual = fecha_actual.toISOString().split("T", 1)[0];
		
		// Definir fecha actual como máximo
		consulta_fin.value = 	fecha_actual;
		consulta_inicio.max =	fecha_actual;
		consulta_fin.max =		fecha_actual;

		sel_periodo.addEventListener ('change', () => {
			if (sel_periodo.value == 1) {
				bs_collapse_periodo.show ();
				return;
			}
			bs_collapse_periodo.hide ();
			consulta_inicio.value = '2025-01-01';
			consulta_fin.value = 	fecha_actual;
		});
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/plantillas.js"></script>
	<script src="js/reportes.js"></script>
	<script src="js/util_movil.js"></script>
	<script src="js/accesibilidad.js"></script>

</html>