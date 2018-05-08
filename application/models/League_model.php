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


	public function setLeagues($countries) { // OK

		$_leagues = [];

		foreach ($countries as $key => $league) {
			if ( !empty($league) ) {
				$_leagues[$key] = $league;
			}
		}

		if ( empty($_leagues) ) {
			return true;
		}

		$counter = 0;
		
		$query = "INSERT INTO tb_league (idleague, idcountry, desleague) VALUES (:idleague, :idcountry, :desleague);";
		$values = [];
		$result = [];

		foreach ($_leagues as $keyCountry => $country) {
			foreach ($country as $keyLeague => $league) {

				$values[":idleague"] 	= $keyLeague;
				$values[":idcountry"] 	= $keyCountry;
				$values[":desleague"] 	= (isset($league[1])) ? $this->decoded($league[1]) : NULL;

				$result[$keyCountry][$keyLeague] = $this->query( $query, $values );

			}
		}
		
		return $result;
		
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
	
	public function getLeagues($active = true) { // OK

		$sql = "SELECT idleague, idcountry, desleague 
			FROM tb_league".( ($active) ? " WHERE intactive = '1;'" : ';' );

		$results = $this->select($sql);

		if ( !(count($results) > 0) ) {
			return false;
		}

		$results = $this->encoded($results, 'desleague');

		return $results;
	}
}

 ?>