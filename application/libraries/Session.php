<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*	Classe Session
*
*	É uma classe que manipula a sessão
*/

class Session {

	/*
	*	Função init
	*
	*	Iniciar a sessão
	*
	*	@author Bruno
	*	@package Theo
	*	
	*/
	public static function init() {
		session_start();
	}

	/*
	*	Função recovery
	*
	*	É uma função que recupera uma sessão se ela existir através do ID
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $id é o valor da ID da sessão que está salva no BD
	*
	*	@return é recuperado e retornado o ID da sessão caso exista, caso nao exista é retorno false
	*	
	*/
	public static function recovery($id = NULL) {

		if ( isset($id) )
			return session_id($id);

		return false;

	}

	/*
	*	Função set
	*
	*	Seta os dados na sessão;
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $values é o array associativo com a chave e valor que serão passados para a sessão
	*	com as mesmas chaves e valores de $values correspondente
	*
	*	@return retorna true se for passado algo para ser colocado na sessão, caso contrário retorna false
	*	
	*/
	public static function set($values = []):bool {
		
		if ( count($values) > 0 ) {
			foreach ($values as $key => $value) {
				$_SESSION[$key] = $value;
			}

			return true;
		}

		return false;

	}


	/*
	*	Função get
	*
	*	É retornado os valores requisitados em $keys
	*	
	*	@author Bruno
	*	@package Theo
	*
	*	@param $keys e um array com as chaves que serão requisitadas na sessão
	*
	*	@return é retornado $values um array associativo com as chaves as mesmas de $keys e 
	*	com os valores correspodente se tiver setado na sessão, se não existe a chave na sessão
	*	é setado na chave um valor NULL; Caso não seja passada nada pra função é retornado array vazio;
	*/
	public static function get($keys = [], $clear = false) {

		$values = [];

		if ( count($keys) > 0 ) {

			if ( count($keys) === 1 ) {

				if ( isset($_SESSION[$keys[0]]) ) {
					$values = $_SESSION[$keys[0]];
				} else {
					$values = NULL;
				}

			} else {

				foreach ($keys as $key) {

					if ( isset($_SESSION[$key]) ) {
						$values[$key] = $_SESSION[$key];
					} else {
						$values[$key] = NULL;
					}
					
				}

			}

		}

		if ( $clear ) {
			self::clear($keys);
		}

		return $values;

	}

	/*
	*	Função clear
	*
	*	Limpa um conjunto de chaves na sessão
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $keys é um array associativo com as chaves a serem limpadas da sessão;
	*	
	*/
	public static function clear($keys = []) {

		foreach ($keys as $key)
			unset($_SESSION[$key]);

		return;

	}
	
}

?>