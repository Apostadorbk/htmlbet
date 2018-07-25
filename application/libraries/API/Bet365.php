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
require_once BET365PATH.DS.'PrematchOdd.php';


define("TYPE_REQUEST", [
	'UPCOMING'		=> "upcoming?",
	'PREMATCHODD'	=> "start_sp?",
	'RESULT'		=> "result?"
]);

define("TYPE", [
	'UPCOMING'		=> 1,
	'PREMATCHODD'	=> 2,
	'RESULT'		=> 3
]);

class Bet365 {

	private $token;
	private $url		= NULL;
	private $type 		= NULL;
	private $status		= false;	// Status da requisição
	private $error 		= false;	// Se ocorreu um error após a requisição
	private $msgError 	= NULL;
	private $required	= false; 	// Os parametros da requisição está prontas

	// Variaveis do upcoming
	private $sportID 	= '1';
	private $totalPage	= 5000;
	private $perPage 	= 50;
	private $page 		= 0;


	private $results	= NULL;
	private $response 	= NULL;

	public function __construct(string $token) {
		// $this->url = "https://api.betsapi.com/v1/bet365/";
		$this->token = $token;
	}

	public function setPager(int $page = 0, int $perPage = 50, int $total = 5000) { // OK
		$this->perPage 		= ($perPage > 0) 	? $perPage 							: $this->perPage;
		$this->totalPage 	= ($total > 0) 		? (int) ceil($total/$this->perPage) : $this->totalPage;
		$this->page 		= ($page > 0) 		? $page 							: $this->page;
	}


	public function request(string $type = '', array $params = []):Bet365 {

		// -------------------------------------------------------------------
		// VARIAVEIS 

		// $this->url 		= "Bet365/";
		$this->url 		= "https://api.betsapi.com/v1/bet365/";
		$this->type 	= NULL;
		$this->msgError = NULL;
		$this->response = NULL;
		$this->status 	= false;
		$this->error 	= false;

		$parameters 			= [];
		// $parameters['token'] 	= Constant::APIKEY;

		// var_dump( $parameters );
		// exit;

		// -------------------------------------------------------------------

		switch ($type) {

			case 'upcoming':

				$this->type 	= TYPE['UPCOMING'];

				if( !$this->nextPage() ) {
					$this->error 	= true;
					$this->msgError = "EXCEEDED PAGE LIMIT";
				} 
				// $this->url 	.= 'Upcoming-Events'.$this->page;
				$this->url 	.= TYPE_REQUEST['UPCOMING'];
				
				$parameters['sport_id'] = '1';
				$parameters['page'] 	= $this->page;

			break;

			case 'prematchodd':

				$this->type 	= TYPE['PREMATCHODD'];

				if ( !isset($params['idevent']) ) {

					$this->error 	= true;
					$this->msgError = "PARAMETER 'idevent' REQUIRED";

				} else {

					if ( $params['idevent'] <= 0 ) {
						$this->error 	= true;
						$this->msgError = "PARAMETER 'idevent' INVALID";
					}

				}

				// $this->url .= 'Prematch-Odds';
				$this->url 	.= TYPE_REQUEST['PREMATCHODD'];

			break;

			default:
				$this->error 	= true;
				$this->msgError = "REQUEST TYPE INVALID";
			break;
			
		}

		if ( !$this->error ) {

			// --------------------------------------------
			// Teste
			/*
			$file = new Json($this->url);
			
			if( !($this->response = $file->readJson()) ) {

				$this->error 	= true;
				$this->msgError = "FILE NOT FOUND";
				$this->response = NULL;

			} else {
				$this->error 	= false;
				$this->status 	= true;
			}
			*/
			// --------------------------------------------


			foreach ($parameters as $key => $value) {
				$this->url .= "{$key}={$value}&";
			}

			$this->url .= "token=".Constant::APIKEY;

			// var_dump( $this->url );
			// exit;

			$ch = curl_init($this->url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$data = curl_exec($ch);
			curl_close($ch);

			if ( !$data ) {
				$this->error 	= true;
				$this->msgError = "REQUEST FAILED";
			} else { 
				$this->status 	= true;
				$this->response = $data; 
			}

			$this->prepare();

		}


		// var_dump( $this->url );
		// var_dump( $this->response );
		
		
		// $this->url .= TYPE_REQUEST[$type];

		
		
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

		/*
		if ( !$data ) {
			$this->error 	= true;
			$this->msgError = "REQUEST FAILED";
		} else { 
			$this->status 	= true;
			$this->response = $data; 
		}
		*/

		//$this->response = ($data) ? $data : NULL;


		return $this;

	}

	public function required():bool {
		return !$this->required;
	}

	public function error():bool {
		return $this->error;
	}

	public function success():bool {
		return !$this->error;
	}

	public function getError():string {
		return $this->msgError ?? "NO ERROR";
	}

	private function prepare():bool {

		if ( !$this->status() || $this->error  ) return false;

		if ( !$this->array() ) 	return false;

		/*
		var_dump( $this->response );
		exit;
		*/

		// ----------------------------------------------------------

		$this->error 	= !((bool) $this->response['success']);
		$this->msgError	= NULL;

		if ( $this->error ) {
			$this->msgError = $this->response['error'];
			return false;
		}

		// ----------------------------------------------------------

		$this->results 	= NULL;

		switch ($this->type) {

			case TYPE['UPCOMING']:

				$this->setPager(
					$this->response['pager']['page'],
					$this->response['pager']['per_page'],
					$this->response['pager']['total']
				);

				$this->results = $this->response['results'];

			break;

			case TYPE['PREMATCHODD']:

				$this->results = $this->response['results'];

				// echo 'Lido com sucesso!';
				// echo '<hr>';

			break;

		}

		// ----------------------------------------------------------
		
		// Desaloocando a resposta da API
		$this->response = NULL;	

		return true;

	}

	public function nextPage():bool { // OK

		if ( ($this->page + 1) > $this->totalPage ) {
			return false;
		}
		
		$this->setPager($this->page + 1);

		return true;

	}

	public function status():bool { // OK
		return $this->status;
	}

	public function getResults() { //OK
		return $this->results;
	}

	public function array() { // OK

		if ( !isset($this->response) || empty($this->response) ) return false;
		
		if ( gettype($this->response) == 'string' ) {
			$this->response = json_decode($this->response, true);
		}

		return $this->response;
	
	}

	public function json() { // OK

		if ( !isset($this->response) || empty($this->response) ) return false;
		
		if ( gettype($this->response) == 'array' ) {
			$this->response = json_encode($this->response);
		}

		return $this->response;


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

	// Instanciando um objeto Prematch Odds
	public function prematchOdd(array $idevents) {
		return new PrematchOdd($idevents);
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