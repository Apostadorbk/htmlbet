<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Model.php';

class Country_model extends Model {

	public function __construct() {
		parent::__construct();
	}

	public function setCountry(array $fields = []):bool { // OK

		if ( count($fields) < 0 ) return false;


		$query = "SELECT ".$this->field($fields)->getField();

		$query .= " FROM tb_mycountry WHERE intactive = 1";

		return $this->setValues(
			$this->db->select($query)
		);

	}

	public function insertCountry(array $countries = []):bool {
		
		if ( empty($countries) ) return false;

		$query = "INSERT INTO `tb_mycountry`(`idmycountry`, `desmycountry`, `intbetting`, `intactive`, `dteregistro`) VALUES ";
		$total = count($countries);
		$count = 0;

		do {

			$query .= "(
				NULL,
				'{$countries[$count]}',
				'0',
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