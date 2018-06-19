<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends BASE_Controller {

	public function __construct(){
		parent::__construct("Home");

		// Adicionar as bibliotecas que não precisam ser instanciadas

		$this->getLibrary(['Time']);

		// Inicialização da sessão
		// Session::init();
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
					/*
					// Se tiver logado
					if ( Auth::checkLogged() ) {

						$this->logged();

					} else { // Se não tiver logado

						$this->index();

					}
					*/

				break;

				default:
					$this->teste();
				break;
			}

			// Atualiza a URL atual acessada
			// Redirect::updateURL();

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

		// $data['user'] = Auth::get();
		
		echo $this->view([
			'header',
			'Logged/template',
			'footer'
		], $data);
	
	}

	public function teste() {

		$start = microtime(true);

		// $this->getLibrary(['Time']);
		
		//------------------------------------------------------------------------
		//------------------------------------------------------------------------
		//------------------------------------------------------------------------

		echo '<pre>';
		
		$d1 = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
		$d2 = new DateTime('now', new DateTimeZone('Australia/Sydney'));
		// $d2 = new DateTime('now', new DateTimeZone('Europe/London'));
		// $d2 = new DateTime('now', new DateTimeZone('UTC'));
		// $d2 = new DateTime('now', new DateTimeZone('America/Los_Angeles'));

		$offset1 = $d1->getOffset();
		$offset2 = $d2->getOffset();
		
		// $diff=$d2->diff($d1);

		// var_dump( $diff );

		//--------------------------------------------------

		// Calculando o fuso horário GMT+0
		$timeOfDay = gettimeofday();

		$GMT = $timeOfDay['sec'] + ($timeOfDay['minuteswest']*60);

		// var_dump( getdate($GMT) );
		echo '<h1>GMT</h1>';
		echo '<br>';

		// var_dump( $timeOfDay );
		var_dump( date('Y-m-d H:i:s', $GMT) );
		// var_dump( getdate($GMT) );

		echo '<hr>';

		//--------------------------------------------------
		
		
		// Calculando horario local
		$localTime = $GMT + $offset1;

		echo '<h1>Recife</h1>';
		echo '<br>';

		// var_dump( getdate($localTime) );
		var_dump( date('Y-m-d H:i:s', $localTime) );

		echo '<hr>';

		// -------------------------------------------------
		
		// Calculando horario de um lugar qualquer
		$targetTime = $GMT + $offset2;

		echo '<h1>Sydney</h1>';
		echo '<br>';

		// var_dump( getdate($targetTime) );
		var_dump( date('Y-m-d H:i:s', $targetTime) );

		echo '<hr>';

		// -------------------------------------------------
		

		$diff = ($offset1 - $offset2);


		$initialTime = $targetTime;

		// Calculando horario de um lugar qualquer
		$finalTime = $initialTime + $diff;

		echo '<h1>Recife</h1>';
		echo '<br>';

		// var_dump( getdate($finalTime) );
		var_dump( date('Y-m-d H:i:s', $finalTime) );

		echo '<hr>';


		var_dump( $finalTime );

		// -------------------------------------------------
		// -------------------------------------------------
		// -------------------------------------------------

		// Library::get('Time');

		// $p = new Time('UTC');
		$p = new Time('Europe/London');

		var_dump( $p );

		echo '>> ';
		var_dump( $p->convert(1490810400)->format() );


		echo '<hr>';
		echo '<hr>';

		/*
		echo '<h1>Teste</h1>';
		echo '<br>';

		// var_dump( getdate($finalTime) );
		var_dump( date('Y-m-d H:i:s', 1490799600) );

		echo '<hr>';

		// -------------------------------------------------
		*/

		echo '<br><br>';

		echo '<hr>';
		echo 'Time Elapsed: ';
		$time_elapsed_secs = microtime(true) - $start;
		var_dump( $time_elapsed_secs );
	}

}

