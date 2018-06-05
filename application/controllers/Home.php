<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends BASE_Controller {

	public function __construct(){
		parent::__construct("Home");

		// Adicionar as bibliotecas que não precisam ser instanciadas
		$this->getLibrary(['redirect', 'constant', 'session', 'auth']);

		// Inicialização da sessão
		Session::init();
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

					// Se tiver logado
					if ( Auth::checkLogged() ) {

						$this->logged();

					} else { // Se não tiver logado

						$this->index();

					}

				break;

			}

			// Atualiza a URL atual acessada
			Redirect::updateURL();

        }

	}
	
	/*
	*	Função index
	*
	*	Somente exibir a página principal
	*
	*	@author Bruno
	*	@package Theo
	*
	*/
	public function index() {
		
		$page = $this->cachePage('Home/index');

		if ( !$page->isValid() ) {

			$data["title"] = "Elite Sports";
			
			$page->setCache($this->view([
				'header',
				'template',
				'footer'
			], $data));
			
		}
		
		echo $page->getCache();
		
	}

	public function logged() {

		$data['title'] = "Elite Sports";

		$data['user'] = Auth::get();
		
		echo $this->view([
			'header',
			'Logged/template',
			'footer'
		], $data);
	
	}

}

