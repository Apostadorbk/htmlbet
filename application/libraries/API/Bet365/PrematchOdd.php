<?php 
/*
	Está classe fará toda logica que usa ligas e com os arquivos cache
*/

/*
	Upcoming Events
	sport_id	Yes	R-SportID
	league_id	No	useful when you want only one league
	day			No	format YYYYMMDD, eg: 20161201
	page		No	R-Pager
*/
	
// TS = TimeStamp
class PrematchOdd {

	// Dados das Odds
	private $odd 		= NULL;
	private $idevent	= 0;
	private $asianLines = [];
	private $goals		= [];
	private $half 		= [];
	private $main 		= [];


	
	// Dados dos eventos para buscar as odds
	private $events 	= NULL;
	private $status 	= []; // Resposta da API para cada requisição do evento
	private $unreceived = [];
	private $received	= [];
	private $length 	= 0;
	private $index 		= -1;

		

	// now = é a hora exata da requisição
	// time é até a hora maxima dos jogos a ser requisitados
	public function __construct(array $events) { // OK

		$this->odd 			= []; 
		$this->index 		= -1; 
		$this->events 		= $events; 
		$this->length 		= isset($this->events) ? count($this->events) : 0; 
		$this->status 		= [];
		$this->unreceived 	= [];
		$this->received 	= [];


		// var_dump( $this->events );
		// var_dump( $this->length );

	}

	public function nextEvent() { // OK

		if ( empty($this->events) ) return false;

		$this->index = ($this->index < 0) ? -1 	: $this->index;

		$this->index++;

		$this->index = ($this->index >= $this->length) ? $this->length : $this->index;

		return ( $this->index >= 0 && $this->index < $this->length ) ? $this->events[$this->index] : false;

	}

	//
	public function readOdd(array $odd):bool { // OK

		$this->odd = [];

		if ( empty($odd) ) return false;

		$this->odd 			= $odd[0];

		$this->idevent 		= $this->odd['FI']			??  0;
		$this->asianLines 	= $this->odd['asian_lines'] ?? [];
		$this->goals 		= $this->odd['goals'] 		?? [];
		$this->half 		= $this->odd['half'] 		?? [];
		$this->main 		= $this->odd['main'] 		?? [];
		
		$this->odd 			= [];

		$this->prepare();

		return true;

	}

	public function prepare():bool { // OK

		if ( $this->idevent <= 0 ) return false;

		

		return true;

	}

	public function getEvent() { // OK

		if ( empty($this->events) ) return NULL;

		return ($this->index >= 0 && $this->index < $this->length) ? $this->events[$this->index] : NULL;
	
	}

	public function setStatus(bool $status = true):bool { // OK
		
		if ( $this->index < 0 || $this->index >= $this->length ) return false;

		if ( $status ) {
			$this->received[] 	= $this->getEvent();
		} else {
			$this->unreceived[] = $this->getEvent();
		}

		$this->status[$this->index] = $status;

		return true;
	
	}

	public function getStatus():bool { // OK

		if ( $this->index < 0 || $this->index >= $this->length ) return false;

		return $this->status[$this->index] ?? false;

	}

	public function getReceived():array {
		return $this->received;
	}

	public function getUnreceived():array {
		return $this->unreceived;
	}

	public function hasOdd():bool {
		return isset($this->odd);
	}

}

?>