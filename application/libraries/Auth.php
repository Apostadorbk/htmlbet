<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*	Classe Auth
*
*	classe responsavel de manipular quem tá autenticado
*/
class Auth {
	
	private static $usuario = [];

	/*
	*	Função get
	*
	*	Pega valores com as chaves correspondente passado no parametros
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $keys é um array com as chaves solicitadas do usuário
	*
	*	@return $usuario é um array com os valores com as mesmas chaves passada em $keys,
	*	se existir é retorna um valor nessa chave , caso contrário é retorno NULL,
	*	se $keys for um array vazio será retornado todos os dados da usuario
	*	
	*/
	public static function get($keys = [], $array = true) {

		if ( isset($_SESSION[Constant::USUARIO]) ) {

			self::$usuario = [];

			if ( empty($keys) )
				return self::$usuario = $_SESSION[Constant::USUARIO];

			foreach ($keys as $key) {

				if ( isset($_SESSION[Constant::USUARIO][$key]) ) {
					self::$usuario[$key] = $_SESSION[Constant::USUARIO][$key];
				} else {
					self::$usuario[$key] = NULL;
				}
				
			}

			if ( !$array )
				return self::$usuario[$keys[0]];

			return self::$usuario;

		}

		return NULL;
	
	}

	/*
	*	Função recuperarusuario
	*
	*	Recupera os dados do usuário diretamente da sessão
	*
	*	@author Bruno
	*	@package Theo
	*	
	*/
	/*
	public static function recuperarusuario() {
		self::$usuario = Session::get([Constant::USUARIO]);
	}
	*/
	
	
	/*
	*	Função checkLogged
	*
	*	Verifica se um usuário está logado ou não
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@return retorna true se um usuário ta logado, caso contrário retorna false
	*	
	*/
	public static function checkLogged() {

		// Atualizando os dados do usuário
		self::get([Constant::IDUSUARIO]);
		
		if ( 
			!isset(self::$usuario[Constant::IDUSUARIO])
			||
			empty(self::$usuario[Constant::IDUSUARIO])
			||
			(int)self::$usuario[Constant::IDUSUARIO] < 1	
		)
			return false;
		

		return true;
		

	}

	/*
	*	Função checkVinculo
	*
	*	Verificar o vinculo da usuario
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $vinculo é o vinculo a qual precisa ser checado
	*
	*	@return é retornado true se o vinculo correspoder ao do usuário
	*	ou retorn false se o vinculo retorna false;
	*	
	*/
	public static function checkVinculo($vinculo) {
		self::get([Constant::VINCULO]);

		foreach (self::$usuario[Constant::VINCULO] as $value) {
			if ( $vinculo == $value ) {
				return true;
			}
		}

		return false;
	}

}


 ?>