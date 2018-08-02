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
	private $timeStampOffsetted;

	private $time;

	public function __construct(string $timeZone = 'America/Sao_Paulo') {
		$this->timeZone = $timeZone;
		date_default_timezone_set($timeZone);
	}

	public function time(string $time = 'now') {
		$date 				= new DateTime('now', new DateTimeZone($this->timeZone));

		$localOffset 		= $date->getOffset();

		$systemDate 		= gettimeofday();

		$systemOffset 		= $systemDate['minuteswest']*60;

		$offset 			= $systemOffset + $localOffset;

		$this->timeStamp 	= $systemDate['sec'] + $offset;

		$this->timeStampOffsetted = strtotime($time, $this->timeStamp);

		return $this;
	}

	public function format(string $format = 'Y-m-d H:i:s'):string {
		return date($format, $this->timeStampOffsetted);
	}

	public function get(string $time = 'now'):int {
		return ($time == 'now') ? 
			$this->timeStampOffsetted : 
			strtotime( $time, $this->timeStampOffsetted );
	}

	public function interval(string $lastTime, string $time, string $errorMargin = ''):bool {
		
		if ( empty($time) || empty($lastTime) ) return false;

		$_last 		= strtotime( $lastTime );
		$nextTime 	= strtotime( $time, $_last );
		$margin = [
			'top' 		=> $nextTime,
			'bottom' 	=> $nextTime
		];

		if ( !empty($errorMargin) ) {
			$margin['top'] = strtotime( "-".$errorMargin, $nextTime );
			$margin['bottom'] = strtotime( $errorMargin, $nextTime );
		}

		/*
		var_dump( 
			date('Y-m-d H:i:s', $_last),
			date('Y-m-d H:i:s', $nextTime),
			date('Y-m-d H:i:s', $margin['top']),
			date('Y-m-d H:i:s', $margin['bottom'])
		);
		echo '<hr>';
		*/
		
		if ( 
			$this->timeStampOffsetted >= $margin['top']
			&&
			$this->timeStampOffsetted <= $margin['bottom']
		) {
			return true;
		} else {
			return false;
		}

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