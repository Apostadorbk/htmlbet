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
class UpcomingEvent {

	private $startTS;
	private $finalTS;
	private $match; 	// Todas as partidas
	private $time;

	private $allowed; 	// Partidas que tem liga no BD
	private $unallowed; // Partidas que não tem liga no BD

	// now = é a hora exata da requisição
	// time é até a hora maxima dos jogos a ser requisitados
	public function __construct(string $time = '+2 hours') { // OK

		// Pegando a hora de Londres
		$t = new Time('Europe/London');

		$this->startTS 		= $t->time()->get();
		$this->finalTS 		= $t->time()->get($time);

		// Convertendo para a hora local
		$t = new TimeZone('Europe/London', 'America/Sao_Paulo');

		$this->startTS 		= $t->convert($this->startTS)->getTime();
		$this->finalTS 		= $t->convert($this->finalTS)->getTime();


		$this->match 		= []; 
		$this->allowed 		= []; 
		$this->unallowed 	= []; 
		$this->time 		= $time;
	
	}
	
	/*
	private function getExactTime(string $time = 'now', string $timezone = 'America/Sao_Paulo'):int { // OK

		// date_default_timezone_set($timezone);

		if ( $time == 'now' ) {
			$timestamp = Time::currentTime($timezone);
			// $timestamp = 1490799600;
		} else {
			$timestamp = strtotime($time, $this->startTS);
		}

		$date = date('Y-m-d H:00:00', $timestamp);

		$date = strtotime($date);

		return $date;

	}
	*/

	public function readMatch(array $results):bool { // OK

		if ( empty($results) || !isset($results) ) return false;

		if ( $this->finalTS < $this->startTS ) return false;

		$tz = new TimeZone('Europe/London', 'America/Sao_Paulo');

		foreach ($results as $value) {

			$value['dtetime'] 	= $tz->convert($value['time'])->format();
			$value['time'] 		= $tz->convert($value['time'])->getTime();

			if (
					$value['time'] >= $this->startTS 
					&& 
					$value['time'] <= $this->finalTS 
			) {

				array_push($this->match, [
					'idevent'		=> $value['id'],
					'inttime'		=> $value['time'],
					'inttimestatus' => $value['time_status'],
					'idleague'		=> $value['league']['id'],
					'desleague'		=> addslashes($value['league']['name']),
					'idhometeam'	=> $value['home']['id'],
					'deshometeam'	=> addslashes($value['home']['name']),
					'idawayteam'	=> $value['away']['id'],
					'desawayteam'	=> addslashes($value['away']['name']),
					'desss'			=> $value['ss'],
					'idourevent' 	=> $value['our_event_id'],

					'dtetime'		=> $value['dtetime']
				]);

				/*
				echo 'Campeonato: '.$value['league']['name'].'<br>';
				echo $value['home']['name'].' vs '.$value['away']['name'].'<br>';
				echo 'Horario: '.$value['dtetime'].'<br>';
				echo 'Horario em TimeStamp: '.$value['time'].'<br>';
				echo 'Hora Inicial: '.$this->startTS.'<br>';
				echo 'Hora Final: '.$this->finalTS.'<br>';
				echo '<hr>';
				*/
			}

			if ( $value['time'] > $this->finalTS ) {
				return false;
			}

		}

		return true;

		/*
		echo '<hr><hr><hr>';

		foreach ($results as $value) {
			
			$value['dtetime'] 	= $time->convert($value['time'])->format();
			$value['time'] 		= $time->convert($value['time'])->getTime();

			if ( 
					$value['time'] >= $this->startTS 
					&& 
					$value['time'] <= $this->finalTS 
			) {
				echo 'Campeonato: '.$value['league']['name'].'<br>';
				echo $value['home']['name'].' vs '.$value['away']['name'].'<br>';
				echo 'Horario: '.$value['dtetime'].'<br>';
				echo 'Horario em TimeStamp: '.$value['time'];
				echo '<hr>';
			}
		}
		*/


		/*


		foreach ($results as $value) {

			$value['dtetime'] = $time->convert($value['time'])->getTime();
			
			// A comparação do tempo é em relação ao horário de Londres
			if ( 
				$value['dtetime'] >= $this->startTS 
				&&
				$value['dtetime'] <= $this->finalTS 
			) {
			
				array_push($this->match, [
					'idevent'		=> $value['id'],
					'dtetime'		=> $value['dtetime'],
					// 'dtetime'		=> date( 'Y-m-d H:i:s', $value['time'] ),
					'inttimestatus'	=> $value['time_status'],
					'idleagueapi'	=> $value['league']['id'],
					'desleague'		=> addslashes($value['league']['name']),
					'idhometeam'	=> $value['home']['id'],
					'deshometeam'	=> addslashes($value['home']['name']),
					'idawayteam'	=> $value['away']['id'],
					'desawayteam'	=> addslashes($value['away']['name']),
					'intss'			=> $value['ss']
					// 'time'			=> $value['time']
				]);
			

				// var_dump( $value['id'] );

			}

			if ( $value['dtetime'] > $this->finalTS ) {
				return false;
			}
			
		}
		*/
		
		// return true;

	}

	public function prepare(array $league):bool { // OK

		if ( empty($league) || !isset($league) ) return false;

		$this->allowed 		= [];
		$this->unallowed 	= [];
		
		foreach ($this->match as $value) {
			
			$index = array_search($value['idleague'], $league['idleague']);

			if ( $index >= 0 && gettype($index) == 'integer' ) {

				$value['idmyleague'] 	= $league['idmyleague'][$index];
				$value['desleague'] 	= $league['desmyleague'][$index];
				$this->allowed[] 		= $value;

			} else {

				$this->unallowed[] 		= $value;

			}

		}

		/*
		var_dump($this->allowed);
		echo '<hr>';
		var_dump($this->unallowed);
		exit;
		*/

		// Desalocando as partidas já classificadas
		$this->match = [];

		return true;

	}

	public function saveUnallowed(string $fileDir):bool { // OK

		if ( empty($fileDir) ) return false;

		$f = new Json($fileDir);

		if ( !($leagues = $f->getVar('league')) ) {
			$leagues = [];
		}

		foreach ($this->unallowed as $value) {
			
			$leagues[] = [
				'idleague'		=> $value['idleague'],
				'desleague' 	=> $value['desleague']
			];

		}

		$this->unallowed = [];

		return $f->setVar('league', $leagues);

	}


	public function hasMatch():bool {
		return !empty($this->match);
	}

	
	public function hasAllowed() {
		return !empty($this->allowed);
	}

	
	public function hasUnallowed() {
		return !empty($this->unallowed);
	}

	public function getAllowed() {
		return $this->allowed;
	}

	public function getUnallowed() {
		return $this->unallowed;
	}

}

?>