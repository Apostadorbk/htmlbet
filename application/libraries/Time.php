<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*	Classe Auth
*
*	classe responsavel de manipular quem tá autenticado
*/

define("TIMEZONE", array(
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
	'TO' => 'America/Araguaia'  
));

class Time {

	private $offset_start;
	private $offset_final;
	private $offset;

	private $date;
	private $format;
	private $time;

	public function __construct(string $start, string $final = 'America/Sao_Paulo') {
		$this->diffTimeZone(
			new DateTime('now', new DateTimeZone($start)), 
			new DateTime('now', new DateTimeZone($final))
		);
	}

	public function diffTimeZone(DateTime $start, DateTime $final) { // OK
		$this->offset_start = $start->getOffset();
		$this->offset_final = $final->getOffset();

		$this->offset = $this->offset_start - $this->offset_final;
	}

	public function getTimeOf(int $timestamp, string $format = 'Y-m-d H:i:s'):string { // OK

		$this->time = $timestamp - $this->offset;

		return date($format, $this->time);
	
	}

	public function getTime():int {
		return $this->time;
	}

	public function setFormat(string $format) {
		$this->$format = $format;
	}

	/*
	public function setTimeZone($uf) {
		return date_default_timezone_set(TIMEZONE[$uf]);
	}
	*/

	/*
	public function getDate($input = []) { // OK

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

				$this->dateInterval = new DateInterval($interval_spec);

				if ( isset($interval['sub']) && !empty($interval['sub']) ) {

					if ($interval['sub'])
						$date->sub($this->dateInterval);

				} else {
					$date->add($this->dateInterval);
				}

			}

			return $date->format($format);

		}

	}
	*/

}

?>