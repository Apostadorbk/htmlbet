<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API extends BASE_Controller {

	private $api;

	public function __construct(){
		parent::__construct("API");

		$this->getLibrary([
			'Bet365', 
			'Time', 
			'Constant',
			'Database',
			'Json'
		]);

		$this->getModel([
			'Event',
			'League'
		]);

		$this->api = new Bet365('Token de teste');
	}

	/*
	*	Função _remap
	*
	*	Aplicar uma camada de segurança ao selecionar a rota
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $params é o caminho da rota
	*	
	*/
	public function _remap($method, $params = []) {

		// Se o método não existe
		if(!method_exists($this, $method)) {

            show_404();

        } else {

			switch ($method) {

				// Função padrão
				case 'index':
					$this->index();
				break;

				// Função padrão
				case 'upcoming':
					$this->upcoming();
				break;


				default:
					$this->teste();
				break;
			}

        }

	}
	
	
	public function index()
	{
		
		$t = new Time('Europe/London');

		//var_dump( $t->convert(1471039200)->format() );
		
		$this->api->request('https://betsapi.com/docs/samples/bet365_upcoming.json');

		$result = $this->api->array();
		

		// ----------------------------------------------------------------

		$ready_league 	= $this->cache('Bet365/League/Ready-League');
		$unready_league = $this->cache('Bet365/League/Unready-League');

		$ready_league->getVars();


		$new_leagues = [];

		foreach ($result['results'] as $match) {

			$id 	= $match['league']['id'];
			$name 	= $match['league']['name'];


			if ( !$ready_league->hasValue($id) ) {
				
				$new_leagues[$id] = [
					'id' 	=> $id,
					'name' 	=> $name
				];

			}

		}
	
		$unready_league->setVars($new_leagues);

		// ----------------------------------------------------------------
		
		echo '<pre>';
		var_dump( $unready_league->getVars() );
		echo '</pre>';
		

	}

	public function teste() {

		echo '<pre>';
		$start = microtime(true);

		
		$m = $this->model('League');

		$m->setLeague(['idleague']);
		
		var_dump( $m->getValues() );


		
		$t = new Time('Europe/London');

		var_dump( $t->convert(1490799600)->format() );
		var_dump( $t->convert(strtotime('+2 hours', 1490799600))->format() );
		


		// var_dump( Time::isHour(12) );

		

		// $this->api->request('prematchodd', ['event_id' => 12345678]);

		$time_elapsed_secs = microtime(true) - $start;
		echo '<hr>';
		echo 'Time Elapsed: ';
		var_dump( $time_elapsed_secs );
		echo '</pre>';

	}

	public function upcoming() { // OK

		// echo '<pre>';

		$intervalHours 	= "+2 hours";
		$midnight 		= false;

		if ( Time::isHour(0) ) {
			$intervalHours 	= "+24 hours";
			$midnight 		= true;
		}
		
		// Teste
		// $midnight = false;
		// $intervalHours = "+2 hours";
		// -------------------------


		// Requisição da pagina
		$this->api->request('upcoming');

		
		// Requisição com sucesso
		if ( $this->api->status() ) {

			$upcoming = $this->api->upcomingEvent($intervalHours);


			while ( $upcoming->readMatch( $this->api->getResults() ) ) {

				$this->api->request('upcoming');

				if ( !$this->api->status() ) break;

			}

			// --------------------------------------------------------

			if ( $upcoming->hasMatch() ) {

				$leagueModel = $this->model('League');

				$leagueModel->setLeague(['idleague']);
				

				$upcoming->prepare(
					$leagueModel->getValues()
				);


				// Salvar no BD tb_leagues oa jogos permitidos
				if ( $upcoming->hasAllowed() ) {

					$eventModel = $this->model('Event');

					( $midnight ) ? $eventModel->setUpcoming($upcoming->getAllowed()) : $eventModel->updateUpcoming($upcoming->getAllowed());

				}

				// Salvar no arquivo os jogos não permitidos
				if ( $upcoming->hasUnallowed() ) {
					
					// Salvar as partidas nao permitidas em Bet365/Event/Unallowed-Upcoming-Event
					$upcoming->saveUnallowed('Bet365/Event/Unallowed-Upcoming-Event');

				}

			}

		}
		
		// echo '</pre>';

	}

	public function prematchodds() {
		
	}

	public function result() {

	}


}
