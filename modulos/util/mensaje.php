<?php
class Mensaje {
	private $tipo;
	private $texto;
	private $accion;
	private $titulo;
	
	function __construct ($tipo = null, $texto = null, $accion = null, $titulo = null) {
		if(isset ($tipo)) $this->setTipo 	 ($tipo);
		if(isset ($texto)) $this->setTexto   ($texto);
		if(isset ($accion)) $this->setAccion ($accion);
		if(isset ($titulo)) $this->setTitulo ($titulo);
	}
	
	function setTipo ($valor) 	{ $this->tipo = $valor; }
	function setTexto ($valor) 	{ $this->texto = $valor; }
	function setAccion ($valor) { $this->accion = $valor; }
	function setTitulo ($valor) { $this->titulo = $valor; }
	function getTipo () 	{ return $this->tipo; }
	function getTexto () 	{ return $this->texto; }
	function getAccion () 	{ return $this->accion; }
	function getTitulo () 	{ return $this->titulo; }
}
?>