<?php

// ENTIDAD DE EMPLEADO
class Usuario {
	private $no_nomina;
	private $nombre;
	private $departamento;
	private $puesto;
	private $email;
	private $tipo_usuario;
	
	function __construct (
		$no_nomina = null, 
		$nombre = null, 
		$tipo_usuario = null, 
		$departamento = null, 
		$email = null,
		$puesto = null
	) {
		$this->setNumNomina 	($no_nomina);
		$this->setTipoUsuario 	($tipo_usuario);
		$this->setNombre 		($nombre);
		$this->setDepartamento 	($departamento);
		$this->setEmail 		($email);
		$this->setPuesto 		($puesto);
	}
	
	function setNumNomina ($valor) 		{ $this->no_nomina = $valor; }
	function setNombre ($valor) 		{ $this->nombre = $valor; }
	function setTipoUsuario ($valor) 	{ $this->tipo_usuario = $valor; }
	function setDepartamento ($valor) 	{ $this->departamento = $valor; }
	function setEmail ($valor) 			{ $this->email = $valor; }
	function setPuesto ($valor) 		{ $this->puesto = $valor; }
	function getNumNomina () 			{ return $this->no_nomina; }
	function getNombreMayus () 			{ return $this->nombre; }
	function getNombre () { 
	
		$nombre = strtolower ($this->nombre);
		$nombre = str_replace ('#', 'ñ', $nombre);
		$nombre_dividido = explode(" ", $nombre);
		$nombre = "";
		
		// Poner mayúsculas y minúsculas en el nombre
		foreach ($nombre_dividido as $seccion) {
			if (!isset($seccion[0])) continue;
			$seccion[0] = strtoupper($seccion[0]);
			$nombre .= $seccion." ";
		}
		return $nombre; 
	}
	function getTipoUsuario () { return $this->tipo_usuario; }
	function getNombreTipoUsuario () {
		
		// Tipos de usuario con privilegios
		$tipos_usuario = array (
			'Empleado', 'Instructor', 'Administrador'
		);
		return $tipos_usuario[$this->tipo_usuario];
	}
	function getTipoUsuarioInfo () {
		
		// Devolver nombre completo del tipo de usuario
		$tipos_usuario = array (
			"admin" => 		"Administrador",
			"empleado" => 	"Empleado",
			"instructor" => "Instructor"
		);
		return $tipos_usuario [$this->tipo_usuario];
	}
	function getDepartamento () { return $this->departamento; }
	function getEmail () 		{ return $this->email; }
	function getPuesto () 		{ return $this->puesto; }
}
?>