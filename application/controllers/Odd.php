<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Odd extends BASE_Controller {

	public function __construct(){
		parent::__construct("odd");

		// Adicionar as bibliotecas que não precisam ser instanciadas
		$this->getLibrary(['constant', 'apievent', 'apiodd', 'time']);

		// Inicialização da sessão
		//Session::init();
		Time::setTimeZone('DF');
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

				case 'get':
					$this->get();
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
	
	
	public function index() {

		//echo 'Index de Country';
		
	}

	public function get() {

		
		$response = $this->getData();
		
		$response['from'] 	= Time::getDate();
		$response['to'] 	= Time::getDate([
			'interval' => [
				'day' => 2
			]
		]);
		
		
		//$result = APIEvent::event($response);

		//echo 'Odd - Get';

		echo json_encode( $response );
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

		$params = [
			'idmatch' => '284637'
		];

		echo '<pre>';
		var_dump(APIOdd::odd($params));
		echo '</pre>';
	}


}
