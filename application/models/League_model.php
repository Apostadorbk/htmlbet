<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Model.php';

class League_model extends Model {

	public function __construct() {
		parent::__construct();
	}

	public function teste() {
		
		$this->select("SELECT * FROM tb_country");

		/*
		echo '<pre>';
		var_dump( $this->select("SELECT * FROM tb_country") );
		echo '</pre>';
		*/
	}

	public function setLeague(array $fields = []):bool { // OK

		if ( count($fields) < 0 ) return false;

		$query = "SELECT ";

		if ( count($fields) > 0 ) {

			$query .= "{$fields[0]}";

			for ( $i = 1; $i < count($fields); $i++ ) { 
				$query .= ", {$fields[$i]}";
			}

		} else {
			$query .= "*";
		}

		$query .= " FROM tb_league WHERE intactive = 1";

		return $this->setValues(
			$this->db->select($query)
		);

	}

}

 ?>