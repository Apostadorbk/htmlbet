<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('APILeague.php');
require_once('APICountry.php');
require_once('Time.php');

/*
*	Classe Auth
*
*	classe responsavel de manipular quem tá autenticado
*/
class APIOdd {
	
	public static function odd($params) { // OK
		
		$APIkey = Constant::APIKEY;

		$from = (isset($params['from']) && !empty($params['from'])) ? 
			Time::getDate([
				'date' => $params['from'],
				'format' => 'Y-m-d'
			]): Time::getDate([
				'format' => 'Y-m-d'
			]);

		$to = (isset($params['to']) && !empty($params['to'])) ? 
			Time::getDate([
				'date' => $params['to'],
				'format' => 'Y-m-d'
			]): Time::getDate([
				'format' => 'Y-m-d',
				'interval' => [
					'day' => 5
				]
			]);

		$url = "https://apifootball.com/api/?action=get_odds&from={$from}&to={$to}";

		$url .= ((isset($params['idmatch']) && !empty($params['idmatch'])) ? "&match_id={$params['idmatch']}": '');

		$url .= "&APIkey={$APIkey}";


		$curl_options = array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_HEADER => false,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_CONNECTTIMEOUT => 5
		);                              
		 
		$curl = curl_init();
		curl_setopt_array( $curl, $curl_options );
		$result = curl_exec( $curl );

		$result = (array) json_decode($result);

		return $result;
		
	}


}

?>