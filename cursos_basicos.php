<?php 
require_once ('modulos/CursosInternos.php');

$lista_cursos = ObtenerCursosAbiertos ();

function prioridadOEE ($a, $b) {
    $a_prioridad = (stripos ($a ['Nombre'], 'oee') !== false);
    $b_prioridad = (stripos ($b ['Nombre'], 'oee') !== false);
    
    if ($a_prioridad == $b_prioridad) {
        return 0;
    }
    return ($a_prioridad > $b_prioridad) ? -1 : 1;
}

// Dar mayor prioridad a los cursos del OEE
usort ($lista_cursos, 'prioridadOEE');
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title>entidad | Cursos Básicos</title>
		<link href="css/bootstrap@5.3.3/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/root.css" rel="stylesheet"/>
		<link href="css/estilos-nuevo.css" rel="stylesheet"/>
		<link href="css/font-awesome.min.css" rel="stylesheet"/>
		<script src="js/bootstrap@5.3.3/bootstrap.bundle.min.js"></script>
	</head>
	<body>
		<header style="position: relative">
            <div class="top-bar shadow-sm d-flex">
                <img class="aftermarket" src="uploads/placeholder/entidad_B_Aftermarket_CMYK.png" class="img-fluid">
                <div class="busqueda-curso mb-4 mx-auto d-flex flex-column">
                    <div class="d-flex flex-grow-1">
                        <img src="uploads/placeholder/mail_logo.png">
                        <button class="btn btn-seccondary dialog ms-auto my-auto d-flex" onclick="window.location.assign('index.php')">
                            <span class="my-auto">Abrir LMS</span>
                            <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="30"><path fill="#FFF" d="M560.3 110.5L420.5 110.5C372.4 110.5 330.6 143.8 320.1 190.8C309.5 143.8 267.8 110.5 219.7 110.5L80 110.5C53.5 110.5 32 132 32 158.5L32 404.3C32 430.8 53.5 452.3 80 452.3L169.7 452.3C271.9 452.3 302.4 476.7 317 527.3C317.7 530.1 322.2 530.1 323 527.3C337.7 476.7 368.2 452.3 470.3 452.3L560 452.3C586.5 452.3 608 430.8 608 404.3L608 158.6C608 132.2 586.7 110.7 560.3 110.5zM274 375.9C274 377.8 272.5 379.4 270.5 379.4L110.2 379.4C108.3 379.4 106.7 377.9 106.7 375.9L106.7 353C106.7 351.1 108.2 349.5 110.2 349.5L270.6 349.5C272.5 349.5 274.1 351 274.1 353L274.1 375.9L274 375.9zM274 315C274 316.9 272.5 318.5 270.5 318.5L110.2 318.5C108.3 318.5 106.7 317 106.7 315L106.7 292.1C106.7 290.2 108.2 288.6 110.2 288.6L270.6 288.6C272.5 288.6 274.1 290.1 274.1 292.1L274.1 315L274 315zM274 254.1C274 256 272.5 257.6 270.5 257.6L110.2 257.6C108.3 257.6 106.7 256.1 106.7 254.1L106.7 231.2C106.7 229.3 108.2 227.7 110.2 227.7L270.6 227.7C272.5 227.7 274.1 229.2 274.1 231.2L274.1 254.1L274 254.1zM533.3 375.8C533.3 377.7 531.8 379.3 529.8 379.3L369.5 379.3C367.6 379.3 366 377.8 366 375.8L366 352.9C366 351 367.5 349.4 369.5 349.4L529.9 349.4C531.8 349.4 533.4 350.9 533.4 352.9L533.4 375.8L533.3 375.8zM533.3 314.9C533.3 316.8 531.8 318.4 529.8 318.4L369.5 318.4C367.6 318.4 366 316.9 366 314.9L366 292C366 290.1 367.5 288.5 369.5 288.5L529.9 288.5C531.8 288.5 533.4 290 533.4 292L533.4 314.9L533.3 314.9zM533.3 254C533.3 255.9 531.8 257.5 529.8 257.5L369.5 257.5C367.6 257.5 366 256 366 254L366 231.2C366 229.3 367.5 227.7 369.5 227.7L529.9 227.7C531.8 227.7 533.4 229.2 533.4 231.2L533.4 254L533.3 254z"/></svg>
                        </button>
                    </div>
                    <div class="barra-busqueda" style="position: relative">
                        <div class="contenedor-icono">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="25"><path fill="var(--menu-secundario)" d="M544 513L397.2 364.2C417.2 336.3 429.1 302 429.1 265C429.1 171.9 354.4 96.1 262.6 96.1C170.7 96 96 171.8 96 264.9C96 358 170.7 433.8 262.5 433.8C302.3 433.8 338.8 419.6 367.5 395.9L513.5 544L544 513zM262.5 394.8C191.9 394.8 134.4 336.5 134.4 264.9C134.4 193.3 191.9 135 262.5 135C333.1 135 390.6 193.3 390.6 264.9C390.6 336.5 333.2 394.8 262.5 394.8z"/></svg>
                        </div>
                        <input id="buscar-cursos" class="form-control" type="text" placeholder="Buscar cursos...">
                    </div>
                </div>
            </div>
		</header>
		
		<article class="contenido-wrap">
		
            <div style="height: 120px; width: 100%"></div>

            <div class="d-flex mx-auto mt-5 pt-5 mb-4" style="width: 800px">
                <div class="d-flex flex-grow-1">
                    <h2 class="flex-grow-1 m-1">Cursos Básicos</h2>
                    <span id="indicador-lista" class="indicador-tag"><?php echo count ($lista_cursos) ?> disponibles</span>
                </div>
            </div>

            <div id="lista-cursos" class="contenedor-cursos p-1">

                <?php 
                $curso_abierto_html = file_get_contents (
                    'uploads/html/elementos/curso_abierto.html'
                );

                foreach ($lista_cursos as $curso) {
                    $elemento_lista = $curso_abierto_html;
                    $portada_personalizada = $curso ['Portada'] != '' && file_exists ('uploads/portadas/'.$curso ['Portada']) ?
                                           '' : 'hidden';
                    $portada_default = $portada_personalizada == 'hidden' ?
                                       '' : 'hidden';
                    $elemento_lista = str_replace ('{URL_PORTADA_CURSO}', 'uploads/portadas/'.$curso ['Portada'], $elemento_lista);
                    $elemento_lista = str_replace ('{PORTADA_DEFAULT}', $portada_default, $elemento_lista);
                    $elemento_lista = str_replace ('{PORTADA_PERSONALIZADA}', $portada_personalizada, $elemento_lista);
                    $elemento_lista = str_replace ('{NOMBRE_CURSO}', $curso ['Nombre'], $elemento_lista);
                    $elemento_lista = str_replace ('{DESCRIPCION_CURSO}', $curso ['Descripcion'], $elemento_lista);
                    $elemento_lista = str_replace ('{ID_CURSO}', $curso ['Id_curso'], $elemento_lista);
                    
                    echo $elemento_lista;
                }
                ?>
            </div>
		</article>
		
	</body>
    <script src="js/idlein.js"></script>
    <script>
        const contenedor_lista = document.querySelector ('#lista-cursos');
        const in_busqueda =      document.querySelector ('#buscar-cursos');
        const indicador =        document.querySelector ('#indicador-lista');

        let tmp_curso_abierto =        false;
        let elemento_curso_abierto =   null;
        let elemento_resultado_vacio = null;

        async function CargarElementosHTML () {
            elemento_curso_abierto =    await CargarElementoHTML ('curso_abierto').catch (() => {});
            elemento_resultado_vacio =  await CargarElementoHTML ('resultado_vacio').catch (() => {});
        }

        async function ComprobarPortada (portada) {
            if (portada == '') return false;
            try {
                const comprobacion = await fetch (
                    `uploads/portadas/${portada}`
                );
                return comprobacion.ok;
            } 
            catch (error) { return false; }
        }

        async function CargarElementoHTML (nombre) {
            tmp_elemento = await fetch (
                `uploads/html/elementos/${ nombre }.html`
            );
            if (!tmp_elemento.ok) 
                throw new Error ('Hubo un problema al recuperar el elemento de la página');
            return await tmp_elemento.text ();
        }

        async function MostrarResultados (lista_cursos, criterio) {
            // Limpiar lista de cursos
            contenedor_lista.innerHTML = '';

            if (!lista_cursos.length) {
                // No se encontraron cursos
                indicador.textContent = '0 disponibles';
                contenedor_lista.innerHTML = elemento_resultado_vacio
                                            .replace ('{CRITERIO}', criterio);
                return;
            }
            
            lista_cursos.forEach (async (curso) => {
                const existe_portada = await ComprobarPortada (curso ['Portada']);
                
                // Agregar cursos a la lista
                contenedor_lista.innerHTML += elemento_curso_abierto
                                         .replace ('{NOMBRE_CURSO}', curso ['Nombre'])
                                         .replace ('{DESCRIPCION_CURSO}', curso ['Descripcion'])
                                         .replace ('{URL_PORTADA_CURSO}', existe_portada ? `uploads/portadas/${curso ['Portada']}` : '')
                                         .replace ('{PORTADA_DEFAULT}', existe_portada ? 'hidden' : '')
                                         .replace ('{PORTADA_PERSONALIZADA}', existe_portada ? '' : 'hidden')
                                         .replace ('{ID_CURSO}', curso ['Id_curso']);
            });
            indicador.textContent = `${ lista_cursos.length } disponibles`;
        }

        async function RealizarBusqueda () {

            // Mostrar spinner de carga
            const spinner = document.createElement ('div');
            spinner.classList.add ('spinner-border', 'text-danger');
            spinner.innerHTML = `<span class="visually-hidden">Loading...</span>`;
            spinner.style.position = 'absolute';
            spinner.style.width = '20px';
            spinner.style.height = '20px';
            spinner.style.top = '50%';
            spinner.style.right = '10px';
            spinner.style.translate = '0 -50%';

            in_busqueda.insertAdjacentElement ('afterend', spinner);

            // Obtener y sanitizar entrada
            const criterio = in_busqueda.value.trim ()
                            .replace (/&/g, '&amp;')
                            .replace (/</g, '&lt;')
                            .replace (/>/g, '&gt;')
                            .replace (/"/g, '&quot;')
                            .replace (/'/g, '&#039;');

            const formConsulta = new FormData ();
            formConsulta.append ('buscar-curso-abierto', 'true');
            formConsulta.append ('busqueda', criterio);

            await fetch ('modulos/CursosInternos.php', {
                method: 'POST',
                body: formConsulta
            })
            .then ((resp) => resp.json ())
            .then ((resultados) => setTimeout (() => {
                MostrarResultados (resultados, criterio);
                spinner.remove ();
            }, 1000));
        }

        const idle_busqueda = new IdleIn (

            // Espera que el usuario deje de interactuar con el input 500 ms, para poder ejecutar la función "RealizarBusqueda" 
            in_busqueda,
            1, 500, () => RealizarBusqueda ()
        );

        in_busqueda.addEventListener ('input', () => idle_busqueda.ActualizarEspera ());
        CargarElementosHTML ();
    </script>
</html>