<?php 
/*
	Está classe fará toda logica que usa ligas e com os arquivos cache
*/
class League {

	public function __construct() {
		// Library::get('Json');

		// $this->rLeague = new Json('Bet365/League/Ready-League');
		// $this->uLeague = new Json('Bet365/League/Unready-League');
		echo 'Criando um objeto League'.'<br>';
	}

	/*
	public function readLeague($result):bool {
		
		$this->rLeague->getVars();

		$new_leagues = [];

		foreach ($result['results'] as $match) {

			$id 	= $match['league']['id'];
			$name 	= $match['league']['name'];

			
			if ( !$this->rLeague->hasValue($id) ) {
				
				$new_leagues[$id] = [
					'id' 	=> $id,
					'name' 	=> $name
				];

			}

		}
	
		return $this->uLeague->setVars($new_leagues);
		
	}
	*/
	
	public function teste($league) {
		
		$league->teste();

	}

}

?>