window.addEventListener ('keypress', (e) => {
    
    // Utilizar la tecla 'Enter' para activar botón de acción
    const entrada = document.querySelector ('input:focus');
    
    if ((e.code !== 'Enter' && e.code !== 'NumpadEnter') || !entrada) return;
    
    const parent = entrada.closest (
        `[data-componente="ventana"], 
        [data-componente="bloque"]`
    );
    
    // Obtener botón de acción
    const btn = parent ? parent.querySelector ('[data-tipo="btn-accion"]') : null;

    // No se encontró ningún posible botón de acción
    if (!btn) return; 

    btn.click ();
});