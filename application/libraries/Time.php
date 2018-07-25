<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*	Classe Auth
*
*	classe responsavel de manipular quem tá autenticado
*/

class Time {

	private $timeZone;
	private $timeStamp;

	private $time;

	public function __construct(string $timeZone = 'America/Sao_Paulo') {
		$this->timeZone = $timeZone;
		date_default_timezone_set($timeZone);
	}

	public function time() {
		$date 				= new DateTime('now', new DateTimeZone($this->timeZone));

		$localOffset 		= $date->getOffset();

		$systemDate 		= gettimeofday();

		$systemOffset 		= $systemDate['minuteswest']*60;

		$offset 			= $systemOffset + $localOffset;

		$this->timeStamp 	= $systemDate['sec'] + $offset;

		return $this;
	}

	public function format(string $format = 'Y-m-d H:i:s'):string {
		return date($format, $this->timeStamp);
	}

	public function get(string $time = 'now'):int {
		return strtotime($time, $this->timeStamp);
	}

	public static function isDST(string $timeZone = 'America/Sao_Paulo') {

		$t = new Time($timeZone);
		
		return (bool) $t->time()->format('I');

	}

	public static function isHour(int $hour):bool {

		$t = new Time();

		$currentHour 	= $t->time()->format('G');

		// var_dump( $currentHour );

		if ( $currentHour == "{$hour}" ) {
			return true;
		} else {
			return false;
		}

	}

	/*
	public function currentDate(string $format = 'Y-m-d H:i:s'):string {

		return date($format, $this->currentTime($local));

	}


	public static function time(string $time = 'now', string $local = 'America/Sao_Paulo'):int {
		return strtotime($time, self::currentTime($local));
	}

	public static function date(string $time = 'now', string $format = 'Y-m-d H:i:s', string $local = 'America/Sao_Paulo'):string {
		return date($format, self::time($time, $local));
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
	*/

}

?>