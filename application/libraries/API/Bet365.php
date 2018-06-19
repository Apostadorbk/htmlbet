<?php 

// define("DS", DIRECTORY_SEPARATOR);

define("BET365PATH", __DIR__.DS.'Bet365');

/*
define("BET365_LIBRARY", [
	'League'		=> BET365PATH.DS.'League.php',
	'Event'			=> BET365PATH.DS.'Event.php',
	'Result'		=> BET365PATH.DS.'Result.php',
	'UpcomingEvent'	=> BET365PATH.DS.'UpcomingEvent.php',
	'CacheEvent'	=> BET365PATH.DS.'CacheEvent.php'
]);
*/

require_once BET365PATH.DS.'League.php';
require_once BET365PATH.DS.'Event.php';
require_once BET365PATH.DS.'Odd.php';
require_once BET365PATH.DS.'Result.php';
require_once BET365PATH.DS.'UpcomingEvent.php';
require_once BET365PATH.DS.'CacheEvent.php';


define("TYPE_REQUEST", [
	'upcoming'		=> "upcoming?",
	'prematchodd'	=> "start_sp?",
	'result'		=> "result?"
]);

define("TYPE", [
	'UPCOMING'		=> 1,
	'PREMATCHODD'	=> 2,
	'RESULT'		=> 3
]);

class Bet365 {

	private $token;
	private $url		= "Bet365/";
	private $type 		= NULL;
	private $status		= 0;
	// private $error 		= true;
	private $msgError 	= NULL;

	// Variaveis do upcoming
	private $sportID 	= '1';
	private $totalPage	= 0;
	private $perPage 	= 0;
	private $page 		= 0;


	private $results	= NULL;
	private $response 	= NULL;

	public function __construct(string $token) {
		// $this->url = "https://api.betsapi.com/v1/bet365/";
		$this->token = $token;
	}

	public function setPager(int $page = 0, int $perPage = 0, int $totalPage = 0) { // OK
		$this->page 		= ($page > 0) 		? $page 		: $this->page;
		$this->perPage 		= ($perPage > 0) 	? $perPage 		: $this->perPage;
		$this->totalPage 	= ($totalPage > 0) 	? $totalPage 	: $this->totalPage;
	}


	public function request(string $type = ''):Bet365 {

		$this->type = NULL;
		$this->url 	= "Bet365/";

		switch ($type) {

			case 'upcoming':

				$this->type = TYPE['UPCOMING'];
				$this->nextPage();
				$this->url .= 'Upcoming-Events'.$this->page;

			break;
			
		}

		$file = new Json($this->url);
		$this->response = $file->readJson();



		// var_dump( $this->url );
		// var_dump( $this->response );
		
		/*
		$this->url .= TYPE_REQUEST[$type];

		foreach ($params as $key => $value) {
			$this->url .= "{$key}={$value}&";
		}

		$this->url .= "token=".$this->token;
		*/
		
		//exit;

		// $file = new Json('Bet365/Upcoming-Events'.$this->page);
		// $data = $file->getVars();
		
		
		// Teste
		//$data = file_get_contents('https://betsapi.com/docs/samples/bet365_upcoming.json');

		/*
		$ch = curl_init($this->url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$data = curl_exec($ch);
		curl_close($ch);
		*/

		//$this->response = ($data) ? $data : NULL;
		
		$this->prepare();

		return $this;

	}

	private function prepare() {

		$this->array();

		$this->results 	= NULL;
		$this->msgError	= NULL;

		if ( isset($this->response) ) {

			$this->status = (int) $this->response['success'];


			if ( !$this->status ) {

				// $this->error 	= true;
				$this->msgError = $this->response['error'];

			} else {

				// $this->error 	= false;

				switch ($this->type) {

					case TYPE['UPCOMING']:

						$this->setPager(
							$this->response['pager']['page'],
							$this->response['pager']['per_page'],
							$this->response['pager']['total']
						);

						$this->results = $this->response['results'];

					break;

				}
				
			}
			
			// Desaloocando a resposta da API
			$this->response = NULL;

		}


	}

	public function nextPage() { // OK
		$this->setPager($this->page + 1);
	}

	public function status():bool { // OK
		return $this->status;
	}

	public function getResults() { //OK
		return $this->results;
	}

	public function array() { // OK

		if ( isset($this->response) ) {

			if ( gettype($this->response) == 'string' ) {
				$this->response = json_decode($this->response, true);
			}

			return $this->response;
		}
		
		return false;
	}

	public function json() { // OK

		if ( isset($this->response) ) {
			
			if ( gettype($this->response) == 'array' ) {
				$this->response = json_encode($this->response);
			}

			return $this->response;

		}
		
		return false;

	}

	// Instanciando um objeto league
	public function league() {
		return new League();
	}
	
	// Instanciando um objeto event
	public function event() {
		return new Event();
	}
	
	// Instanciando um objeto odd
	public function odd() {
		return new Odd();
	}

	// Instanciando um objeto odd
	public function upcomingEvent(string $time) {
		return new UpcomingEvent($time);
	}

	// Instanciando um objeto result
	public function result() {
		return new Result();
	}

	public function cacheEvent(string $file) {
		return new cacheEvent($file);
	}

	/*
	private function getLibrary(string $library = '') {
		if ( isset(BET365_LIBRARY[$library]) ) {
			require_once BET365_LIBRARY[$library];
		} else {
			throw new \Exception("Library {$library} not found", 100);
		}
	}
	*/

	public function teste() {

		var_dump( BET365PATH.DS.'League.php' );
		var_dump( BET365PATH.DS.'Event.php' );
		var_dump( BET365PATH.DS.'Odd.php' );
		var_dump( BET365PATH.DS.'Result.php' );
		
	}
}


?>