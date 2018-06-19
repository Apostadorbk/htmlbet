<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//use Database;

class Country_model extends Database {

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

	
}

 ?>