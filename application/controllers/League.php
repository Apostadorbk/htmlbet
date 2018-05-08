<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class League extends BASE_Controller {

	public function __construct(){
		parent::__construct("league");

		// Adicionar as bibliotecas que não precisam ser instanciadas
		$this->getLibrary(['constant', 'apileague']);

		// Inicialização da sessão
		//Session::init();
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

				case 'getleagues':
					$this->getleagues();
				break;

				// Admin
				case 'set':
					$this->set();
				break;

				case 'getleaguebycountry':
					$this->getleaguebycountry($params[0]);
				break;

				case 'setactivebycountry':
					$this->setactivebycountry($params[0]);
				break;

				// Admin
				case 'check':
					$this->check();
				break;

				// Teste
				default:
					$this->teste($params[0]);
				break;

			}

			// Atualiza a URL atual acessada
			//Redirect::updateURL();

        }

	}
	
	// Setando os dados de ligas no BD
	public function set() {

		$md_league = $this->getModel('league');


		$all_countries = APILeague::getCountries();
		$all_leagues = APILeague::getLeagues();

		$bd_leagues = $md_league->getLeagues(false); // Pegando todos as ligas independente se tá ativo ou não
		
		$format_leagues = [];

		foreach ($bd_leagues as $key => $league) {
			$format_leagues[$league['idcountry']][$league['idleague']] = [
				$all_countries[$league['idcountry']],
				$league['desleague']
			];
		}
		
		$diff_league = []; // Salvar no BD
		
		foreach ($all_leagues as $keyCountry => $country) {
			if ( isset($format_leagues[$keyCountry]) ) {
				$diff_league[$keyCountry] = array_diff_key($country, $format_leagues[$keyCountry]);
			} else {
				$diff_league[$keyCountry] = $country;
			}
		}

		echo '<pre>';
		var_dump( $md_league->setLeagues($diff_league) );
		echo '</pre>';

	}
	
	public function index() {

		//echo 'Index de Country';
		
	}

	public function getleagues() { // OK
		$league = $this->getModel('league');

		$response = $league->getLeagues();

		//echo '<pre>';
		echo json_encode([
			'status' => (!$response) ? false : true,
			'response' => $response
		]);
	}

	public function getleaguebycountry($idcountry) { //OK
		
		$league = $this->getModel('league');

		$response = $league->getLeagueByCountry($idcountry);

		//echo '<pre>';
		echo json_encode([
			'status' => (!$response) ? false : true,
			'response' => $response
		]);
		//echo '</pre>';
		
	}


	public function setactivebycountry($idcountry) { // OK
		$league = $this->getModel('league');
		echo json_encode([
			'status' => $league->setActiveByCountry($idcountry)
		]);
	}

	/*
	*	Função getData
	*
	*	Pega os dados no formato json
	*
	*	@author Bruno
	*	@package Elite Sports
	*
	*	@return retorna o um array proveniente do json decodificado
	*	
	*/
	private function getData() { // OK
		$data               = file_get_contents("php://input");
		$dataJsonDecode     = json_decode($data, true);

		return $dataJsonDecode;
	}

	// Verificando as ligas da API e das Constant_Leagues.php
	public function check() {

		echo '<pre>';
		var_dump( APILeague::check() );
		echo '</pre>';

	}


}
