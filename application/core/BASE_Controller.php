<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class BASE_Controller extends CI_Controller {

	protected $controller;
	protected $baseView;

	protected $options = [
		'template' 	=> [],
		'data'		=> []
	];

	// Conexão com o BD
	protected $conn;

	/*
	*	Função __construct
	*
	*	Seta dados iniciais como o nome do controlador e o caminho dele
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $controller é uma string com o nome do controlador
	*	
	*/
	public function __construct($controller){
		parent::__construct();

		// Setando o nome do controlador
		$this->controller = ucfirst($controller);

		// Caminho padrão das views de cada controlador
		$this->baseView = $this->controller.'/';
	}

	/*
	* 	Função view
	*
	* 	Printa todas as views passadas e seus dados referente
	*
	*	@package THeo 
	*	@author Bruno
	*
	*	@param $template é os nomes das views	
	*	@param $data é os dados que vai ser passado para as views
	*
	*/
	protected function view($template = [], $data = []) {

		$this->options['template'] = $template;
		$this->options['data'] = $data;

		if ( count($this->options['template']) > 0 ) {

			$this->load->view(
				$this->baseView.$this->options['template'][0], 
				$this->options['data']
			);

			for ($i=1; $i < count($this->options['template']); $i++) { 
				$this->load->view(
					$this->baseView.$this->options['template'][$i]
				);
			}

		}

	}

	/*
	*	Função getLibrary
	*
	*	Incluir bibliotecas que nao precisam ser instanciadas.
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $classNames é um array com os nomes das classes a serem incluidas
	*	
	*/
	protected function getLibrary($classNames = []) {
		foreach ($classNames as $names) {
			require_once APPPATH.'libraries/'.ucfirst($names).'.php';
		}
	}


	protected function getModel($className = '') {
		if ( empty($className) )
			return false;

		require_once APPPATH.'models\\'.ucfirst($className).'_model.php';
		$class = ucfirst($className).'_model';
		return new $class;
	}
	
}

?>