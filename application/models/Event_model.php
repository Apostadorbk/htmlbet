<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__.'\Database.php';


//use Database;

class Event_model extends Database {

	public function __construct() {
		parent::__construct();
	}

	public function get() {
		$results = $this->select("SELECT idcountry, descountry FROM tb_country");

		return $this->encoded($results, 'descountry');
	}

	public function getall() { // OK
		$results = $this->select("SELECT idcountry, descountry, intactive, intbetting, intclick FROM tb_country WHERE intactive = '1'");

		return $this->encoded($results, 'descountry');
	}


	public function setCountry($result, $list) { // OK
		
		$query = "INSERT INTO tb_country (idcountry, descountry) VALUES ";
		$values = [];
		$index = 0;


		for($i = 0; $i < count($result); $i++) {
			
			$query .= "(:idcountry".$i.", :descountry".$i.")";

			if ( $i == (count($result)-1)  ) {
				$query .= ";";
			} else {
				$query .= ",";
			}

			$values[":idcountry".$i] 	= $result[$i]->country_id;
			$values[":descountry".$i] = $list[$result[$i]->country_id];

		}

		return $this->query( $query, $values );

	}

	
}

 ?>