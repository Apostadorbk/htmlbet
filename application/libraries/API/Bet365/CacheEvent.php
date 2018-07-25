<?php 
/*
	Está classe fará toda logica que usa ligas e com os arquivos cache
*/

require_once APPPATH.DS.'libraries'.DS.'Json.php';
require_once APPPATH.DS.'models'.DS.'Event_model.php';

use Event_model AS Event;

// TS = TimeStamp
class CacheEvent extends Json {

	private $event;

	public function __construct(string $file) { // OK

		parent::__construct("Bet365/Event/".$file);

		$this->event = new Event();

		// var_dump( $this->event );
	
	}
	
	public function update(string $time = '+2 hours'):bool { // OK
		
		// echo 'Cache Allowed'.'<br>';

		if ( empty($time) || !isset($time) ) return false;
	
		$start = $this->getDate('now');
		$final = $this->getDate($time);

		// Teste
		// $start = '2017-03-29 13:00:00';
		// $final = '2017-03-29 15:00:00';
		// -----------------------------

		$this->setVar("start", $start);
		$this->setVar("final", $final);

		if ( 
			!$this->event->setEventByDate( $start, $final )
		) {

			$this->setVar("status", false);
			$this->setVar("result", []);

			return false;
		}

		$this->setVar("status", true);
		$this->setVar("result", $this->event->getValues());

		return true;

	}

}

?>