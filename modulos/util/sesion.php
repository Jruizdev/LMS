<?php
require_once ('usuario.php');

// CLASE QUE SE UTILIZARÁ PARA LA GESTIÓN DE SESIONES DE USUARIO
class Sesion extends Usuario {
	private $tmp_pass;
	private $autenticado;
	
	function __construct ($num_nomina, $nombre, $tipo_usuario, $departamento, $email) {
		parent::__construct ($num_nomina, $nombre, $tipo_usuario, $departamento, $email);
		$this->setAutenticado (true);
		$this->setTmpPass (false);
	}

	function setTmpPass ($valor)		{ $this->tmp_pass = $valor; }
	function setAutenticado ($valor)	{ $this->autenticado = $valor; }
	function getTmpPass ()				{ return $this->tmp_pass; }
	function getAutenticado ()			{ return $this->autenticado; }
}

?>