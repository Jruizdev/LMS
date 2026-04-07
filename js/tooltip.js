function MostrarTooltip ($campo, mensaje, duracion, tooltip_previo = null) {
	
	if (tooltip_previo) {
		
		// Mostrar instancia de tooltip ya existente
		$campo.insertAdjacentElement ('afterend', tooltip_previo);
		
		setTimeout (() => {
			tooltip_previo.remove();
		}, duracion);
		return tooltip_previo;
	}
	
	// Nuevo tooltip
	const elemento = 	  $temp_tooltip_input.content.cloneNode (true).querySelector ('div');
	const elemento_body = elemento.querySelector ('span');
	
	elemento_body.textContent = mensaje;
	
	setTimeout (() => {
		elemento.remove();
	}, duracion);
	
	// Mostrar tooltip indicando el mensaje de error
	$campo.insertAdjacentElement ('afterend', elemento);
	
	return elemento;
}