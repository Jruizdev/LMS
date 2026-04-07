function btnModoCarga (btn) {
    // Cambiar estado del botón a modo de carga (recuperación de contenido)
    btn.disabled = 	  true;
    const rollback =  btn.innerHTML;
    const icono_btn = btn.querySelector ('svg');

    if (icono_btn) icono_btn.remove ();

    btn.insertAdjacentHTML (
        'beforeend', 
        '<div class="spinner-border spinner-border-sm ms-2" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
        '</div>'
    );
    return rollback;
}

function btnModoNormal (btn, rollback) {
    // Cambiar estado del botón a modo normal
    btn.innerHTML = rollback;
    btn.disabled =  false;
}