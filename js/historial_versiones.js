let modal_historial =  	 null;
let bs_modal_historial = null;
let in_version = 		 null;
let registro_versiones = null;
let seleccionado =       null;
let comp_modal =         null;
let pagina_externa =     null;
let _id_curso =          null;
let _no_nomina =         null;
let _version =           null;

function CerrarHistorial () {
    comp_modal.remove ();
    comp_modal = null;
}

function AbrirHistorial (id_cur = null, nomina = null, vers = null, externo = false) {

    if (!comp_modal) {
        comp_modal = document.createElement ('div');
        comp_modal.appendChild ($temp_historial_versiones.content.cloneNode (true));
        document.body.appendChild (comp_modal);
    }
    pagina_externa = externo;
    _id_curso =  typeof id_curso  != 'undefined' ? id_curso : id_cur;
    _no_nomina = typeof no_nomina != 'undefined' ? no_nomina : nomina;
    _version =   typeof version   != 'undefined' ? version : vers;

    modal_historial =  	    document.querySelector ('#historial-versiones');
    bs_modal_historial = 	new bootstrap.Modal (modal_historial);
    in_version = 			document.querySelector ('select');

    in_version.addEventListener ('change', (e) => {
        seleccionarOpcion (in_version.value);
    });

    // Limpiar lista de versiones
    in_version.innerHTML = '';
    
    modal_historial.addEventListener ('show.bs.modal', async (e) => {
        const formData = 	new FormData ();
        formData.append ('recuperar_versiones', _id_curso);
    
        await fetch ('modulos/CursosInternos.php', {
            method: 'POST',
            body: formData
        })
        .then ((response) => response.json())
        .then ((versiones) => {
            // Almacenar resultados recuperados
            registro_versiones = versiones;
    
            // Crear un arreglo únicamente con los números de las versiones
            const lista_versiones = Array.from (versiones).map(
                version => version['Version']
            );
    
            // Obtener el registro de la versión que se está visualizando actualmente
            version_visualizada = registro_versiones.findIndex (
                versiones => (versiones ['Version'] == _version)
            );
    
            lista_versiones.forEach ((version, index) => {
                // Agregar números de versiones a la lista "select"
                const opcion = 	 	 document.createElement ('option');
                opcion.value = 		 index;
                opcion.textContent = version;
    
                in_version.appendChild (opcion);
            });
    
            // Seleccionar versión visualizada
            in_version.value = version_visualizada;	
            seleccionarOpcion (version_visualizada);			
        });
    });

    bs_modal_historial.show ();
}

const seleccionarOpcion = (opcion) => {
    const $curso =       modal_historial.querySelector ('[data-info="curso"]');
    const $creado = 	 modal_historial.querySelector ('[data-info="creado"]');
    const $actualizado = modal_historial.querySelector ('[data-info="actualizado"]');
    const $comentarios = modal_historial.querySelector ('[data-info="comentarios"]');

    // Recuperar información de la versión seleccionada de la lista
    seleccionado = registro_versiones [opcion];

    // Mostar información
    $curso.textContent =       seleccionado ['Curso'];
    $creado.textContent = 	   seleccionado ['nombre'];
    $actualizado.textContent = seleccionado ['nombre'];
    $comentarios.textContent = seleccionado ['Comentarios'];
};

function VisualizarVersion () {
    // Mostra versión seleccionada del curso, en una nueva pestaña
    if (pagina_externa) {
        window.open(
            'visualizador.php?curso_int=' + _id_curso + 
            '&version=' + seleccionado ['Version'] + '&visualizar=true',
            '_blank'
        ); return;
    }
    // Mostrar version seleccionada del curso, en el visualizador
    window.location.replace (
        'visualizador.php?curso_int=' + _id_curso + 
        '&version=' + seleccionado ['Version'] + '&visualizar=true'
    );
}