<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

//use Database;

class Odd_model extends Model {

	public function __construct() {
		parent::__construct();
	}

	public function setTypeOdd($fields = '*'):bool { // OK

		if ( empty($fields) || !isset($fields) ) return false;

		$query = "SELECT ".$this->field($fields)->getField();

		$query .= " FROM tb_mytypeodd WHERE intactive = 1";

		return $this->setValues(
			$this->db->select($query)
		);

	}

}

 ?>