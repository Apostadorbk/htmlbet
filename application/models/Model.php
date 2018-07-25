<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Model {

	private $field 		= [];
	private $queryField = NULL;
	private $query 		= NULL;

	protected $columns = [];

	// Dados que vem do BD
	protected $values = [];

	protected $db;

	public function __construct() {
		$this->db = new Database();

		$this->queryField 	= NULL;
		$this->query 		= NULL;
	}

	public function set($key = NULL, $value = NULL, bool $clear = false):bool {
		
		$this->values = ($clear) ? [] : $this->values;

		if ( !isset($key) || !isset($value) ) return false;

		$this->values[$key] = $value;

		return true;
	}

	public function get($key = NULL) { // OK
		
		if ( !$this->hasValue() ) return false;

		if ( !isset($key) ) return $this->values[0];

		return $this->values[0][$key] ?? false;
	
	}

	public function setValues(array $values):bool { // OK
		
		$this->values = [];

		if ( empty($values) || !isset($values) ) return false;

		foreach ($values as $key => $value) {
			if ( !$this->set($key, $value) ) return false;
		}

		return true;

	}

	public function getValues($values = NULL) { // OK
		
		if ( !$this->hasValue() ) return false;

		if ( !isset($values) || empty($values) ) return $this->values;
		
		$data = [];

		switch (gettype($values)) {

			case 'array':
				
				foreach ($values as $value) {
				
					$data[$value] = NULL;

					if ( isset($this->values[0][$value]) ) {
						
						$data[$value] = [];

						foreach ($this->values as $v) {
						
							$data[$value][] = $v[$value];
							
						}

					}

				}

				if ( count($data) == 0 ) {
					return false;
				} else if ( count($data) == 1 ) {
					return $data[$values[0]];
				}
				

			break;

			// -----------------------------------------------

			case 'string':
				
				foreach ($this->values as $value) {
			
					$data[] = $value[$values];
					
				}

			break;
			
			default:
				$data = [];
			break;
		}

		return $data;

	}

	public function hasValue():bool {
		return (isset($this->values) && !empty($this->values));
	}

	public function field($field = '*') {

		$this->queryField = NULL;

		switch (gettype($field)) {

			case 'string':
				
				$this->queryField = $field;
				
			break;

			case 'array':
				
				if ( empty($field) )
					$this->queryField = '*';
				else
					$this->queryField = implode(', ', $field);

			break;
			
		}

		return $this;

	}

	public function getField():string {
		return $this->queryField = $this->queryField ?? '*';
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