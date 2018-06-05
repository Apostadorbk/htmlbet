<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends BASE_Controller {

	public function __construct() {
		parent::__construct("event");

		// Adicionar as bibliotecas que não precisam ser instanciadas
		$this->getLibrary(['constant', 'apievent', 'apileague', 'time']);

		// Inicialização da sessão
		//Session::init();
		// Time::setTimeZone('PE');
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

				case 'seteventbycountry':
					$this->seteventbycountry();
				break;

				

				// Teste
				default:
					$this->teste();
				break;

			}

			// Atualiza a URL atual acessada
			//Redirect::updateURL();

        }

	}

	public function seteventbycountry() {

		//$params = $this->getData();
		
		
		
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

	public function teste() {
		// dados do usuario
		// $params['idcountry'] = '169';
		// $params['idcountry'] = '170';
		$params['idcountry'] = '222';
		// $params['from'] = '2018-05-04';
		
		$_result = APIEvent::event($params);

		// $_result['data'] = APIEvent::formatEvents($_result['data']);

		
		echo "<pre>";
		var_dump( $_result );
		echo "</pre>";
		

		//echo json_encode(APILeague::getAllLeagues());



	}


}
