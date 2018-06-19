<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Model {

	protected $columns = [];

	// Dados que vem do BD
	protected $values = [];

	protected $db;

	public function __construct() {
		$this->db = new Database();
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

	public function setValues(array $values = []):bool {
		
		if ( empty($values) ) return false;

		foreach ($values as $key => $value) {
			if ( !$this->set($key, $value) ) return false;
		}

		return true;
	}

	public function getValues(array $values = []):array {
		

		if ( empty($values) ) return $this->values;
		
		$data = [];

		foreach ($values as $key => $value) {
			$data[$key] = $this->get($key);
		}

		return $data;

	}

	public function setColumns(string $tableName) {

		$this->columns = [];

		if ( empty($tableName) || !isset($tableName) ) return false;

		$this->columns = $this->$this->db->select(
			"SHOW COLUMNS FROM {$tableName}"
		);

		return (count($this->columns) > 0) ? true : false;
	}

	public function getColumns() {
		return $this->columns;
	}

	public function getNumberRows(string $tableName) {

		if ( empty($tableName) || !isset($tableName) ) return NULL;

		$result = $this->db->select("
			SELECT COUNT(*) AS COUNT FROM {$tableName}
		");
		
		if ( count($result) > 0 ) {
			return (int) $result[0]['COUNT'];
		} else {
			return NULL;
		}
		
	}

}

?>