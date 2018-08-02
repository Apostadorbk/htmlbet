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
			'Country',
			'Odd'
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

				/*
				case 'updatecountry':
					$this->updatecountry();
				break;
				*/

				case 'updateleague':
					$this->updateleague();
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

		/*
		echo '<pre>';
		$start = microtime(true);
		echo '<hr>';
		*/
		
		$t = $this->model('Odd');

		$t->setTypeOdd();

		var_dump( 
			$t->getValues(['idmytypeodd','desodd'])
		);

		/*
		ini_set('max_execution_time', 6000);
		

		$baseDir = "H:\\Programas\\xampp\\htdocs\\aposta\\application\\cache\\cache_json\\Bet365\\Odd\\Teste1";
		
		$allDir = scandir($baseDir);

		$json = file_get_contents($baseDir.'\\FI=75228278.json');

		echo $json;
		*/

		
		// $typeOdds = [];


		//for ($i=2; $i < count($allDir); $i++) {
			
			// $odds = json_decode(file_get_contents( $baseDir.'\\FI=75228278.json' ), true);

			// $keysOdds = array_keys($odds['odd']['results'][0]);

			// var_dump( $keysOdds );

			// $typeOdds[$i-2] = [];
			/*
			for ($j=2; $j < count($keysOdds); $j++) { 

				$typeOdds[0][$keysOdds[$j]] = array_keys($odds['odd']['results'][0][$keysOdds[$j]]['sp']);
			}
			*/
			
		//}


		//var_dump( $typeOdds );
		

		// $odds = json_decode($json, true);

		// var_dump( $odds );

		// var_dump( array_keys($odds['odd']['results'][0]) );

		/*

		// var_dump( $allDir );

		for ($i=2; $i < count($allDir); $i++) { 
			
			$json = file_get_contents($baseDir."\\".$allDir[$i]);
			
			$json = str_replace(['"{', '}"'], ['{', '}'], $json);

			file_put_contents($baseDir."\\".$allDir[$i], $json);

			// var_dump( json_decode($json, true) );

			// exit;
			
		}
		*/
		
		// for ($i = 2; $i < count($allDir); $i++) { 
		/*
		$FI = "FI=75097214";


		var_dump( json_decode($json, true) );
		*/

		// }
		
		

		/*
		$t = new Time();
		// $f = new Json("Bet365/Odd/Teste/FI=75097214");

		$eventModel 	= $this->model('Event');
		$intervalHours 	= "+144 hours";

		$startTime = $t->time()->format();
		$finalTime = $t->time($intervalHours)->format();

		var_dump( $startTime, $finalTime );

		$eventModel->field('idevent')->setEventByDate($startTime, $finalTime);

		$allEvents = $eventModel->getValues('idevent');

		// var_dump( $allEvents );
		*/


		/*
		
		foreach ($allEvents as $value) {
			
			$f->setDir("Bet365/Odd/Teste1/FI=".$value);

			$f->clearCache();

			$ch = curl_init("https://api.betsapi.com/v1/bet365/start_sp?token=4118-MkUMJ3jCQehsuu&FI=".$value);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$data = curl_exec($ch);


			if ( !$data ) {
				$this->error 	= true;
				$this->msgError = "REQUEST FAILED";
			} else { 
				$this->status 	= true;
				$this->response = $data; 
			}

			$f->setVar('odd', $data);

		}
		
		curl_close($ch);
		*/

		/*
		echo '<hr>';
		$time_elapsed_secs = microtime(true) - $start;
		echo 'Time Elapsed: ';
		var_dump( $time_elapsed_secs );
		echo '</pre>';
		*/
		

	}

	public function upcoming() { // OK

		echo '<pre>';

		$intervalHours 	= "+24 hours";
		$midnight 		= false;

		if ( Time::isHour(0) ) {
			$intervalHours 	= "+168 hours"; // Equivalente a 7 dias
			$midnight 		= true;
		}
		
		// Teste
		// $midnight 		= true;
		// $intervalHours 	= "+144 hours";
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

					/*echo 'EVENTOS PERMITIDOS'.'<br>';
					var_dump( $upcoming->getAllowed() );
					echo '<hr>';
					echo '<hr>';*/

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

					/*echo 'EVENTOS NÃO PERMITIDOS'.'<br>';
					var_dump( $upcoming->getUnallowed() );
					echo '<hr>';
					echo '<hr>';*/
					
					// Salvar as ligas nao permitidas 
					$upcoming->saveUnallowed('Bet365/League/League');

				}
				

			}

		}
		
		echo '</pre>';

	}

	public function prematchodd() {
		
		echo '<pre>';

		$this->getModel(['Time']);

		$t = $this->model('Time');

		$t->setTime(['destype', 'destime', 'dteupdate']);

		$response = $t->getValues(['destype', 'destime', 'dteupdate']);

		$type 		= $response['destype'];
		$interval 	= $response['destime'];
		$update 	= $response['dteupdate'];
 		$choose 	= [];

 		/*
		var_dump (
			$type, $interval, $update
		);
		*/

		$time = new Time('America/Sao_Paulo');

		$time->time();

		/*
		var_dump( 
			$time->interval( 
				'2018-08-01 17:51:17', 
				'1 hour', 
				'3 minutes'
			) 
		);
		*/
		
		for ($i=0; $i < count($type); $i++) {
			
			if ( 
				$time->interval( 
					$update[$i], 
					$interval[$i], 
					'10 minutes'
				)
			) {
				$choose[] = $type[$i];
				break;
			}

		}
		
		var_dump( $choose );

		foreach ($choose as $value) {
			var_dump( $t->updateTime($value) );
		}
		

		//$eventModel 	= $this->model('Event');
		
		/*
			A cada hora atualizo os jogos das próximas 6h,
			A cada 3 horas atualizo os jogos entre as 6h e 12h
			A cada 6 horas atualizo os jogos entre 12h e 18h
			A cada 12 horas atualizo os jogos entre 18h e 24h
		*/


		/*
		$intervalHours 	= "+24 hours";
		$midnight 		= false;

		if ( Time::isHour(0) ) {
			$intervalHours 	= "+72 hours";
			$midnight 		= true;
		}
		*/

		// $startTime 		= Time::date('now');
		// $finalTime		= Time::date($intervalHours);

		/*
			1. Pegar todas as partidas em um intervalo
				A cada hora atualizo as odds de todos proximos jogos em um intervalo de 24h;
				A cada meia noite eu atualizo as odds dos 3 próximo dias

		*/


		// ------------------------------------------------------------------
		// Teste
		// $startTime		= '2017-03-29 13:00:00';

		// tempo final correspodente ao intervalo somado com a data inicial
		// $finalTime		= '2017-03-30 13:00:00';
		
		// ------------------------------------------------------------------
				
		/*
		var_dump ( 
			$eventModel,
			$startTime,
			$finalTime
		);
		*/

		/*
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
					*/
					/*
					if ( $match[0]['FI'] == $idevent ) {
						var_dump( $match );
					}
					*/
					/*
				} else {
					var_dump( "Não sucedido: ".$idevent );
				}

				echo '<hr>';

			}

		}
		*/









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
	/*
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
	*/

	// Ler as ligas adicionados em Bet365/League/League.json
	public function updateleague() { // OK

		// echo '<pre>';

		$file = new Json('Bet365/League/League');

		$update 		= $file->getVar('update');

		if ( !$update ) {

			$leagueFile 	= $file->getVar('league');

			//$countryModel 	= $this->model('Country');
			$leagueModel 	= $this->model('League');

			$leagueModel->setLeague(['idleague']);


			// Os indices de idmycountry a patir da tabela league
			if ( !($idLeague = $leagueModel->getValues('idleague')) ) {
				$idLeague = [];
			}

			// Ligas para serem adicionadas
			$addLeague = [];
			// Ligas que ja foram adicionadas em addLeague
			$addedLeague = [];
			
			foreach ($leagueFile as $value) {

				$index 		= array_search($value['idleague'], $idLeague);
				$addedIndex = array_search($value['idleague'], $addedLeague);

				if ( $index == false && $addedIndex == false && gettype($index) == 'boolean' && gettype($addedIndex) == 'boolean' ) {
					$addLeague[] = $value;
					$addedLeague[] = $value['idleague'];
				}

			}

			// Salva as novas ligas no arquivo de já adicionado no BD
			if ( count($addLeague) > 0 ) {

				// Inserindo as novas Ligas
				$total = count($addLeague);
				$start = 0;
				$offset = 25;

				while ( $start < $total ) {

					$final = $start + $offset;

					if ( $final >= $total ) {
						$offset = $total - $start;
					}

					$leagueModel->insertLeague(array_slice($addLeague, $start, $offset));

					$start += $offset;

				}


				$f 			= new Json('Bet365/League/Ready-League');
				$time 		= new Time('America/Sao_Paulo');
				$leagues 	= $f->getVar('league');

				if( !$leagues ) $leagues = [];

				$f->setVar('update_at', $time->time()->format());

				$f->setVar('league', array_merge($leagues, $addLeague));

				$file->setVar('update', true);
				$file->setVar('update_at', $time->time()->format());
				$file->setVar('league', []);

			}
			

		}

		// echo '</pre>';


	}


}
