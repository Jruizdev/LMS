<?php
require_once ('cookiescursos.php');

$directorio_video = '../../uploads/video/';
$directorio_img = 	'../../uploads/img/';
$directorio_fotos = '../../uploads/fotos/';

if (!file_exists ($directorio_video)) {
    mkdir ($directorio_video, 0777, true);
}

if (!file_exists ($directorio_img)) {
    mkdir ($directorio_img, 0777, true);
}

if (!file_exists ($directorio_fotos)) {
    mkdir ($directorio_fotos, 0777, true);
}

if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
	if (!isset ($_POST ['accion'])) { echo "No se especificó la acción"; exit; }
	
	switch ($_POST ['accion']) {
		case 'verificar_src': 	 	VerificarRecurso (); 		   break;
		case 'subir_video': 	 	SubirVideo (); 	  		   	   break;
		case 'subir_imagen': 	 	SubirImagen (); 	  		   break;
		case 'eliminar_recurso': 	EliminarRecurso ();  		   break;
		case 'aplicar_cambios':  	AplicarCambios ();  		   break;
		case 'limpiar_cambios':  	LimpiarCambiosNoGuardados ();  break;
		case 'eliminar_de_cookie': 	EliminarDeCookie (); 		   break;
		case 'obtener_foto_perfil': ObtenerFotoPerfil ();		   break;
	}
}

function ObtenerFotoPerfil () {
	global $directorio_fotos;
	
	if (!isset ($_POST ['no_nomina'])) exit;
	
	// Obtener número de nómina
	$no_nomina = $_POST ['no_nomina'];
	$archivos =	 scandir ($directorio_fotos);

	// Variable para almacenar el nombre del archivo encontrado
	$archivo_encontrado = null;
	
	foreach ($archivos as $archivo) {
		if (is_file ($directorio_fotos . DIRECTORY_SEPARATOR . $archivo)) {

			// Buscar el número de nómina al inicio del nombre del archivo
			if ($no_nomina == explode('.', $archivo)[0]) {
				$archivo_encontrado = $archivo;
				break;
			}
		}
	}
	// Se encontró el archivo (foto)
	if ($archivo_encontrado) echo $archivo_encontrado;
}

function AplicarCambios () {
	EliminarCookie ('cambios_multimedia');
}

function EliminarDeCookie () {
	if (!isset ($_POST['src'])) return;
	
	$src = filter_input (INPUT_POST, 'src', FILTER_SANITIZE_STRING);
	
	$cambios =  isset ($_COOKIE['cambios_multimedia']) ? 
				json_decode ($_COOKIE['cambios_multimedia']) : 
				array();
	
	$nuevos_cambios = array_values (
		array_diff ($cambios, [$src])
	);
	
	if (count($nuevos_cambios) == 0) AplicarCambios ();
	else setcookie ('cambios_multimedia', json_encode ($nuevos_cambios));
	echo var_dump($cambios) . ", " . $src; exit;
}

function VerificarRecurso () {

	// Retornar si recurso se encuentra disponible en el servidor
	$src = filter_input (INPUT_POST, 'src', FILTER_SANITIZE_STRING);
	echo file_exists ($src); exit;
}

function EliminarRecurso ($url = null, $mostrar_msj = true) {
	$src = $url == null ? filter_input (INPUT_POST, 'src', FILTER_SANITIZE_STRING) : $url;
	
	// Eliminar recurso del servidor
	if (file_exists ($src) && unlink ($src)) { 
		if ($mostrar_msj) { echo 'Recurso eliminado'; exit; }
	} else { 
		if ($mostrar_msj) { echo 'Recurso no eliminado: '.$src; exit; } 
	}
}

function SubirVideo () {
	global $directorio_video;
	
	$chunk_index = 		$_POST ['chunk_index'];
    $total_chunks = 	$_POST ['total_chunks'];
	$timestamp = 		$_POST ['timestamp'];
    $nombre_archivo = 	$_POST ['nombre_archivo'];

    $chunk_file = 	$_FILES['archivo']['tmp_name'];
    $destino = 		$directorio_video . $timestamp . $nombre_archivo . '.parte' . $chunk_index;

    if (move_uploaded_file ($chunk_file, $destino)) {
		
        // Verificar si todos los chunks han sido subidos
        if ($chunk_index == $total_chunks - 1) {
			
            // Combinar los chunks
            $directorio_final = $directorio_video . $timestamp . $nombre_archivo;
			
            if ($fpOut = fopen ($directorio_final, 'wb')) {
                for ($i = 0; $i < $total_chunks; $i++) {
					
                    $directorio_chunk = $directorio_video . $timestamp . $nombre_archivo . '.parte' . $i;
                    
					if ($fpIn = fopen ($directorio_chunk, 'rb')) {
                        while ($buffer = fread ($fpIn, 4096)) {
                            fwrite ($fpOut, $buffer);
                        }
                        fclose ($fpIn);
                        unlink ($directorio_chunk); // Eliminar el chunk una vez combinado
                    } 
					else { echo "Error al abrir el chunk $i"; exit; }
                }
                fclose ($fpOut);
				$nuevo_recurso = '../../uploads/video/' . $timestamp . $nombre_archivo;
				
				// Almacenar cambios en cookies (en caso de que se requiera revertir)
				GuardarCambiosRecurso ($nuevo_recurso);
				
				echo $nuevo_recurso;
            } else {
                echo 'Error al crear el archivo final.'; exit;
            }
        } else { echo "Chunk" . $chunk_index . "subido con éxito."; }
    } 	  else { echo 'Error al subir el chunk.'; }
}

function SubirImagen () {
	global $directorio_img;

	$ruta_almacenamiento = isset ($_POST ['directorio']) ? $_POST ['directorio'] : $directorio_img;

	$ancho_maximo =   1000;
    $timestamp = 	  $_POST ['timestamp'];
    $nombre_archivo = $_POST ['nombre_archivo'];

    $archivo = $_FILES ['archivo']['tmp_name'];
    $destino = $ruta_almacenamiento . $timestamp . $nombre_archivo;

    $info_imagen =	  getimagesize ($archivo);

	if (!$info_imagen) {
		// La imagen no contiene información de sus dimensiones
		SubirImagenNormal (
			$archivo, 
			$destino, 
			$nombre_archivo, 
			$timestamp
		); exit;
	}
	$ancho_original = 	$info_imagen [0];
	$alto_original = 	$info_imagen [1];
	$tipo_imagen = 		$info_imagen [2];

	// El ancho de la imagen excede el máximo
	if ($ancho_original > $ancho_maximo) {
		SubirImagenRedimensionada (
			$ancho_maximo, 
			$alto_original, 
			$ancho_original, 
			$archivo,
			$tipo_imagen,
			$destino,
			$timestamp,
			$nombre_archivo
		); exit;
	} 
	else {
		// El ancho de la imagen es menor que el máximo
		SubirImagenNormal (
			$archivo, 
			$destino, 
			$nombre_archivo, 
			$timestamp
		);
	}
}

function GestionarCambiosImg ($nombre_archivo, $timestamp) {
	$nuevo_recurso = '../../uploads/img/' . $timestamp . $nombre_archivo;
	GuardarCambiosRecurso ($nuevo_recurso);
	echo $nuevo_recurso;
}

function SubirImagenNormal ($archivo, $destino, $nombre_archivo, $timestamp) {
	if (move_uploaded_file($archivo, $destino)) {
		GestionarCambiosImg ($nombre_archivo, $timestamp);
	} 
	else echo 'Error al subir el archivo.';
}

function SubirImagenRedimensionada (
	$ancho_maximo, 
	$alto_original, 
	$ancho_original, 
	$archivo,
	$tipo_imagen,
	$destino,
	$timestamp,
	$nombre_archivo
) {
	// Calcular nuevo alto manteniendo la proporción
	$nuevo_ancho = $ancho_maximo;
	$nuevo_alto = intval ($alto_original * ($ancho_maximo / $ancho_original));

	switch ($tipo_imagen) {
		case IMAGETYPE_JPEG:
			$imagen_original = imagecreatefromjpeg ($archivo);
			break;
		case IMAGETYPE_PNG:
			$imagen_original = imagecreatefrompng ($archivo);
			break;
		case IMAGETYPE_GIF:
			$imagen_original = imagecreatefromgif ($archivo);
			break;
		default:
			// No es una imagen compatible con el procesamiento
			SubirImagenNormal (
				$archivo, 
				$destino, 
				$nombre_archivo, 
				$timestamp
			); exit;
	}

	// Crear nueva imagen con las dimensiones calculadas
	$imagen_redimensionada = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);

	// Mantener transparencias 
	if ($tipo_imagen == IMAGETYPE_PNG || $tipo_imagen == IMAGETYPE_GIF) {
		$transparente = imagecolorallocatealpha($imagen_redimensionada, 255, 255, 255, 127);
		imagealphablending 	 ($imagen_redimensionada, false);
		imagesavealpha 		 ($imagen_redimensionada, true);
		imagefilledrectangle ($imagen_redimensionada, 0, 0, $nuevo_ancho, $nuevo_alto, $transparente);
	}

	// Copiar y redimensionar la imagen
	imagecopyresampled ($imagen_redimensionada, $imagen_original, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho_original, $alto_original);

	// Guardar la imagen redimensionada
	switch ($tipo_imagen) {
		case IMAGETYPE_JPEG:
			imagejpeg ($imagen_redimensionada, $destino, 90);
			break;
		case IMAGETYPE_PNG:
			imagepng ($imagen_redimensionada, $destino, 9);
			break;
		case IMAGETYPE_GIF:
			imagegif ($imagen_redimensionada, $destino);
			break;
	}

	// Liberar memoria
	imagedestroy ($imagen_original);
	imagedestroy ($imagen_redimensionada);

	GestionarCambiosImg ($nombre_archivo, $timestamp);
}

function GuardarCambiosRecurso ($recurso) {
	$cambios = array();
	if (isset ($_COOKIE ['cambios_multimedia'])) {
		$cambios = json_decode ($_COOKIE ['cambios_multimedia']);
	}
	
	array_push ($cambios, $recurso);
	setcookie ('cambios_multimedia', json_encode($cambios));
}

function LimpiarCambiosNoGuardados () {
	if (!isset ($_COOKIE ['cambios_multimedia'])) return;
	
	$cambios = json_decode ($_COOKIE ['cambios_multimedia']);
	foreach ($cambios as $cambio) EliminarRecurso ($cambio, false);
}

?>