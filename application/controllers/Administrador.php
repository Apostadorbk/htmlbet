<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrador extends BASE_Controller {

	public function __construct(){
		parent::__construct("administrador");

		// Adicionar as bibliotecas que não precisam ser instanciadas
		$this->getLibrary(['constant', 'redirect', 'session', 'auth']);

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
	
			/*
			// Checagem se ta logado
			if ( !Auth::checkLogged() ) {
				Redirect::route('/pessoa/login');
			}

			// Checagem de vinculo
			if ( !Auth::checkVinculo(Constant::VINCULO['ADMINISTRADOR']['CODIGO']) ) {
				Redirect::route('/home');
			}
			*/

			switch ($method) {

				// Função padrão
				case 'index':
					$this->index();
				break;

				case 'precadastro':
					$this->precadastro();
				break;

			}

			// Atualiza a URL atual acessada
			//Redirect::updateURL();

        }

	}
	
	
	public function index()
	{
		
		$data['title'] = "ES | Administrador";

		$data['user'] = Auth::get();

		/*
		echo '<pre>';
		var_dump($data['user']);
		echo '</pre>';
		exit;
		*/

		$this->view([
			'header',
			'template-starter',
			'footer'
		], $data);
		
	}

	/*
	*	Função precadastro
	*
	*	Precadastra um usuario
	*
	*	@author Bruno
	*	@package Elite Sports
	*
	*	@return retorna o status e a message e possivelmente os dados novamente se
	*	tiver acontecido um erro
	*	
	*/
	public function precadastro () { // OK funcionando

		$admin = $this->getModel('administrador');

		$data = $this->getData();

		$response = [];

		if ( $admin->precadastro($data) ) {
			$response['status'] = true;
			$response['message'] = "Email(s) cadastro(s) com sucesso!";
		} else {
			$response['status'] = false;
			$response['message'] = "Erro ao cadastrar o(s) email(s)";
			$response['data'] = $data;
		}

		echo json_encode($response);

	}

	public function seteventbycountry() {
		$response = $this->getData();

		
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
	private function getData() {
		$data               = file_get_contents("php://input");
		$dataJsonDecode     = json_decode($data, true);

		return $dataJsonDecode;
	}


}
