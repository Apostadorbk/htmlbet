<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__.'\Database.php';


//use Database;

class League_model extends Database {

	public function __construct() {
		parent::__construct();
	}

	/*
	public function getall() { // OK
		$results = $this->select("SELECT idcountry, descountry, intactive, intbetting, intclick FROM tb_country WHERE intactive = '1'");

		return $this->encoded($results, 'descountry');
	}
	*/


	public function setLeagues($league) { // OK
		
		$query = "INSERT INTO tb_league (idleague, idcountry, desleague) VALUES ";
		$values = [];
		$index = 0;

		$result = $league['leagues'];

		for($i = 0; $i < count($result); $i++) {
			
			$query .= "(:idleague".$i.", :idcountry".$i.", :desleague".$i.")";

			if ( $i == (count($result)-1)  ) {
				$query .= ";";
			} else {
				$query .= ",";
			}

			$values[":idleague".$i] 	= $result[$i]->league_id;
			$values[":idcountry".$i] 	= $league['_idcountry'];
			$values[":desleague".$i] 	= $league['_leagues'][$result[$i]->league_id];

		}

		return $this->query( $query, $values );
	
	}

	public function setActiveByCountry($idcountry) { // OK
		return $this->query("UPDATE tb_league
			SET intactive = '1'
			WHERE idcountry = :idcountry", [
			':idcountry' => $idcountry
		]);
	}

	public function getLeagueByCountry($idcountry) { // OK
		$results = $this->select("SELECT idleague, idcountry, desleague 
			FROM tb_league 
			WHERE idcountry = :idcountry AND intactive = '1'", [
			':idcountry' => $idcountry
		]);

		
		if ( !(count($results) > 0) ) {
			return false;
		}
		
		return $this->encoded($results, 'desleague');
	}
	
	public function getLeagues() { // OK
		$results = $this->select("SELECT idleague, idcountry, desleague 
			FROM tb_league 
			WHERE intactive = '1'");

		if ( !(count($results) > 0) ) {
			return false;
		}

		$results = $this->encoded($results, 'desleague');

		return $results;
	}
}

 ?>