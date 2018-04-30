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

				case 'setleague':
					$this->setleague($params[0]);
				break;

				case 'getleaguebycountry':
					$this->getleaguebycountry($params[0]);
				break;

				case 'setactivebycountry':
					$this->setactivebycountry($params[0]);
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

	public function setleague($idcountry) { // OK

		$data['_idcountry'] = $idcountry;
		$data['_leagues'] 	= APILeague::getByCountry($idcountry, true);
		$data['leagues'] 	= APILeague::league($idcountry);

		$league = $this->getModel('league');

		echo json_encode( ['status' => $league->setLeagues($data)] );

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

	public function teste($id) {

	}


}
