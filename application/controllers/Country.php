<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Country extends BASE_Controller {

	public function __construct(){
		parent::__construct("country");

		// Adicionar as bibliotecas que não precisam ser instanciadas
		$this->getLibrary(['constant', 'apicountry']);

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

				case 'get':
					$this->get();
				break;

				case 'getall':
					$this->getall();
				break;

				/*
				case 'setcountry':
					$this->setcountry();
				break;
				*/

				// Admin
				case 'check':
					$this->check();
				break;

				// Teste
				default:

				break;

			}

			// Atualiza a URL atual acessada
			//Redirect::updateURL();

        }

	}
	
	
	public function index() {
		/*
		echo '<pre>';
		var_dump( APICountry::getById('166') );
		echo '</pre>';
		*/	
	}

	public function get() {

		$country = $this->getModel('country');

		echo json_encode($country->get());
	}

	// Pega todos os paises ativos
	public function getall() { // OK

		$country = $this->getModel('country');

		// echo '<pre>';
		echo json_encode($country->getAll());
		// echo '</pre>';
	}

	/*
	public function setcountry() { // OK

		$country = $this->getModel('country');

		$results 		= APICountry::country();
		$listCountry 	= APICountry::getAll(true);


		$country->setCountry($results, $listCountry);

	}
	*/

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
	private function getData() {
		$data               = file_get_contents("php://input");
		$dataJsonDecode     = json_decode($data, true);

		return $dataJsonDecode;
	}

	/*
		Verifica se há nova atualização de países da API
	*/
	public function check() {

		echo '<pre>';
		var_dump( APICountry::check() );
		echo '</pre>';
		
	}

}
