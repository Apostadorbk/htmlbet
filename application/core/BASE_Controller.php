<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

define("DS", DIRECTORY_SEPARATOR);

define("LIBRARY", [
	'Redirect' 	=> APPPATH."libraries".DS."Redirect.php",
	'Auth' 		=> APPPATH."libraries".DS."Auth.php",
	'Session'	=> APPPATH."libraries".DS."Session.php",
	'Page'		=> APPPATH."libraries".DS."Page.php",
	'Json'		=> APPPATH."libraries".DS."Json.php",
	'Database'	=> APPPATH."libraries".DS."Database.php",
	'Constant'	=> APPPATH."libraries".DS."Constant.php",
	'Time'		=> APPPATH."libraries".DS."Time.php",
	'TimeZone'	=> APPPATH."libraries".DS."TimeZone.php",
	'Bet365'	=> APPPATH."libraries".DS."API".DS."Bet365.php"
]);

define("MODEL", [
	'Administrador' 	=> APPPATH."models".DS."Administrador_model.php",
	'Country' 			=> APPPATH."models".DS."Country_model.php",
	'Event'				=> APPPATH."models".DS."Event_model.php",
	'League'			=> APPPATH."models".DS."League_model.php",
	'Usuario'			=> APPPATH."models".DS."Usuario_model.php",
	'Vinculo'			=> APPPATH."models".DS."Vinculo_model.php",
	'Teste'				=> APPPATH."models".DS."Teste_model.php"
]);

// require_once MODEL['Teste'];

/*
require_once APPPATH."libraries".DS."Redirect.php";
require_once APPPATH."libraries".DS."Auth.php";
require_once APPPATH."libraries".DS."Session.php";
require_once APPPATH."libraries".DS."Page.php";
require_once APPPATH."libraries".DS."Json.php";
require_once APPPATH."libraries".DS."Database.php";
require_once APPPATH."libraries".DS."Constant.php";
require_once APPPATH."libraries".DS."Time.php";
require_once APPPATH."libraries".DS."API".DS."Bet365.php";
*/

// require APPPATH."libraries".DS."Library.php";

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
	public function __construct($controller) {
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
	protected function view($template = [], $data = []):string {

		$this->options['template'] = $template;
		$this->options['data'] = $data;

		$html = "";

		if ( count($this->options['template']) > 0 ) {

			$html .= $this->load->view(
				$this->baseView.$this->options['template'][0], 
				$this->options['data'],
				true
			);

			for ($i=1; $i < count($this->options['template']); $i++) { 
				$html .= $this->load->view(
					$this->baseView.$this->options['template'][$i],
					'',
					true
				);
			}

		}

		return $html;
	}

	protected function cachePage(string $filename, int $time = 0) {
		return new Page($filename, $time);
	}
	
	protected function cache(string $filename, int $time = 0) {
		return new Json($filename, $time);
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
	protected function getLibrary(array $classNames = []) {
		
		foreach ($classNames as $class) {
			if ( isset(LIBRARY[$class]) ) {
				require_once LIBRARY[$class];
			} else {
				throw new \Exception("Library {$class} not found", 100);
			}
		}

	}

	protected function getModel(array $classNames = []) {

		foreach ($classNames as $value) {
			if ( isset(MODEL[$value]) ) {
				require_once MODEL[$value];
			} else {
				throw new \Exception("Model {$value} not found", 200);
			}
		}

	}

	protected function model(string $modelName) {
		$name = $modelName.'_model';
		return new $name();
	}

	protected function getData():array {
		$data               = file_get_contents("php://input");
		$dataJsonDecode     = json_decode($data, true);

		return $dataJsonDecode;
	}
	
}

?>