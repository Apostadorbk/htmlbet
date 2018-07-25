<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Model.php';

class League_model extends Model {

	public function __construct() {
		parent::__construct();
	}

	public function teste() {
		
		return $this->db->select("SELECT * FROM tb_myleague");

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

		$query .= " FROM tb_myleague WHERE intactive = 1";

		return $this->setValues(
			$this->db->select($query)
		);

	}

	public function insertLeague(array $leagues = []):bool {
		if ( empty($leagues) ) return false;

		$query = "INSERT INTO `tb_myleague`(`idmyleague`, `idmycountry`, `idleague`, `desmyleague`, `intactive`, `dteregistro`) VALUES ";
		$total = count($leagues);
		$count = 0;

		do {

			$query .= "(
				NULL,
				'{$leagues[$count]['idmycountry']}',
				'{$leagues[$count]['idleague']}',
				'{$leagues[$count]['desmyleague']}',
				'1',
				CURRENT_TIMESTAMP
			)";

			$count++;

			if ( $count < $total ) $query .= ", "; 

		} while ( $count < $total );

		return $this->db->query($query);		

	}

}

 ?>