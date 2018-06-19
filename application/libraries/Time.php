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

	private $time;

	public function __construct(string $start, string $final = 'America/Sao_Paulo') {
		$this->setDiffTimeZone($start, $final);
	}

	private function setDiffTimeZone(string $start, string $final = 'America/Sao_Paulo') {
		$this->diffTimeZone(
			new DateTime('now', new DateTimeZone($start)), 
			new DateTime('now', new DateTimeZone($final))
		);
	}

	private function diffTimeZone(DateTime $start, DateTime $final) { // OK
		$this->offset_start = $start->getOffset();
		$this->offset_final = $final->getOffset();

		$this->offset = $this->offset_start - $this->offset_final;
	}

	public function convert(int $timestamp):Time { // OK

		$this->time = $timestamp - $this->offset;

		return $this;
	
	}

	public function format(string $format = 'Y-m-d H:i:s'):string {
		return date($format, $this->time);
	}

	public function getTime():int {
		return $this->time;
	}

	public static function currentDate(string $format = 'Y-m-d H:i:s', string $local = 'America/Sao_Paulo'):string {

		$date 			= new DateTime('now', new DateTimeZone($local));

		$localOffset 	= $date->getOffset();


		$systemDate 	= gettimeofday();

		$systemOffset 	= $systemDate['minuteswest']*60;

		$offset 		= $systemOffset + $localOffset;


		$localTimestamp = $systemDate['sec'] + $offset;

		return date($format, $localTimestamp);

	}

	public static function isHour(int $hour):bool {

		$currentHour 	= self::currentDate('G');

		// var_dump( $currentHour );

		if ( $currentHour == "{$hour}" ) {
			return true;
		} else {
			return false;
		}

	}

}

?>