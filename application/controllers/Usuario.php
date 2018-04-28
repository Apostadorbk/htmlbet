<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends BASE_Controller {

	public function __construct(){
		parent::__construct("Usuario");

		// Adicionar as bibliotecas que não precisam ser instanciadas
		$this->getLibrary(['constant', 'redirect', 'auth', 'session']);


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

				case 'login':

					if( Auth::checkLogged() ) {
						return Redirect::route("/home");
					}

					$this->login();

				break;
				

				case 'signup':

					if( Auth::checkLogged() ) {
						return Redirect::route("/home");
					}

					$this->login();

				break;


				case 'create': // Rota para criar um usuário no sistema

					if ( !Redirect::fromURL('/usuario/signup', 'post') ) {
						Redirect::route('/usuario/signup');
					}

					
					$this->getLibrary(['validate']);
					$this->create();
					
				break;


				case 'logon': // Rota para logar o usuario no sistema

					if ( !Redirect::fromURL('/usuario/login', 'post') ) {
						return Redirect::route("/usuario/login");
					}
					
					$this->getLibrary(['validate']);
					$this->logon();

				break;

				case 'logout':
					$this->logout();
				break;
			
			}

			// Atualiza a URL atual acessada
			Redirect::updateURL();

        }

	}

	
	/*
	*	Função login
	*
	*	Pegas as informações do usuário para logar
	*
	*	@author Bruno
	*	@package Theo
	*	
	*/
	public function login() {


		$data['title'] = "Login";
		$data['login'] = true;
		$data['signup'] = false;

		// Recuperando os dados caso haja algum dado, e limpando a sessão
		$data['input'] = Session::get([Constant::DATA_VALIDATE],    true);
		$data['errorLogin'] = Session::get([Constant::ERROR_LOGIN], true);

		/*
		echo '<pre>';
		var_dump( $data['errorLogin'] );
		echo '</pre>';
		*/
		
		$this->view([
			'form/header',
			'form/form',
			'form/footer'
		], $data);
		
	}

	/*
	*	Função signup
	*
	*	Recebe os dados do login da pesosa
	*
	*	@author Bruno
	*	@package Theo
	*	
	*/
	public function signup() {

		$data['title'] = "Cadastro";
		$data['login'] = false;
		$data['signup'] = true;

		$data['input'] = Session::get([Constant::DATA_VALIDATE],  true);
		$data['errorSignup'] = Session::get([Constant::ERROR_VALIDATE], true);
		
		$this->view([
			'form/header',
			'form/form',
			'form/footer'
		], $data);
		

	}

	/*
	*	Função create
	*
	*	Cria uma usuario no banco de dados
	*
	*	@author Bruno
	*	@package Theo
	*	
	*/
	public function create() {

		//$this->load->model('vinculo_model', 'vinculo');
		//$this->load->model('usuario_model', 'usuario');

		// Validando os tipos de cada campo, se ocorrer algum erro de validação
		// vai para a rota /usuario/signup
		Validate::allTypes([
			'desnome'		=>	$this->input->post('desnome'),
			'dessobrenome'	=> 	$this->input->post('dessobrenome'),
			'desemail'		=> 	$this->input->post('desemail'),
			'deslogin'		=>	$this->input->post('deslogin'),
			'dessenha'		=>	$this->input->post('dessenha'),
		], [
			'string',
			'string',
			'string',
			'string',
			'string'
		], 
			"/usuario/signup"
		);

		$this->load->model('usuario_model', 'usuario');
		$this->load->model('vinculo_model', 'vinculo');

		// Recuperando os dados validados, sem limpar a sessão
		// precisa-se recuperar em login ou signup
		$data = Session::get([Constant::DATA_VALIDATE]);

		// Encriptando a senha
		$data['dessenha'] = password_hash($data['dessenha'], PASSWORD_BCRYPT);

		// Inseri em tb_usuario
		$idusuario = $this->usuario->insertData([
			'desnome'		=> $data['desnome'],
			'dessobrenome'	=> $data['dessobrenome'],
			'desemail'		=> $data['desemail'],
			'deslogin'		=> $data['deslogin'],
			'dessenha'		=> $data['dessenha']
		]);

		// Se foi inserido com sucesso em tb_usuarios
		if ( $idusuario ) {

			// Inserindo um vínculo
			$idvinculo = $this->vinculo->insertData([
				'idusuario'	=> $idusuario
			]);

			
			return Redirect::route("/usuario/login");
		}
		
	}

	/*
	*	Função logon
	*
	*	Faz a autenticação do usuário no sistema
	*
	*	@author Bruno
	*	@package Theo
	*	
	*/
	public function logon() {

		Validate::allTypes([
			'deslogin'		=>	$this->input->post('deslogin'),
			'dessenha'		=>	$this->input->post('dessenha')
		], [
			'string',
			'string'
		], 
			"/usuario/login"
		);



		$this->load->model('usuario_model', 'usuario');
		$this->load->model('vinculo_model', 'vinculo');

		$data = Session::get([Constant::DATA_VALIDATE]);

		
		$usuarioData = $this->usuario->selectData('*', [
			'deslogin'	=> $data['deslogin']
		]);


		if ( !$usuarioData )
			return Redirect::route("/usuario/login",[
				Constant::ERROR_LOGIN => "Login/senha inválido(s)!"
			]);

		
		if ( $usuarioData ) {

			// Validação do usuário
			if ( !password_verify($data['dessenha'], $usuarioData['dessenha']) ) {
				return Redirect::route(
					"/usuario/login", [
					Constant::ERROR_LOGIN => "Login/senha inválido(s)!"
				]);
			}
			
			// Retirando a senha
			$usuarioData['dessenha'] = '';

			$vinculoData = $this->vinculo->getVinculos([
				'idvinculo', 'intvinculo', 'intprivilegio'
			], [
				'idusuario'	=> $usuarioData['idusuario']
			]);

			// Juntando os dados da usuario com os do vinculo
			$usuarioData = array_merge($usuarioData, $vinculoData);

			// Atualizando o ultimo login
			$this->usuario->updateData([
				'dteultimologin'	=> strtotime("now")
			], [
				'idusuario'	=> $usuarioData['idusuario']
			]);

			return Redirect::route(
				"/home", [
					Constant::usuario => $usuarioData // Setando a usuario
				]
			);

		}

	}
	
	/*
	*	Função logout
	*
	*	Retira ume usuario do sistema
	*
	*	@author Bruno
	*	@package Theo
	*	
	*/
	public function logout() {
		Session::clear([
			Constant::USUARIO, 
			Constant::DATA_VALIDATE, 
			Constant::ERROR_VALIDATE,
			Constant::ERROR_LOGIN
		]);

		return Redirect::route("/home");
	}

}
