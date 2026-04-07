<?php
require_once ('modulos/GestionUsuarios.php');
require_once ('modulos/util/cookiescursos.php');
require_once ('modulos/util/curso.php');
require_once ('modulos/CursosInternos.php');

$redireccionar = null;
$curso_abierto = false;

if (isset ($_GET ['curso_int']) && isset ($_GET ['visualizar'])) {
	$id_curso =   $_GET ['curso_int'];
	$visualizar = $_GET ['visualizar'];

	// Almacenar enlace
	if ($visualizar == 'true') {
		$redireccionar = base64_encode ("visualizador.php?curso_int={$id_curso}&visualizar=true");
	}
}

// Validar si se trata de un curso abierto (no es necesario iniciar sesión)
if (isset ($_GET ['tipo']) && !isset ($sesion)) {
	$tipo = trim (base64_decode ($_GET ['tipo']));
	if ($tipo == 'abierto')
		$curso_abierto = ConsultarIdCursoAbierto ($id_curso) == '1' ? true : false;
}

RecuperarSesion ();

// Evitar validación de inicio de sesión en cursos abiertos
if (!$curso_abierto) ValidarSesion ($redireccionar);

if (isset ($_POST ['curso'])) {
	
	// Recibir curso como JSON mediante método post
	$curso_json = json_decode ($_POST ['curso']);
	
	// Almacenar curso temporalmente nuevo en las cookies
	if(isset ($c_curso_interno)) {
		$id_curso = $c_curso_interno->getIdCurso ();
		$id_int = 	$c_curso_interno->getIdInt ();
		
		// Guardar borrador con ID's
		$c_curso_interno = GuardarBorradorCookies (
			$curso_json, true, $id_int, $id_curso
		);
	}
	// Guardar borrador sin ID's
	else $c_curso_interno = GuardarBorradorCookies ($curso_json);
}

function VisualizandoBorrador () {
	global $c_curso_interno;
	
	if ($c_curso_interno->getBorrador() && !isset ($_GET ['curso_int'])) return true;
	return false;
}

function BarraBorrador () {
	global $c_curso_interno;
	
	// Mostrar barra al visualizar un borrador
	if ($c_curso_interno->getBorrador () && !isset ($_GET ['curso_int'])) {
		echo '<div data-componente="barra-borrador"></div>';
	}
}

if (!isset ($c_curso_interno)) {

	// No se recibió ningún curso para mostrar
	header ('Location: inicio.php');
	exit();
}

// Consultar si el curso ya fue aprobado
$curso_aprobado = ConsultarCursoAprobado (
	$c_curso_interno->getIdCurso(),
	$c_curso_interno->getVersionActual(),
	0//$sesion->getNumNomina()
);

// Consultar si se trata de un curso secuencial (o modular)
$secuencial = ComprobarCursoSecuencial (
	$c_curso_interno->getIdCurso()
);
?>

<!DOCTYPE html>
<html lang="es">

	<head>
		<meta charset="utf-8"/>
		<title><?php if(isset($c_curso_interno)) echo $c_curso_interno->getNombre() ?></title>
		<link href="css/bootstrap@5.3.3/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/estilos.css" rel="stylesheet"/>
		<link href="css/font-awesome.min.css" rel="stylesheet"/>
		<script src="js/bootstrap@5.3.3/bootstrap.bundle.min.js"></script>
	</head>
	
	<body>
		<header>
			<div data-componente = "navbar-sesion-<?php echo isset ($sesion) ? $sesion->getTipoUsuario() : 'iniciar'//echo $sesion->getTipoUsuario()?>">
				<?php BarraBorrador ()?>
			</div>
		</header>
		
		<article class="pt-0">
			<hr class="my-5 border-0">
			<button class="scroll-elemento-top d-flex" onclick="ScrollTop ()">
				<svg class="m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40"><path fill="#fff" d="M233.4 105.4c12.5-12.5 32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L256 173.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l192-192z"/></svg>
			</button>
			<button class="scroll-elemento-bottom d-flex" onclick="ScrollElementoBottom ()">
				<svg class="m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40"><path fill="#fff" d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>
			</button>
		
			<div 
			data-componente="ventana-extendida"
			data-titulo="<?php echo $c_curso_interno->getNombre() ?>"
			data-color="dark"
			data-pos="elemento-top">
				<div data-tipo="contenido">
					<div class="personalizado mb-5">
						<div class="contenido-curso text-break"> 
							<?php isset ($sesion) ? $c_curso_interno->VerCurso ($sesion->getTipoUsuario(), $secuencial) : $c_curso_interno->VerCurso ('Empleado', $secuencial) //$c_curso_interno->VerCurso ($sesion->getTipoUsuario(), $secuencial)?>
						</div>
					</div>
				</div>
			</div>
			<div 
			data-componente="ventana"
			data-titulo="Evaluación"
			data-color="blue"
			data-pos="elemento-bottom" class="mb-5" <?php echo isset ($sesion) ? '' : 'hidden' ?>>
				<div data-tipo="contenido">
					<div class="personalizado mt-4 mb-3" <?php echo !$curso_aprobado ? 'hidden' : '' ?>>
						<div class="alert alert-primary d-flex align-items-center px-4" role="alert">
							<svg class="me-3 mb-auto mt-2" style="float: left" fill="currentColor" viewBox="0 0 16 16" width="40">
								<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
							</svg>
							<div>
								<h5>Ya has aprobado este curso.</h5>
								<p class="mb-0">Tu puntaje obtenido fue: 
								<?php echo $curso_aprobado['Puntaje'].' de '.
								$curso_aprobado['Puntaje_max'] ?>
								</p>
							</div>
						</div>
					</div>
					
					<div class="personalizado evaluacion gap-0 my-4" <?php echo ($curso_aprobado || VisualizandoBorrador ()) ? 'hidden' : '' ?>>
						<div class="alert alert-primary d-flex align-items-center px-4" role="alert">
							<svg class="me-3 mb-auto mt-2" style="float: left" fill="currentColor" viewBox="0 0 16 16" width="40">
								<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
							</svg>
							<div>
								<div class="text-break">
									<p>Antes de iniciar la evaluación, asegúrate de haber leído completamente el contenido del curso, ya que, una vez abierta la evaluación no podrás cerrarla hasta terminarla.</p>
									<p class="mb-0"><b>Nota: </b>Si la evaluación es colaborativa, asegurate de agregar en la siguiente tabla los números de nómina de las personas que participarán en la evaluación. De otra forma, no se les podrá registrar la calificación obtenida.</p>
								</div>
							</div>
						</div>

						<hr class="my-2 border-0">
						<h5>Empleados que realizarán la evaluación:</h5>
						<hr class="my-2 border-0">
						<div class="shadow">
							<table class="tabla-colaboracion table">
								<thead class="thead-light">
									<tr>
										<th scope="col" class="col-3" style="vertical-align: middle;">No. Nómina</th>
										<th scope="col">Nombre del empleado</th>
										<th scope="col" class="text-center" style="vertical-align: middle;">
											<button title="Agregar empleado" onclick="AgregarEmpleadoEvaluacion ()">
												<svg width="30" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 800 800" enable-background="new 0 0 800 800" xml:space="preserve">
													<path fill="#0000" opacity="1.000000" stroke="none" d=" M1.000000,282.000000   C1.000000,188.000000 1.000000,94.500000 1.000000,1.000000   C267.666656,1.000000 534.333313,1.000000 801.000000,1.000000   C801.000000,267.666656 801.000000,534.333313 801.000000,801.000000   C534.333313,801.000000 267.666656,801.000000 1.000000,801.000000   C1.000000,628.166687 1.000000,455.333344 1.000000,282.000000  M720.982910,629.530762   C735.127930,609.898865 747.390259,589.195312 757.455322,567.148621   C769.022095,541.812683 777.934204,515.679260 784.365967,488.545258   C789.566650,466.604553 792.690857,444.406799 793.752991,422.046722   C794.841003,399.141357 794.150330,376.175507 791.165283,353.288300   C788.859131,335.606445 785.403137,318.261261 781.155884,300.961548   C775.003845,275.903473 765.917114,251.931961 754.795044,228.817612   C745.165833,208.805771 733.707397,189.762634 720.481262,171.758789   C704.519714,150.031540 687.787048,129.049088 667.048767,111.698647   C650.960388,98.238518 634.993774,84.441849 617.698364,72.667038   C599.020325,59.950905 579.064087,49.142857 558.030396,40.367744   C529.598328,28.506050 500.593536,18.771753 470.108856,14.070771   C453.699371,11.540298 437.184570,9.158960 420.632507,8.244695   C405.613586,7.415115 390.444977,8.143259 375.406616,9.147108   C360.787811,10.122951 346.235931,12.203156 331.681030,14.010472   C309.842377,16.722229 288.881622,23.291805 268.322510,30.578201   C247.065781,38.111843 226.549118,47.587078 206.820404,58.846355   C180.638824,73.788322 156.321030,91.247459 134.281631,111.595222   C120.132767,124.658096 107.157822,139.155090 94.832451,153.986374   C81.543106,169.977615 70.055214,187.335739 59.647171,205.468781   C47.843628,226.033081 37.975880,247.395416 30.079931,269.656067   C21.562700,293.668243 15.063234,318.250977 12.001080,343.626312   C10.574627,355.446960 8.523291,367.266937 8.194410,379.127136   C7.744415,395.355011 8.259429,411.642761 9.131530,427.863403   C9.787259,440.059631 10.862840,452.343231 13.167717,464.315338   C16.722839,482.781464 20.918493,501.170807 25.888737,519.305969   C31.918213,541.305969 40.936310,562.292175 51.917763,582.240845   C60.746708,598.279419 70.229897,614.080566 80.827690,628.988281   C91.471321,643.960571 103.533287,657.974976 115.599518,671.865540   C122.195618,679.458923 129.821976,686.234009 137.457504,692.829712   C148.560516,702.420532 159.464249,712.449829 171.592575,720.595337   C188.089951,731.675293 205.275452,741.895081 222.890411,751.096802   C238.173676,759.080383 254.263107,765.654236 270.362823,771.907349   C290.183899,779.605896 310.739441,785.050415 331.844910,787.963013   C348.461578,790.256287 365.115814,793.162476 381.820801,793.699768   C401.279633,794.325562 420.883209,793.476318 440.291016,791.762085   C457.687256,790.225525 475.106110,787.557983 492.152435,783.761658   C520.085388,777.540710 547.108398,768.026123 572.513916,754.838623   C590.886841,745.301636 608.562195,734.333557 626.083252,723.266846   C643.926025,711.996948 659.858887,698.139526 674.925415,683.414368   C684.242065,674.308777 693.076538,664.659790 701.560852,654.768616   C708.303101,646.908386 714.185852,638.310791 720.982910,629.530762  z"/>
													<path fill=var(--success) opacity="1.000000" stroke="none" d=" M720.716553,629.785645   C714.185852,638.310791 708.303101,646.908386 701.560852,654.768616   C693.076538,664.659790 684.242065,674.308777 674.925415,683.414368   C659.858887,698.139526 643.926025,711.996948 626.083252,723.266846   C608.562195,734.333557 590.886841,745.301636 572.513916,754.838623   C547.108398,768.026123 520.085388,777.540710 492.152435,783.761658   C475.106110,787.557983 457.687256,790.225525 440.291016,791.762085   C420.883209,793.476318 401.279633,794.325562 381.820801,793.699768   C365.115814,793.162476 348.461578,790.256287 331.844910,787.963013   C310.739441,785.050415 290.183899,779.605896 270.362823,771.907349   C254.263107,765.654236 238.173676,759.080383 222.890411,751.096802   C205.275452,741.895081 188.089951,731.675293 171.592575,720.595337   C159.464249,712.449829 148.560516,702.420532 137.457504,692.829712   C129.821976,686.234009 122.195618,679.458923 115.599518,671.865540   C103.533287,657.974976 91.471321,643.960571 80.827690,628.988281   C70.229897,614.080566 60.746708,598.279419 51.917763,582.240845   C40.936310,562.292175 31.918213,541.305969 25.888737,519.305969   C20.918493,501.170807 16.722839,482.781464 13.167717,464.315338   C10.862840,452.343231 9.787259,440.059631 9.131530,427.863403   C8.259429,411.642761 7.744415,395.355011 8.194410,379.127136   C8.523291,367.266937 10.574627,355.446960 12.001080,343.626312   C15.063234,318.250977 21.562700,293.668243 30.079931,269.656067   C37.975880,247.395416 47.843628,226.033081 59.647171,205.468781   C70.055214,187.335739 81.543106,169.977615 94.832451,153.986374   C107.157822,139.155090 120.132767,124.658096 134.281631,111.595222   C156.321030,91.247459 180.638824,73.788322 206.820404,58.846355   C226.549118,47.587078 247.065781,38.111843 268.322510,30.578201   C288.881622,23.291805 309.842377,16.722229 331.681030,14.010472   C346.235931,12.203156 360.787811,10.122951 375.406616,9.147108   C390.444977,8.143259 405.613586,7.415115 420.632507,8.244695   C437.184570,9.158960 453.699371,11.540298 470.108856,14.070771   C500.593536,18.771753 529.598328,28.506050 558.030396,40.367744   C579.064087,49.142857 599.020325,59.950905 617.698364,72.667038   C634.993774,84.441849 650.960388,98.238518 667.048767,111.698647   C687.787048,129.049088 704.519714,150.031540 720.481262,171.758789   C733.707397,189.762634 745.165833,208.805771 754.795044,228.817612   C765.917114,251.931961 775.003845,275.903473 781.155884,300.961548   C785.403137,318.261261 788.859131,335.606445 791.165283,353.288300   C794.150330,376.175507 794.841003,399.141357 793.752991,422.046722   C792.690857,444.406799 789.566650,466.604553 784.365967,488.545258   C777.934204,515.679260 769.022095,541.812683 757.455322,567.148621   C747.390259,589.195312 735.127930,609.898865 720.716553,629.785645  M561.406250,711.970154   C600.953125,691.348694 635.998474,664.845337 664.758240,630.672424   C739.254089,542.154846 765.768799,440.845764 742.773804,327.419067   C729.102417,259.982361 696.974060,201.752426 648.088501,153.362915   C591.254761,97.105865 522.876587,63.728653 443.350433,53.755089   C398.359680,48.112682 353.887665,51.084240 310.165955,63.107075   C231.689438,84.686974 167.281952,127.662743 119.435318,193.478897   C57.813343,278.243896 37.645382,372.837097 59.238270,475.470764   C74.671516,548.826843 110.915474,610.966431 166.798737,660.921387   C227.359772,715.057922 298.442963,745.159607 379.811646,750.152527   C443.575562,754.065247 503.847565,741.273499 561.406250,711.970154  z"/>
													<path fill=var(--success) opacity="1.000000" stroke="none" d=" M561.062622,712.116089   C503.847565,741.273499 443.575562,754.065247 379.811646,750.152527   C298.442963,745.159607 227.359772,715.057922 166.798737,660.921387   C110.915474,610.966431 74.671516,548.826843 59.238270,475.470764   C37.645382,372.837097 57.813343,278.243896 119.435318,193.478897   C167.281952,127.662743 231.689438,84.686974 310.165955,63.107075   C353.887665,51.084240 398.359680,48.112682 443.350433,53.755089   C522.876587,63.728653 591.254761,97.105865 648.088501,153.362915   C696.974060,201.752426 729.102417,259.982361 742.773804,327.419067   C765.768799,440.845764 739.254089,542.154846 664.758240,630.672424   C635.998474,664.845337 600.953125,691.348694 561.062622,712.116089  M626.065002,431.692963   C636.450439,422.196930 642.590088,410.937744 641.023621,396.347717   C640.104614,387.787109 636.691162,380.390198 631.341736,373.734863   C622.101990,362.239502 609.187317,359.049469 595.553772,358.963989   C546.563416,358.656830 497.569977,358.843475 448.577698,358.843475   C446.784546,358.843475 444.991364,358.843445 442.934692,358.843445   C442.836273,356.954346 442.705994,355.500275 442.692596,354.045197   C442.208740,301.519775 441.807465,248.993439 441.187897,196.469666   C441.089417,188.122284 437.645691,180.770721 432.066895,174.513321   C423.512756,164.918655 413.208496,160.317062 399.947418,160.727264   C390.340546,161.024445 382.593872,164.174133 375.373474,169.848267   C363.944580,178.829620 360.404144,191.475296 360.323181,205.017319   C360.028320,254.341080 360.206696,303.667694 360.206696,352.993225   C360.206696,354.784760 360.206696,356.576263 360.206696,358.843445   C357.971985,358.843445 356.184174,358.843475 354.396362,358.843475   C305.570740,358.843475 256.745026,358.799835 207.919647,358.907166   C202.711731,358.918610 197.342651,359.158691 192.327301,360.410461   C171.272430,365.665466 156.864197,389.237793 162.533783,409.842529   C167.862152,429.207214 184.011734,440.951416 203.215775,441.115509   C253.704102,441.546967 304.198547,441.261353 354.690582,441.261353   C356.444427,441.261353 358.198242,441.261322 360.206696,441.261322   C360.206696,443.786224 360.206696,445.608459 360.206696,447.430695   C360.206696,496.256317 360.164551,545.081970 360.269989,593.907410   C360.280853,598.946533 360.572815,604.132935 361.782959,608.989685   C364.014191,617.944397 369.141144,625.287598 376.330505,631.156189   C383.529205,637.032532 392.210510,639.254517 401.024109,640.847290   C403.256073,641.250549 405.801788,639.401489 408.254425,639.195312   C415.933716,638.549744 421.977478,634.586182 427.712769,630.002136   C439.177460,620.838623 442.435547,607.838684 442.538452,594.275574   C442.910126,545.286133 442.684631,496.292175 442.684631,447.299927   C442.684631,445.385925 442.684662,443.471954 442.684662,441.261322   C445.247040,441.261322 447.055908,441.261353 448.864807,441.261353   C495.524109,441.261353 542.183899,441.141357 588.842407,441.338135   C601.959229,441.393494 614.624390,440.570068 626.065002,431.692963  z"/>
													<path fill="#FEFDFE" opacity="1.000000" stroke="none" d=" M625.794556,431.936951   C614.624390,440.570068 601.959229,441.393494 588.842407,441.338135   C542.183899,441.141357 495.524109,441.261353 448.864807,441.261353   C447.055908,441.261353 445.247040,441.261322 442.684662,441.261322   C442.684662,443.471954 442.684631,445.385925 442.684631,447.299927   C442.684631,496.292175 442.910126,545.286133 442.538452,594.275574   C442.435547,607.838684 439.177460,620.838623 427.712769,630.002136   C421.977478,634.586182 415.933716,638.549744 408.254425,639.195312   C405.801788,639.401489 403.256073,641.250549 401.024109,640.847290   C392.210510,639.254517 383.529205,637.032532 376.330505,631.156189   C369.141144,625.287598 364.014191,617.944397 361.782959,608.989685   C360.572815,604.132935 360.280853,598.946533 360.269989,593.907410   C360.164551,545.081970 360.206696,496.256317 360.206696,447.430695   C360.206696,445.608459 360.206696,443.786224 360.206696,441.261322   C358.198242,441.261322 356.444427,441.261353 354.690582,441.261353   C304.198547,441.261353 253.704102,441.546967 203.215775,441.115509   C184.011734,440.951416 167.862152,429.207214 162.533783,409.842529   C156.864197,389.237793 171.272430,365.665466 192.327301,360.410461   C197.342651,359.158691 202.711731,358.918610 207.919647,358.907166   C256.745026,358.799835 305.570740,358.843475 354.396362,358.843475   C356.184174,358.843475 357.971985,358.843445 360.206696,358.843445   C360.206696,356.576263 360.206696,354.784760 360.206696,352.993225   C360.206696,303.667694 360.028320,254.341080 360.323181,205.017319   C360.404144,191.475296 363.944580,178.829620 375.373474,169.848267   C382.593872,164.174133 390.340546,161.024445 399.947418,160.727264   C413.208496,160.317062 423.512756,164.918655 432.066895,174.513321   C437.645691,180.770721 441.089417,188.122284 441.187897,196.469666   C441.807465,248.993439 442.208740,301.519775 442.692596,354.045197   C442.705994,355.500275 442.836273,356.954346 442.934692,358.843445   C444.991364,358.843445 446.784546,358.843475 448.577698,358.843475   C497.569977,358.843475 546.563416,358.656830 595.553772,358.963989   C609.187317,359.049469 622.101990,362.239502 631.341736,373.734863   C636.691162,380.390198 640.104614,387.787109 641.023621,396.347717   C642.590088,410.937744 636.450439,422.196930 625.794556,431.936951  z"/>
												</svg>
											</button>
										</th>
									</tr>
								<thead>
								<tbody>
									<tr class="empleado">
										<td><input class="form-control" type="number" value="<?php echo isset ($sesion) ? $sesion->getNumNomina () : 0000//$sesion->getNumNomina () ?>" disabled></td>
										<td class="col-8" data-columna="nombre"><p class="my-auto text-start"><?php echo isset ($sesion) ? $sesion->getNombreMayus () : ''//$sesion->getNombreMayus () ?></p></td>
										<td class="text-center" style="vertical-align: middle;"></td>
									</tr>
								</tbody>
							</table>
						</div>
						<hr class="my-2 border-0">
						<div class="d-flex flex-row w-100" style="min-width: 100% !important;">
							<button class="ms-auto flex-grow-1 flex-lg-grow-0 mx-0 my-3 py-2 btn-success" data-bs-toggle="modal" data-bs-target="#modal-inicio">
								Iniciar evaluación
								<svg class="ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="30"><path fill="#FFF" d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
							</button>
						</div>
					</div>
					
					
					<div class="personalizado evaluacion gap-0 my-4" <?php echo !VisualizandoBorrador () ? 'hidden' : ''?>>
						<div class="alert alert-warning d-flex align-items-center px-4" role="alert">
							<svg class="me-3 mb-auto mt-2" style="float: left" fill="currentColor" viewBox="0 0 16 16" width="40">
								<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
							</svg>
							<div>
								<div class="text-break"><b>Te encuentras en el modo de previsualización del curso.</b><br>Ten en cuenta que cualquier interacción que realices, incluyendo respuestas en la evaluación final, no será registrada ni tendrá impacto en los resultados. Este modo es solo para fines de revisión del contenido.</div>
							</div>
						</div>
						<div class="d-flex flex-row w-100" style="min-width: 100% !important;">
							<button class="ms-auto flex-grow-1 flex-lg-grow-0 mx-0 my-3 py-2 btn-success" data-bs-toggle="modal" data-bs-target="#<?php echo VisualizandoBorrador() ? 'modal-evaluacion' : 'modal-inicio'?>">
								Iniciar evaluación
								<svg class="ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="30"><path fill="#FFF" d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
							</button>
						</div>
					</div>
					
				</div>
			</div>
			
		</article>
		
		<div id="modal-inicio" class="modal fade confirmacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Iniciar evaluación</h1>
					</div>
					<div class="modal-body">
						<div class="d-flex">
							<svg class="me-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="60"><path fill="#AAA" d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm169.8-90.7c7.9-22.3 29.1-37.3 52.8-37.3l58.3 0c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24l0-13.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1l-58.3 0c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg>
							<span>
							<p class="mb-0">Estás a punto de iniciar la evaluación. Una vez iniciada, no podrás cerrarla hasta haberla completado.</p>
							<p class="mb-0 mt-3"><b>¿Deseas continuar?</b></p>
							</span>
						</div>
					</div>
					<div class="modal-footer">
						<div class="d-flex flex-row w-100">
							<button class="btn-secundario w-100 mx-2" data-bs-target="#modal-inicio" data-bs-toggle="modal">
								Cancelar
							</button>
							<button class="btn-primario w-100 mx-2" data-bs-target="#modal-evaluacion" data-bs-toggle="modal">
								Continuar
								<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="15"><path fill="#fff" d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div id="modal-evaluacion" class="modal evaluacion fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
				  <div class="modal-header d-flex justify-content-between">
					<h5 class="modal-title">Evaluación iniciada</h5>
					<h6 class="modal-title">Página:</h6>
				  </div>
				  <div class="modal-body fade-scroll">
					<?php $preguntas = json_encode ($c_curso_interno->getNuevaEvaluacion ()); ?>
				  </div>
				  <div class="modal-footer py-4">
					<div class="indicadores-nav d-flex w-75 justify-content-center"></div>
					<div class="nav-evaluacion d-flex w-100">
						<div class="nav-btns visible d-flex flex-grow-1 flex-lg-grow-0 mx-auto flex-row">
							<button class="px-5 d-flex btn-secundario" onclick="PagAntEvaluacion ()">
								<div class="d-flex mx-auto" style="min-width: 180px;">
									<svg class="ms-auto me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="15"><path fill="#fff" d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
									<span class="me-auto">Anterior</span>
								</div>
							</button>
							<button class="px-5 d-flex" onclick="PagSigEvaluacion ()">
								<div class="d-flex mx-auto" style="min-width: 180px;">
									<span class="ms-auto">Siguiente</span>
									<svg class="me-auto ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="15"><path fill="#fff" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
								</div>
							</button>
						</div>
						<div class="d-flex ultima-pag flex-grow-1 flex-lg-grow-0 mx-auto">
							<button class="btn-secundario px-5 d-flex w-100" onclick="PagAntEvaluacion ()">
								<div class="d-flex mx-auto" style="min-width: 180px;">
									<svg class="ms-auto me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="15"><path fill="#fff" d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
									<span class="me-auto">Anterior<span>
								</div>
							</button>
							<button class="btn-terminar px-5 d-flex w-100" data-bs-target="#modal-confirmacion" data-bs-toggle="modal">
								<div class="d-flex m-auto" style="white-space: nowrap; min-width: 180px;">
									<span class="ms-auto">Terminar evaluación</span>
									<svg class="me-auto ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#fff" d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L369 209z"/></svg>
								</div>
							</button>
						</div>
					</div>
				  </div>
				</div>
			</div>
		</div>
		
		<div id="modal-confirmacion" class="modal fade confirmacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
			<div class="modal-dialog modal-dialog-centered" <?php echo VisualizandoBorrador () ? 'hidden' : ''?>>
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Terminar evaluación</h1>
					</div>
					<div class="modal-body">
						<div class="d-flex">
							<svg class="me-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="60"><path fill="#AAA" d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm169.8-90.7c7.9-22.3 29.1-37.3 52.8-37.3l58.3 0c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24l0-13.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1l-58.3 0c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg>
							Se enviarán tus respuestas para evaluación. ¿Deseas continuar?
						</div>
					</div>
					<div class="modal-footer">
						<div class="d-flex flex-row w-100">
							<button class="btn-secundario w-100 mx-2" data-bs-target="#modal-evaluacion" data-bs-toggle="modal">
								<svg class="me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#fff" d="M48.5 224L40 224c-13.3 0-24-10.7-24-24L16 72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8L48.5 224z"/></svg>
								Regresar
							</button>
							<button class="btn-primario w-100 mx-2" onclick="EnviarEvaluacion (
								<?php echo $c_curso_interno->getIdCurso() ?>, 
								<?php echo $c_curso_interno->getVersionActual() ?>,
								<?php echo isset ($sesion) ? $sesion->getNumNomina() : 0000 ?>)">
								Continuar
								<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="15"><path fill="#fff" d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg>
							</button>
						</div>
					</div>
				</div>
			</div>
			
			<div class="modal-dialog modal-dialog-centered" <?php echo !VisualizandoBorrador () ? 'hidden' : ''?>>
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Terminar evaluación</h1>
					</div>
					<div class="modal-body">
						<div class="d-flex">
							<svg class="me-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="60"><path fill="#AAA" d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm169.8-90.7c7.9-22.3 29.1-37.3 52.8-37.3l58.3 0c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24l0-13.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1l-58.3 0c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg>
							<span class="my-auto">Se cerrará la evaluación. ¿Deseas continuar?</span>
						</div>
					</div>
					<div class="modal-footer">
						<div class="d-flex flex-row w-100">
							<button class="btn-secundario w-100 mx-2" data-bs-target="#modal-evaluacion" data-bs-toggle="modal">
								<svg class="me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20"><path fill="#fff" d="M48.5 224L40 224c-13.3 0-24-10.7-24-24L16 72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8L48.5 224z"/></svg>
								Regresar
							</button>
							<button class="btn-primario w-100 mx-2" data-bs-target="#modal-confirmacion" data-bs-toggle="modal">
								Continuar
								<svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="15"><path fill="#fff" d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</body>
	
	<script id="tmp-preguntas">
		var id_curso = 			<?php echo isset($_GET ['curso_int']) ? $_GET ['curso_int'] : 'null' ?>;
		var no_nomina = 		<?php echo isset ($sesion) ? $sesion->getNumNomina() : 0000//$sesion->getNumNomina() ?>;
		var curso_abierto =		<?php echo $curso_abierto == '1' ? 'true' : 'false' ?>;
		var version = 			<?php echo 
									($c_curso_interno->getVersionActual() != 'N/A') ? 
									$c_curso_interno->getVersionActual() : 'null' 
								?>;
		var evaluacion = 		<?php echo $preguntas ?>;
		var puntaje_total = 	<?php echo $c_curso_interno->getPuntajeTotal() ?>;
		var puntaje_min = 		<?php echo $c_curso_interno->getPuntajeMin() ?>;
		var previsualizacion = 	<?php echo VisualizandoBorrador() ? 'true' : 'false' ?>;
	</script>
	<script>
		var mensaje = {
			tipo: null, texto: null, accion: null
		};
	</script>
	<script src="js/mensajes.js"></script>
	<script src="js/plantillas.js"></script>
	<script src="js/scroll.js"></script>
	<script src="js/paginacion.js"></script>
	<script src="js/evaluacion.js"></script>
	<script src="js/historial_versiones.js"></script>
	<script src="js/evaluacion_colab.js"></script>
	<script>
		const modal_evaluacion = 	document.querySelector ('#modal-evaluacion');

		// Iniciar evaluación
		modal_evaluacion.addEventListener ('shown.bs.modal', async (e) => {
			ConfigurarFadeScroll ();
			
			// No registrar intento en modo "previsualización"
			if (previsualizacion) return;

			const lista_colaboradores = ObtenerColaboradores ();
			const formData = new FormData ();
			formData.append ('registrar_intento', 'true');
			formData.append ('id_curso', id_curso);
			formData.append ('no_nomina', no_nomina);
			formData.append ('colaboradores', lista_colaboradores ['nominas'].join (','));
			formData.append ('version', version);
			
			// Registrar nuvo intento de evaluación en la BD
			await fetch ('modulos/GestionUsuarios.php', {
				method: 'POST',
				body: formData
			})
			.then ((resp) => resp.text())
			.then ((res) => {
				if (res === 'true') console.log ('Se registró un nuevo intento');
				else console.error ('Intento no registrado');
			});
		});
	</script>

</html>