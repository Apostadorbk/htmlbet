<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('APICountry.php');
require_once('Constant_Leagues.php');

/*
*	Classe Auth
*
*	classe responsavel de manipular quem tá autenticado
*/
class APILeague {

	public static function league($idcountry = NULL) { // OK

		$APIkey = Constant::APIKEY;

		$country_id = $idcountry;
		
		$url = 'https://apifootball.com/api/?action=get_leagues';
		$url .= (isset($idcountry)) ? "&country_id={$country_id}" : "";
		$url .= "&APIkey={$APIkey}";


		$curl_options = array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_HEADER => false,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_CONNECTTIMEOUT => 10
		);                              
		 
		$curl = curl_init();
		curl_setopt_array( $curl, $curl_options );
		$result = curl_exec( $curl );

		$result = (array) json_decode($result);
		    
		return $result;

	}

	public static function getByCountry($idcountry, $decode = false) { // OK
		$leagues = [];
		if ( $decode ) {
			foreach (self::$_LEAGUE[$idcountry] as $key => $value) {
				$leagues[$key] = utf8_decode($value);
			}
			return $leagues;
		} else {
			return self::$_LEAGUE[$idcountry];
		}
	}

	// Pegando os dados da API e formatando
	public static function getAllLeagues() { // OK

		$all_leagues = self::league();

		$format = [];

		foreach ($all_leagues as $key => $value) {
			$format[$value->country_id][$value->league_id] = [$value->country_name, $value->league_name];
			// $format[$value->country_id][$value->league_id] = [APICountry::COUNTRY[$value->country_id], $value->league_name];
		}

		return $format;
	}

	// Checando se os dados da API e se as ligas estão atualizadas
	public static function check() {

		$all_leagues = self::getAllLeagues(); // Todas as ligas da API

		$diff_league = [];

		foreach ($all_leagues as $keyCountry => $country) {

			if ( isset(LEAGUES[$keyCountry]) ) {
				$diff_league[$keyCountry] = array_diff_key($country, LEAGUES[$keyCountry]);
			} else {
				$diff_league[$keyCountry] = $country;
			}

		}

		return $diff_league;

	}

	// Retorna todas as ligas definido em Constant_Leagues.php
	public static function getLeagues() {
		return LEAGUES;
	}

	public static function getLeague($idcountry = NULL) {
		if ( !isset($idcountry) ) {
			return LEAGUES;
		}

		if ( isset(LEAGUES[$idcountry]) ) {
			return LEAGUES[$idcountry];
		} else {
			return false;
		}
	}

	// Pegando os dados de países
	public static function getCountries() {
		return APICountry::COUNTRY;
	}

}

?>