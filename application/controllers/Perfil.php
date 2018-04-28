<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*	Classe Perfil
*
*	Responsavel pelo perfil do usuário
*/

class Perfil extends BASE_Controller {

	public function __construct() {
		parent::__construct("Perfil");

		// Adicionar as bibliotecas que não precisam ser instanciadas
		$this->getLibrary(['constant', 'redirect', 'auth', 'session', 'file']);

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

        	// Verificação de usuario logado
        	if ( !Auth::checkLogged() )
				Redirect::route("/user/login");

			switch ($method) {

				case 'upload':

					if ( empty($params) )
						Redirect::route("/home");

					$this->upload($params[0]);
				break;

			}

			// Atualiza a URL atual acessada
			Redirect::updateURL();

        }

	}

	/*
	*	Função upload
	*
	*	Recebe a requisição de upload do perfil.
	*
	*	@author Bruno
	*	@package theo
	*
	*	@param $type é o tipo do upload no perfil, podendo ser foto por exemplo
	*	
	*/
	public function upload($type = '') {

		$this->load->model('pessoa_model', 'pessoa');

		switch ($type) {

			case 'foto-home': // Pq é um upload a partir do home e nao do perfil de fato
				
				$userFolder = implode("-", Auth::get(['idpessoa', 'deslogin']));

				$directory = "storage/".$userFolder."/perfil/foto";
				
				$fileDirectory = File::upload( $directory, Constant::FOTO_PERFIL );
					
				// Atualizando tb_pessoas
				$this->pessoa->updateData([
					'desfoto'	=> $fileDirectory
				], [
					'idpessoa'	=> Auth::get(['idpessoa'])
				]);

				Session::update(Constant::PESSOA, ['desfoto' => $fileDirectory]);
				
				
				Redirect::route("/home");
				

			break;
			
		}

	}


}
