<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teste extends BASE_Controller {

	public function __construct()
	{
		parent::__construct("teste");

		$this->getLibrary(['session', 'auth', 'constant']);

		Session::init();
	}

	public function testeuser() {
		//Session::set([ Constant::PESSOA => ['idpessoa' => '1', 'deslogin' => 'bjnb'] ]);

		//Auth::get();

		//var_dump( Session::get([Constant::PESSOA]) );

		//var_dump( Auth::get() );

		/*
		echo '<pre>';
		var_dump( $_SESSION );
		echo '</pre>';
		*/

		//Session::clear(['teste']);
	}

	public function printuser() {
		//Session::set(['teste' => "Testando"]);

		/*
		echo '<pre>';
		var_dump( Auth::get() );
		echo '</pre>';
		*/

		var_dump( Auth::getData() );
	}


	public function file() {
		$this->load->view("teste/files");
	}

	public function getfiles() {

		$login = 'bjnb';

		if ( isset($_FILES['file']) ) {

			$number_total = count($_FILES['file']['tmp_name']);

			// Verifica se já há uma pasta unica para o login da pessoa
			if ( !file_exists(__DIR__.'/../../storage/'.$login) ) {
				mkdir(__DIR__.'/../../storage/'.$login);
			}

			if ( $number_total > 1 ) {

				for($i = 0; $i < $number_total; $i++) {
					
					$filename = $_FILES['file']['tmp_name'][$i];

					$destination = __DIR__."/../../storage/".$login."/".time().'-'.$_FILES['file']['name'][$i];

					move_uploaded_file($filename, $destination);
					
				}
				
			} else if ( $number_total == 1 ) {

				$filename = $_FILES['file']['tmp_name'][0];

				$destination = __DIR__."/../../storage/".$login."/".time().'-'.$_FILES['file']['name'][0];

				move_uploaded_file($filename, $destination);

			}

		}

	}

	public function teste() {

		echo '<pre>';
		var_dump( strtotime("now") );
		echo '</pre>';

	} 

}
