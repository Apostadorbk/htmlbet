<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class BASE_Model {

	// Dados que vem do BD
	private $values = [];



	public function __construct() {
		echo 'Criando o BASE Model'.'<br>';
		parent::__construct();
	}

	public function set($key = NULL, $value = NULL):bool {
		
		if ( !isset($key) || !isset($value) ) return false;

		$this->values[$key] = $value;

		return true;
	}

	public function get($key = NULL) {
		
		if ( !isset($key) ) return false;

		return ($this->$values[$key]) ?? false;
	
	}

	public function setValues(array $values = []):array {
		
		$error = [];

		foreach ($values as $key => $value) {
			$error[$key] = $this->set($key, $value);
		}

		return $error;
	}

	public function getValues(array $values = []):array {
		
		$data = [];

		foreach ($values as $key => $value) {
			$data[$key] = $this->get($key);
		}

		return $data;
	}

}

?>