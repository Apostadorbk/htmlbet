<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

//use Database;

class Time_model extends Model {

	public function __construct() {
		parent::__construct();
	}

	public function setTime($fields = '*'):bool { // OK

		if ( empty($fields) || !isset($fields) ) return false;

		$query = "SELECT ".$this->field($fields)->getField();

		$query .= " FROM tb_time";

		return $this->setValues(
			$this->db->select($query)
		);

	}

	public function updateTime(string $destype):bool {

		if ( empty($destype) || !isset($destype) ) return false;

		$query = "UPDATE tb_time SET dteupdate = NOW() WHERE destype = '{$destype}'";

		return $this->db->query($query);

	}

}

 ?>