<?php 
require_once ('modulos/CursosInternos.php');
require_once ('modulos/GestionUsuarios.php'); 
require_once ('modulos/util/paginado.php');
ValidarSesion (); 
ValidarSesionInstAdmin ($sesion->getTipoUsuario ());

// Crear paginación
$tam_pag = 	 10; // Número de registros por página
$paginado =  new Paginado ('usuarios_aprobados.php', $tam_pag);

// Recuperar criterio de búsqueda, en caso de que exista
$criterio_busqueda = isset ($_GET ['buscar']) ? $_GET ['buscar'] : null;

$num_nomina = ($sesion->getTipoUsuario() != 'admin') ? 
			   $sesion->getNumNomina() : 
			   null;

$registros = ObtenerEmpleadosAprobados (
	$num_nomina, 
	$paginado->getPagActual (), 
	$tam_pag,
	urldecode ($criterio_busqueda)
);

// Configurar paginado
$paginado->setTotalRegistros ($registros ['total_ua']);
$paginado->validarPagActual  ();

// Obtener lista de usuarios aprobados
$usuarios_aprobados = $registros ['lista_ua'];

// Determinar si exite al menos una calificación colaborativa en los cursos aprobados
$hay_colaborativa = false;
foreach ($usuarios_aprobados as $curso_aprobado) {
	if ($curso_aprobado ['Colaborativo']) {
		$hay_colaborativa = true;
		break;
	}
}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8"/>
		<title>Usuarios aprobados</title>
		<link href="css/bootstrap@5.3.3/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/estilos.css" rel="stylesheet"/>
		<link href="css/font-awesome.min.css" rel="stylesheet"/>
		<script src="js/bootstrap@5.3.3/bootstrap.bundle.min.js"></script>
	</head>
	<body style="height: 100vh !important;">
		<header>
			<div data-componente = "navbar-sesion-<?php echo $sesion->getTipoUsuario()?>"></div>
		</header>
		<article>
			<hr class="my-4 my-sm-0 border-0">
			<div class="alerta" <?php echo $sesion->getTipoUsuario () == 'admin' ? 'hidden' : '' ?>>
				<div class="alert alert-primary w-100 d-flex align-items-center px-4" role="alert" style="animation: add-btn-in 1s">
					<svg class="me-3" style="float: left" fill="currentColor" viewBox="0 0 16 16" width="40">
						<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
					</svg>
					<div>
						<div class="text-break">Aquí puedes consultar el registro de los empleados aprobados, en los cursos creados por tí.</div>
					</div>
				</div>
			</div>

			<div 
			data-componente="ventana"
			data-titulo="Empleados aprobados"
			data-color="green">
				<small data-componente="nota-tabla" class="py-2 px-5 aprobado" <?php echo $hay_colaborativa ? '' : 'hidden'; ?>>Una <b>C</b> al final de la calificación indicará que la calificación fué colaborativa.</small>
				<div 
				data-componente="busqueda-curso"
				data-pagina="usuarios_aprobados.php"
				data-criterio="<?php echo $criterio_busqueda ?>"></div>
				<div data-tipo="contenido">
					<div 
					data-componente="tabla-usuarios-apr"
					data-msg_placeholder="No hay registros de usuarios aprobados"
					style="overflow-x: auto">
						<?php 
						foreach ($usuarios_aprobados as $registro) {
							echo '
							<div 
							data-componente="usuario-apr"
							data-id_aprobado="'.$registro ['Id_aprobado'].'"
							data-id_curso="'.$registro ['Id_curso'].'"
							data-empleado="'.$registro ['nombre'].'"
							data-curso="'.htmlspecialchars ($registro ['Nombre']).'"
							data-version="'.$registro ['No_version'].'"
							data-aprobado="'.$registro ['Aprobado'].'"
							data-asignado="'.$registro ['Asignado'].'"
							data-intentos="'.$registro ['Intentos'].'"
							data-puntaje="'.$registro ['Puntaje'].'"
							data-puntaje_max="'.$registro ['Puntaje_max'].'"
							data-colaborativo="'.$registro ['Colaborativo'].'"></div>
							';
						}
						?>
					</div>

					<div <?php echo !isset ($usuarios_aprobados) || count ($usuarios_aprobados) == 0 || $sesion->getTipoUsuario () != 'admin' ? 'hidden' : '' ?>>
						<div class="mx-5 my-3 d-flex flex-column">
							<button class="btn btn-svg mx-md-auto btn-secondary" data-bs-target="#modalConfiguracion" data-bs-toggle="modal">
								<span>Generar reporte en PDF</span>
								<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30"><path fill="#FFF" d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 144-208 0c-35.3 0-64 28.7-64 64l0 144-48 0c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"/></svg>
							</button>
						</div>
					</div>
				</div>
				<div 
				data-componente="footer-paginacion"
				data-max_pags="<?php echo $paginado->getMaxPags () ?>"
				data-pag_actual="<?php echo $paginado->getPagActual () ?>"
				data-pagina="usuarios_aprobados.php"
				data-busqueda="<?php echo $criterio_busqueda ?>">
					<?php $paginado->crearPaginado (); ?>
				</div>
			</div>
			<hr class="my-5 border-0">
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
								<input id="fechaInicio" name="fechaInicio" class="form-control" type="date" min="2025-01-01" max="" value="2025-01-01">
							</div>
							<div class="d-flex mt-3">
								<span class="mt-2 me-2 w-50 text-end">Hasta:</span>
								<input id="fechaFin" name="fechaFin" class="form-control" type="date" min="2025-01-01" max="">
							</div>
						</div>
					</div>
					<div <?php echo isset ($criterio_busqueda) ? '' : 'hidden'; ?>>
						<hr class="my-2 border-0">
						<small style="background-color: #EEE; padding: 5px 10px;"><b>Nota: </b>El filtro de búsqueda aplicará al reporte.</small>
					</div>
				</div>
				<div class="modal-footer">
					<div>
						<button class="btn btn-svg btn-cerrar px-5 py-2" onclick="GenerarReporte (this, 'Reporte de Empleados Aprobados', 'aprobados', consulta_inicio, consulta_fin, <?php echo $num_nomina ?>)">
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
					<h1 class="modal-title fs-5" id="labelModalReporte">Reporte de Empleados Aprobados</h1>
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
	<script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/tiempo.js"></script>
	<script>
		const bs_collapse_periodo = new bootstrap.Collapse (
			'#collapsePeriodo', { toggle: false }
		);

		const sel_periodo = 	document.querySelector ('#definirRango');
		const consulta_inicio = document.querySelector ('#fechaInicio');
		const consulta_fin = 	document.querySelector ('#fechaFin');
		let fecha_actual =		new Date ();
		
		// Corregir fecha del servidor
		fecha_actual.setDate(fecha_actual.getDate());
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
	<script src="js/plantillas.js"></script>
	<script src="js/reportes.js"></script>
	<script src="js/util_movil.js"></script>
	<script src="js/accesibilidad.js"></script>
	
</html>