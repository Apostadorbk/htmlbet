<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*	Classe Auth
*
*	classe responsavel de manipular quem tá autenticado
*/
class APILeague {
	
	private static $_LEAGUE = [
		'163' 	=>	[
			'782'	=> 'Grupo A',
			'783'	=> 'Grupo B',
			'784'	=> 'Grupo C',
			'785'	=> 'Grupo D',
			'786'	=> 'Grupo E',
			'787'	=> 'Grupo F',
			'788'	=> 'Grupo G',
			'789'	=> 'Grupo H',
			'790' 	=> 'Qualificação',
			'924'	=> 'Oitavas de Finais',
			'996'	=> 'Quartas de Finais',
			'1068'	=> 'Final',
			'1069'	=> 'Semi Finais'
		]
	];

	public static function league($idcountry) { // OK

		$APIkey = Constant::APIKEY;

		$country_id = $idcountry;

		$curl_options = array(
		  CURLOPT_URL => "https://apifootball.com/api/?action=get_leagues&country_id=$country_id&APIkey=$APIkey",
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

}

?>