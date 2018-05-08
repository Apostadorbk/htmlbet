<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('APICountry.php');
require_once('Constant_Leagues.php');
require_once('Time.php');

/*
*	Classe Auth
*
*	classe responsavel de manipular quem tá autenticado
*/
class APIEvent {

	private static $country = [
		'idcountry'	=> '',
		'descountry' => ''
	];

	private static $league = [
		'idleague' => '',
		'desleague' => ''
	];

	private static $match = [];

	private static $events = [];


	public static function event($params) { // OK

		Time::setTimeZone("DF");

		$result = '';

		$APIkey = Constant::APIKEY;

		$from = Time::getDate([
			'format' => 'Y-m-d'
		]);

		$to = Time::getDate([
			'format' => 'Y-m-d',
			'interval' => [
				'day' => 10
			]
		]);

		$country_id = $params['idcountry'];
		
		$curl_options = array(
			CURLOPT_URL => 
				"https://apifootball.com/api/?action=get_events&from=$from&to=$to&country_id={$country_id}&APIkey=$APIkey",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => false,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_CONNECTTIMEOUT => 10
		);                              
		 
		/*
		echo "<pre>";
		var_dump( $_GET );
		echo "</pre>";
		*/

		$curl = curl_init();
		curl_setopt_array( $curl, $curl_options );
		$result = curl_exec( $curl );

		$result = json_decode($result, true);

		$_result = self::fromAPI($result);

		/*
		echo '<pre>';
		var_dump( $result );
		echo '</pre>';
		*/
		
		return $_result;
		
	}

	// Formatando o evento
	public static function fromAPI( $result ) { // OK

		$response = [];

		$response['status'] = true;
		$response['message'] = "Recebido";
		$response['data'] = $result;

		// ---------------------------------------------------------------------------------------

		// Verificando os erros
		if ( isset($result['error']) ) {

			switch ($result['error']) {
				case 404:
					$response['status'] = false;
					$response['message'] = "Nenhum evento encontrado (Por favor olhe seu plano)";
					$response['data'] = NULL;
				break;
				
				default:

				break;
			}

			return $response;
		}

		// ---------------------------------------------------------------------------------------

		return $response;
	}

	public static function formatEvents($events) { // OK

		self::$events = [];

		$index = 0;

		foreach ($events as $key => $value) {
			
			self::formatMatch($value);
			
			self::addEvent();

		}

		echo '<hr>';
		echo '<pre>';
		var_dump( self::$events );
		echo '</pre>';
		echo '<hr>';
		

		//return $format;
	}

	public static function formatMatch($match) { // OK

		foreach ($match as $key => $value) {
			switch ($key) {

				case 'match_id':
					self::$match['idmatch'] = $value;
				break;
				
				case 'country_id':
					self::$country['idcountry'] = $value;
					self::$match['idcountry'] = $value;
				break;
				
				case 'league_id':
					self::$league['idleague'] = $value;
					self::$match['idleague'] = $value;
					self::$match['descountry'] 		= LEAGUES[self::$match['idcountry']][$value][0];
					self::$country['descountry'] 	= self::$match['descountry'] ;
					self::$match['desleague'] 		= LEAGUES[self::$match['idcountry']][$value][1];
					self::$league['desleague'] 		= self::$match['desleague'];
				break;
				
				case 'match_date':
					self::$match['dtematch'] = $value;
				break;
				
				case 'match_status':

					switch ($value) {

						case '':
							self::$match['desstatus'] = 'UPCOMING';
						break;

						case 'FT':
							self::$match['desstatus'] = 'FULL_TIME';
						break;

						case 'Canc.':
							self::$match['desstatus'] = 'CANCELED';
						break;

						case 'Postp.':
							self::$match['desstatus'] = 'POSTPONED';
						break;

						case 'AAW':
							self::$match['desstatus'] = 'AWARDED_AWAY_WIN';
						break;

						case 'AP':
							self::$match['desstatus'] = 'AFTER_PENALTY';
						break;

						case 'AET':
							self::$match['desstatus'] = 'AFTER_EXTRA_TIME';
						break;
						
						default:
							self::$match['desstatus'] = 'LIVE';
						break;
						
					}

				break;
				
				case 'match_time':

					self::$match['dtematch'] .= ' ' . $value;
					self::$match['dtematch'] = Time::getDate([
						'date' => self::$match['dtematch'],
						'interval' => [
							'hour' => 5,
							'sub' => true
						]
					]);

				break;
				
				case 'match_hometeam_name':
					self::$match['deshometeam'] = $value;
				break;
				
				case 'match_hometeam_score':
					self::$match['inthometeamscore'] = (empty($value) || $value == '?') ? '0': $value;
				break;
				
				case 'match_awayteam_name':
					self::$match['desawayteam'] = $value;
				break;
				
				case 'match_awayteam_score':
					self::$match['intawayteamscore'] = (empty($value) || $value == '?') ? '0': $value;
				break;
				
				case 'match_hometeam_halftime_score':
					self::$match['inthometeamhalftimescore'] = (empty($value)) ? '0': $value;
				break;
				
				case 'match_awayteam_halftime_score':
					self::$match['intawayteamhalftimescore'] = (empty($value)) ? '0': $value;
				break;
				
				case 'match_hometeam_system':
					self::$match['deshometeamsystem'] = (empty($value)) ? false: $value;
				break;
				
				case 'match_awayteam_system':
					self::$match['desawayteamsystem'] = (empty($value)) ? false: $value;
				break;
				
				case 'match_live':
					self::$match['intlive'] = $value;
				break;

				
				case 'goalscorer':

					if ( empty($value) || !isset($value) ) {

						self::$match['goalscorer'] = false;

					} else {

						self::$match['goalscorer'] = [];

						foreach ($value as $k1 => $v1) {
							foreach ($v1 as $k2 => $v2) {
								
								switch ($k2) {
									case 'time':
										self::$match['goalscorer'][$k1]['destime'] = $v2;
									break;
									
									case 'home_scorer':
										self::$match['goalscorer'][$k1]['deshomescorer'] = $v2;
									break;
									
									case 'score':
										self::$match['goalscorer'][$k1]['desscore'] = $v2;
									break;

									case 'away_scorer':
										self::$match['goalscorer'][$k1]['desawayscorer'] = $v2;
									break;
								}

							}
						}

					}

				break;
				
				
			}
		}

	}

	public static function getMatch( $result, $date, $days ) { //

		$new = [];

		$initialDate = new DateTime( Time::getDate([
			'date' => $date
		]));
		
		$finalDate = new DateTime( Time::getDate([
			'date' => $date,
			'interval' => [
				'day' => $days,
				'hour' => 23,
				'minute' => 59,
				'second' => 59
			]
		]));

		
		$index = 0;
		
		foreach ($result as $key => $value) {
			
			$matchDate = new DateTime($value['dtematch']);

			if ( ($initialDate <= $matchDate) && ($finalDate >= $matchDate) ) {
				$new[$index] = $value;
				$index++;
			}
			
		}
		
		unset($initialDate);
		unset($finalDate);
		
		/*
		echo '<pre>';
		var_dump( $initialDate, $finalDate );
		echo '</pre>';
		*/
		return $new;

	}

	public static function addEvent() { // OK

		$length = count(self::$events);
	
		if ( $length == 0 ) {

			self::$events[] = self::$country;

			self::$events[0]['leagues'][] = self::$league;
			self::$events[0]['leagues'][0]['matches'][] = self::$match;

		} else {

			$keyCountry = 0;
			$keyLeague = 0;
			$countries = self::$events;
			
			while ($country = current($countries)) {
					
				$keyCountry = key($countries);
				
				// País encontrado
			    if ($country['idcountry'] == self::$country['idcountry']) {

			    	$leagues = self::$events[$keyCountry]['leagues'];

			    	if ( empty($leagues) ) { // Se não existir nenhuma
			    		
			    		self::$events[$keyCountry]['leagues'][] = self::$league;
						self::$events[$keyCountry]['leagues'][0]['matches'][] = self::$match;
						return;

			    	} else { // Se existir uma liga

			    		while( $league = current($leagues) ) {

			    			$keyLeague = key($leagues);

			    			if ( $league['idleague'] == self::$league['idleague'] ) {

			    				self::$events[$keyCountry]['leagues'][$keyLeague]['matches'][] = self::$match;
			    				return;
			    			}

			    			next($leagues);
			    		}

			    		$keyLeague++;

			    		self::$events[$keyCountry]['leagues'][$keyLeague] = self::$league;
			    		self::$events[$keyCountry]['leagues'][$keyLeague]['matches'][] = self::$match;
			    		return;
			    	}

			    }
			    
			    next($countries);
			}
			
			/*
			self::$events[$keyCountry][] = self::$country;
			self::$events[$keyCountry]['leagues'][] = self::$league;
			self::$events[$keyCountry]['leagues']['matches'][] = self::$match;
			*/
			
		}

		//var_dump( count() );
	}

}

?>