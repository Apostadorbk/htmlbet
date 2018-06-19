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

	private $allowed; 	// Partidas que tem liga no BD
	private $unallowed; // Partidas que não tem liga no BD

	// now = é a hora exata da requisição
	// time é até a hora maxima dos jogos a ser requisitados
	public function __construct(string $time) { // OK
		// $this->startTS = strtotime('now');
		// $this->finalTS = strtotime($time);

		$this->startTS 		= $this->getExactTime();
		$this->finalTS 		= $this->getExactTime($time);
		$this->match 		= []; 
		$this->allowed 		= []; 
		$this->unallowed 	= []; 
	
		// var_dump( date('Y-m-d H:i:s', 1490811300) );
		// var_dump( date('Y-m-d H:i:s', $this->startTS) );
		// var_dump( date('Y-m-d H:i:s', $this->finalTS) );
	}
	
	public function getExactTime(string $time = 'now'):int {

		if ( $time == 'now' ) {
			// $timestamp = strtotime(Time::currentDate('Y-m-d H:i:s', 'Europe/London'));
			$timestamp = 1490799600;
		} else {
			$timestamp = strtotime($time, $this->startTS);
		}

		$date = date('Y-m-d H:00:00', $timestamp);

		$date = strtotime($date);

		// var_dump( date( 'Y-m-d H:i:s', $date) );

		return $date;

	}

	public function readMatch(array $results):bool { // OK

		if ( empty($results) || !isset($results) ) return false;

		if ( $this->finalTS < $this->startTS ) return false;


		$time = new Time('Europe/London');

		foreach ($results as $value) {

			// $value['time'] = $time->convert($value['time'])->getTime();
			
			if ( 
				$value['time'] >= $this->startTS 
				&&
				$value['time'] <= $this->finalTS 
			) {

				array_push($this->match, [
					'idevent'		=> $value['id'],
					'dtetime'		=> $time->convert($value['time'])->format(),
					// 'dtetime'		=> date( 'Y-m-d H:i:s', $value['time'] ),
					'inttimestatus'	=> $value['time_status'],
					'idleague'		=> $value['league']['id'],
					'desleague'		=> addslashes($value['league']['name']),
					'idhometeam'	=> $value['home']['id'],
					'deshometeam'	=> addslashes($value['home']['name']),
					'idawayteam'	=> $value['away']['id'],
					'desawayteam'	=> addslashes($value['away']['name']),
					'intss'			=> $value['ss']
				]);

				// var_dump( $value['id'] );

			}

			if ( $value['time'] > $this->finalTS ) {
				return false;
			}
			
		}
		
		return true;

	}

	public function prepare(array $leagues):bool {

		if ( empty($leagues) || !isset($leagues) ) return false;

		$ids = [];

		foreach ($leagues as $key => $value) {
			$ids[] = $value['idleague'];
		}

		// var_dump( $ids );
		// exit;

		$this->allowed 		= [];
		$this->unallowed 	= [];
		
		foreach ($this->match as $value) {
			if ( in_array($value['idleague'], $ids) ) {
				$this->allowed[] = $value;
			} else {
				$this->unallowed[] = $value;
			}
		}

		$this->match = [];

		return true;

	}

	public function saveUnallowed(string $fileDir):bool {

		if ( empty($fileDir) ) return false;

		$f = new Json($fileDir);

		return $f->setVars($this->unallowed);

	}

	public function hasMatch():bool {
		return !empty($this->match) || $this->hasAllowed() || $this->hasUnallowed();
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