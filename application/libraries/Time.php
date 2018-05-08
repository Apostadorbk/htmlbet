<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*	Classe Auth
*
*	classe responsavel de manipular quem tá autenticado
*/
class Time {
	
	private static $timezones = array(
		'AC' => 'America/Rio_branco',   
		'AL' => 'America/Maceio',
		'AP' => 'America/Belem',        
		'AM' => 'America/Manaus',
		'BA' => 'America/Bahia',        
		'CE' => 'America/Fortaleza',
		'DF' => 'America/Sao_Paulo',    
		'ES' => 'America/Sao_Paulo',
		'GO' => 'America/Sao_Paulo',    
		'MA' => 'America/Fortaleza',
		'MT' => 'America/Cuiaba',       
		'MS' => 'America/Campo_Grande',
		'MG' => 'America/Sao_Paulo',    
		'PR' => 'America/Sao_Paulo',
		'PB' => 'America/Fortaleza',    
		'PA' => 'America/Belem',
		'PE' => 'America/Recife',       
		'PI' => 'America/Fortaleza',
		'RJ' => 'America/Sao_Paulo',    
		'RN' => 'America/Fortaleza',
		'RS' => 'America/Sao_Paulo',    
		'RO' => 'America/Porto_Velho',
		'RR' => 'America/Boa_Vista',    
		'SC' => 'America/Sao_Paulo',
		'SE' => 'America/Maceio',       
		'SP' => 'America/Sao_Paulo',
		'TO' => 'America/Araguaia',     
	);

	public static function setTimeZone($state) {
		return date_default_timezone_set(self::$timezones[$state]);
	}

	public static function getDate($input = []) { // OK

		$date = '';
		$format = 'Y-m-d H:i:s';
		$output = '';

		if ( empty($input) ) {
			return (new DateTime("now"))->format($format);
		} else {

			$format = (isset($input['format']) && !empty($input['format'])) ? $input['format'] : $format;

			if ( isset($input['date']) && !empty($input['date']) ) {
				$date = new DateTime($input['date']);
			} else {
				$date = new DateTime('now');
			}

			if ( isset($input['interval']) && !empty($input['interval']) ) {

				$interval = $input['interval'];

				$interval_spec = 'P' . ((isset($interval['year'])) ? $interval['year'] : 0) . 'Y';

				$interval_spec .= ((isset($interval['month'])) ? $interval['month'] : 0) . 'M';

				$interval_spec .= ((isset($interval['week'])) ? $interval['week'] : 0) . 'W';

				$interval_spec .= ((isset($interval['day'])) ? $interval['day'] : 0) . 'D';

				$interval_spec .= 'T' . ((isset($interval['hour'])) ? $interval['hour'] : 0) . 'H';

				$interval_spec .= ((isset($interval['minute'])) ? $interval['minute'] : 0) . 'M';

				$interval_spec .= ((isset($interval['second'])) ? $interval['second'] : 0) . 'S';

				$interval_time = new DateInterval($interval_spec);

				if ( isset($interval['sub']) && !empty($interval['sub']) ) {

					if ($interval['sub'])
						$date->sub($interval_time) ;

				} else {
					$date->add($interval_time);
				}

			}

			return $date->format($format);

		}

	}

}

?>