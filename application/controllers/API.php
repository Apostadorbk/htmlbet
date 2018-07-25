<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API extends BASE_Controller {

	private $api;

	public function __construct(){
		parent::__construct("API");

		$this->getLibrary([
			'Bet365', 
			'Time',
			'TimeZone',
			'Constant',
			'Database',
			'Json'
		]);

		$this->getModel([
			'Event',
			'League',
			'Country'
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

				// Função padrão
				case 'prematchodd':
					$this->prematchodd();
				break;

				case 'updatecountry':
					$this->updatecountry();
				break;

				default:
					$this->teste();
				break;
			}

        }

	}
	
	
	public function index() {
		
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
		echo '<hr>';

		$t = new Json("Bet365/League/Unallowed-League");

		var_dump( $t->getVar('league') );


		echo '<hr>';
		$time_elapsed_secs = microtime(true) - $start;
		echo 'Time Elapsed: ';
		var_dump( $time_elapsed_secs );
		echo '</pre>';

	}

	public function upcoming() { // OK

		echo '<pre>';

		$intervalHours 	= "+2 hour";
		$midnight 		= false;

		if ( Time::isHour(0) ) {
			$intervalHours 	= "+48 hours";
			$midnight 		= true;
		}
		
		// Teste
		$midnight 		= true;
		$intervalHours 	= "+24 hours";
		// -------------------------

		$upcoming = $this->api->upcomingEvent($intervalHours);


		// Requisição da pagina
		$this->api->request('upcoming');

		// var_dump( $this->api->getResults() );

		// $upcoming->readMatch( $this->api->getResults() );

		//$this->api->request('upcoming');

		//var_dump( $this->api->getResults() );

		// exit;

		// Requisição com sucesso
		if ( $this->api->status() ) {

			// Lendo todas as partidas requisitadas dentro do intervalo
			while ( $upcoming->readMatch( $this->api->getResults() ) ) {

				$this->api->request('upcoming');

				if ( !$this->api->status() ) break;

			}

			// var_dump( $upcoming );
			// exit;

			// --------------------------------------------------------


			if ( $upcoming->hasMatch() ) {

				// var_dump( 'Teste' );

				$leagueModel = $this->model('League');

				$leagueModel->setLeague(['idmyleague', 'idleague', 'desmyleague']);
				

				$leagues = [];

				$leagues = $leagueModel->getValues(['idmyleague', 'idleague', 'desmyleague']);
				
				if ( !$leagues ) $leagues = [];

				$upcoming->prepare(
					$leagues
				);

				// Salvar no BD tb_leagues oa jogos permitidos
				if ( $upcoming->hasAllowed() ) {

					$eventModel = $this->model('Event');

					if ( $midnight ) {
						// var_dump( $upcoming );

						// Salvar no BD
						$eventModel->setEvent($upcoming->getAllowed());

					} else {

						// Atualizar os jogos existente
						$eventModel->updateEvent($upcoming->getAllowed());

					}

					echo 'EVENTOS PERMITIDOS'.'<br>';
					var_dump( $upcoming->getAllowed() );
					echo '<hr>';
					echo '<hr>';

					// Atualizar o cache
					/*
					$cache = $this->api->cacheEvent('Next-Events');

					$cache->update(
						$intervalHours
					);
					*/

				}

				// Salvar no arquivo os jogos não permitidos
				
				if ( $upcoming->hasUnallowed() ) {

					echo 'EVENTOS NÃO PERMITIDOS'.'<br>';
					var_dump( $upcoming->getUnallowed() );
					echo '<hr>';
					echo '<hr>';
					
					// Salvar as partidas nao permitidas em Bet365/Event/Unallowed-Upcoming-Event
					$upcoming->saveUnallowed('Bet365/League/Unallowed-League');

				}
				

			}

		}
		
		echo '</pre>';

	}

	public function prematchodd() {
		
		echo '<pre>';

		$eventModel 	= $this->model('Event');
		
		$intervalHours 	= "+24 hours";
		$midnight 		= false;

		if ( Time::isHour(0) ) {
			$intervalHours 	= "+72 hours";
			$midnight 		= true;
		}

		$startTime 		= Time::date('now');
		$finalTime		= Time::date($intervalHours);

		/*
			1. Pegar todas as partidas em um intervalo
				A cada hora atualizo as odds de todos proximos jogos em um intervalo de 24h;
				A cada meia noite eu atualizo as odds dos 3 próximo dias

		*/


		// ------------------------------------------------------------------
		// Teste
		$startTime		= '2017-03-29 13:00:00';

		// tempo final correspodente ao intervalo somado com a data inicial
		$finalTime		= '2017-03-30 13:00:00';
		
		// ------------------------------------------------------------------
				
		/*
		var_dump ( 
			$eventModel,
			$startTime,
			$finalTime
		);
		*/

		// Buscar todos os IDs dos próximos jogos dentro do intervalo
		if ( $eventModel->field('idevent')->setEventByDate($startTime, $finalTime) ) {

			// instanciar prematchodd
			$odd = $this->api->prematchOdd(
				$eventModel->getValues('idevent')
			);


			// var_dump( $odd );

			// OK

			// Buscar as Odds partida por partida
			while ( $idevent = $odd->nextEvent() ) {
				
				// Se recebeu a resposta do Betapi
				if ( $this->api->request('prematchodd', ['idevent' => $idevent])->success() ) {
					// $match = $this->api->getResults();

					// Tratar a resposta do servidor
					$odd->readOdd( $this->api->getResults() );

					/*
					if ( $match[0]['FI'] == $idevent ) {
						var_dump( $match );
					}
					*/

				} else {
					var_dump( "Não sucedido: ".$idevent );
				}

				echo '<hr>';

			}

		}










		/*
		echo '<hr>';

		if ( $eventModel->field('idevent')->setEventByDate($startTime, $finalTime) ) {

			// instanciar prematchodd
			$odd = $this->api->prematchOdd(
				$eventModel->getValues('idevent')
			);

			echo '<hr>';

			// ---------------------------------------------------------
		*/
			/*
			var_dump( 
				$this->api->request('prematchodd', ['idevent' => 0])->getError()
			);
			*/

			/*
			$i = 0;

			// while ( (
				$idevent = $odd->nextEvent();
			// ) ) {
				
				$odd->setStatus(
					$this->api->request('prematchodd', ['idevent' => $idevent])->success()
				);

				// var_dump( $this->api->getResults() );
				// echo '<hr>';

				
				if ( $odd->getStatus() ) {
					$odd->readOdd( 
						$this->api->getResults()
					);
				}
				*/
			// }
			
			/*
			var_dump( $odd->getReceived() );
			echo '<hr>';

			var_dump( $odd->getUnreceived() );
			echo '<hr>';
			*/
		
		//}
		



		echo '</pre>';
	
	}

	public function result() {

	}

	// Ler os países adicionados em Bet365/Country/Country.json
	public function updatecountry() { // OK

		$file = new Json('Bet365/Country/Country');

		$update 		= $file->getVar('update');
		$countryFile 	= $file->getVar('country');

		if ( !$update ) {

			$countryModel = $this->model('Country');

			$countryModel->setCountry(['desmycountry']);


			if ( !($country = $countryModel->getValues('desmycountry')) ) {
				$country = [];
			}

			$newCountry = array_values(array_diff($countryFile, $country));

			if ( $countryModel->insertCountry($newCountry) ) {
				$file->setVar('update', true);
				$file->setVar('country', []);
			}

		}


	}


}
